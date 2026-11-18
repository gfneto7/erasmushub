<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * List all Erasmus countries and cities.
     */
    public function index(): JsonResponse
    {
        $countries = Country::with(['cities'])->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $countries,
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Retrieve a specific city along with its live 7-day weather forecast.
     */
    public function show(string $slug, WeatherService $weatherService): JsonResponse
    {
        $city = City::with('country')
            ->where('slug', $slug)
            ->orWhere('name', 'like', "%{$slug}%")
            ->first();

        if (! $city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City destination not found.',
            ], 404, ['Content-Type' => 'application/json']);
        }

        $weather = $weatherService->getWeatherForCity($city->name);

        return response()->json([
            'status' => 'success',
            'data' => [
                'city' => $city,
                'weather' => $weather,
            ],
        ], 200, ['Content-Type' => 'application/json']);
    }
}
