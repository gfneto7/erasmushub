<?php

namespace App\Services;

use App\Models\City;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Fallback approximate coordinates for well-known Erasmus cities.
     */
    protected array $cityCoordinates = [
        'lisboa' => ['lat' => 38.7223, 'lon' => -9.1393, 'name' => 'Lisboa'],
        'lisbon' => ['lat' => 38.7223, 'lon' => -9.1393, 'name' => 'Lisboa'],
        'porto' => ['lat' => 41.1579, 'lon' => -8.6291, 'name' => 'Porto'],
        'coimbra' => ['lat' => 40.2033, 'lon' => -8.4103, 'name' => 'Coimbra'],
        'braga' => ['lat' => 41.5454, 'lon' => -8.4265, 'name' => 'Braga'],
        'zagreb' => ['lat' => 45.8150, 'lon' => 15.9819, 'name' => 'Zagreb'],
        'split' => ['lat' => 43.5081, 'lon' => 16.4402, 'name' => 'Split'],
        'dubrovnik' => ['lat' => 42.6507, 'lon' => 18.0944, 'name' => 'Dubrovnik'],
        'madrid' => ['lat' => 40.4168, 'lon' => -3.7038, 'name' => 'Madrid'],
        'barcelona' => ['lat' => 41.3851, 'lon' => 2.1734, 'name' => 'Barcelona'],
        'valencia' => ['lat' => 39.4699, 'lon' => -0.3763, 'name' => 'Valência'],
        'paris' => ['lat' => 48.8566, 'lon' => 2.3522, 'name' => 'Paris'],
        'lyon' => ['lat' => 45.7640, 'lon' => 4.8357, 'name' => 'Lyon'],
        'rome' => ['lat' => 41.9028, 'lon' => 12.4964, 'name' => 'Roma'],
        'roma' => ['lat' => 41.9028, 'lon' => 12.4964, 'name' => 'Roma'],
        'bologna' => ['lat' => 44.4949, 'lon' => 11.3426, 'name' => 'Bolonha'],
        'bolonha' => ['lat' => 44.4949, 'lon' => 11.3426, 'name' => 'Bolonha'],
        'berlin' => ['lat' => 52.5200, 'lon' => 13.4050, 'name' => 'Berlim'],
        'munich' => ['lat' => 48.1351, 'lon' => 11.5820, 'name' => 'Munique'],
        'prague' => ['lat' => 50.0755, 'lon' => 14.4378, 'name' => 'Praga'],
        'praga' => ['lat' => 50.0755, 'lon' => 14.4378, 'name' => 'Praga'],
    ];

    /**
     * Map Open-Meteo WMO weather codes to human-readable description and icon.
     */
    protected array $weatherCodes = [
        0 => ['desc' => 'Clear sky', 'icon' => '☀️'],
        1 => ['desc' => 'Mainly clear', 'icon' => '🌤️'],
        2 => ['desc' => 'Partly cloudy', 'icon' => '⛅'],
        3 => ['desc' => 'Overcast', 'icon' => '☁️'],
        45 => ['desc' => 'Foggy', 'icon' => '🌫️'],
        48 => ['desc' => 'Depositing rime fog', 'icon' => '🌫️'],
        51 => ['desc' => 'Light drizzle', 'icon' => '🌦️'],
        53 => ['desc' => 'Moderate drizzle', 'icon' => '🌦️'],
        55 => ['desc' => 'Dense drizzle', 'icon' => '🌧️'],
        61 => ['desc' => 'Slight rain', 'icon' => '🌧️'],
        63 => ['desc' => 'Moderate rain', 'icon' => '🌧️'],
        65 => ['desc' => 'Heavy rain', 'icon' => '🌧️'],
        71 => ['desc' => 'Slight snow fall', 'icon' => '🌨️'],
        73 => ['desc' => 'Moderate snow fall', 'icon' => '🌨️'],
        75 => ['desc' => 'Heavy snow fall', 'icon' => '❄️'],
        80 => ['desc' => 'Slight rain showers', 'icon' => '🌦️'],
        81 => ['desc' => 'Moderate rain showers', 'icon' => '🌧️'],
        82 => ['desc' => 'Violent rain showers', 'icon' => '⛈️'],
        95 => ['desc' => 'Thunderstorm', 'icon' => '⛈️'],
    ];

    /**
     * Resolve latitude and longitude for a given city string or City model.
     */
    protected function resolveCoordinates(string $city): array
    {
        $normalizedCity = strtolower(trim($city));

        // 1. Try static map
        if (isset($this->cityCoordinates[$normalizedCity])) {
            return $this->cityCoordinates[$normalizedCity];
        }

        // 2. Try database
        $dbCity = City::where('name', 'like', "%{$city}%")
            ->orWhere('slug', 'like', "%{$normalizedCity}%")
            ->first();

        if ($dbCity) {
            return [
                'lat' => (float) $dbCity->latitude,
                'lon' => (float) $dbCity->longitude,
                'name' => $dbCity->name,
            ];
        }

        // Default fallback to Lisbon
        return $this->cityCoordinates['lisboa'];
    }

    /**
     * Get current weather and 7-day forecast for a given city name.
     * Handles timeout (3s), caching (30m), and failure fallbacks.
     */
    public function getWeatherForCity(?string $city): ?array
    {
        if (! $city) {
            return null;
        }

        $normalizedCity = strtolower(trim($city));
        $cacheKey = 'weather_city_extended_' . $normalizedCity;

        $coords = $this->resolveCoordinates($city);

        // Check cache first
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            $cached['from_cache'] = true;
            return $cached;
        }

        try {
            // Requirement: Set timeouts (3 seconds) to avoid hanging
            $response = Http::timeout(3)
                ->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lon'],
                    'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min',
                    'timezone' => 'auto',
                ]);

            if (! $response->successful()) {
                Log::warning("Open-Meteo returned status {$response->status()} for city {$city}");
                return $this->getFallbackData($coords['name'], 'Service returned error code ' . $response->status());
            }

            $data = $response->json();

            // Requirement: Validate expected response structure and required fields
            if (! isset($data['current']) || ! isset($data['current']['temperature_2m'])) {
                Log::warning("Open-Meteo response missing required fields for city {$city}: " . json_encode($data));
                return $this->getFallbackData($coords['name'], 'Incomplete weather data structure');
            }

            $current = $data['current'];
            $weatherCode = $current['weather_code'] ?? 0;
            $weatherMeta = $this->weatherCodes[$weatherCode] ?? ['desc' => 'Partly cloudy', 'icon' => '⛅'];

            // Process 7-day forecast
            $dailyForecast = [];
            if (isset($data['daily']) && isset($data['daily']['time'])) {
                $days = $data['daily']['time'];
                $maxTemps = $data['daily']['temperature_2m_max'] ?? [];
                $minTemps = $data['daily']['temperature_2m_min'] ?? [];
                $codes = $data['daily']['weather_code'] ?? [];

                foreach ($days as $index => $dateString) {
                    $code = $codes[$index] ?? 0;
                    $dayMeta = $this->weatherCodes[$code] ?? ['desc' => 'Partly cloudy', 'icon' => '⛅'];
                    $carbonDate = Carbon::parse($dateString);

                    $dailyForecast[] = [
                        'date' => $dateString,
                        'day_name' => $carbonDate->isToday() ? 'Today' : $carbonDate->format('D'),
                        'full_date' => $carbonDate->format('d M'),
                        'max_temp' => isset($maxTemps[$index]) ? round($maxTemps[$index], 1) : null,
                        'min_temp' => isset($minTemps[$index]) ? round($minTemps[$index], 1) : null,
                        'icon' => $dayMeta['icon'],
                        'description' => $dayMeta['desc'],
                    ];
                }
            }

            $result = [
                'success' => true,
                'city' => $coords['name'],
                'temperature' => round($current['temperature_2m'], 1),
                'temperature_unit' => '°C',
                'humidity' => $current['relative_humidity_2m'] ?? null,
                'wind_speed' => round($current['wind_speed_10m'] ?? 0, 1),
                'wind_unit' => 'km/h',
                'description' => $weatherMeta['desc'],
                'icon' => $weatherMeta['icon'],
                'forecast' => $dailyForecast,
                'last_updated' => now()->format('Y-m-d H:i:s'),
                'from_cache' => false,
            ];

            // Cache for 30 minutes to avoid unnecessary repeated calls
            Cache::put($cacheKey, $result, now()->addMinutes(30));

            return $result;
        } catch (Exception $e) {
            Log::error("WeatherService exception for city {$city}: " . $e->getMessage());

            return $this->getFallbackData($coords['name'], 'Weather service connection timed out or unreachable');
        }
    }

    /**
     * Fallback data in case of error, maintaining graceful UI.
     */
    protected function getFallbackData(string $cityName, string $errorMessage): array
    {
        return [
            'success' => false,
            'city' => $cityName,
            'temperature' => '--',
            'temperature_unit' => '°C',
            'humidity' => '--',
            'wind_speed' => '--',
            'wind_unit' => 'km/h',
            'description' => 'Temporarily unavailable',
            'icon' => '☁️',
            'forecast' => [],
            'error' => $errorMessage,
            'last_updated' => now()->format('Y-m-d H:i:s'),
            'from_cache' => false,
        ];
    }
}
