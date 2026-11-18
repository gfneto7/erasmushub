<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Guide;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    /**
     * Display Erasmus destinations explorer with live weather and 7-day forecast.
     */
    public function index(Request $request, WeatherService $weatherService): View
    {
        $countries = Country::with(['cities'])->orderBy('name')->get();
        $allCities = City::with('country')->orderBy('name')->get();

        // Selected city or default to Lisboa (or first city)
        $selectedCitySlug = $request->query('city', 'lisboa');
        $currentCity = City::with('country')
            ->where('slug', $selectedCitySlug)
            ->orWhere('name', 'like', "%{$selectedCitySlug}%")
            ->first() ?? $allCities->first();

        $weather = null;
        $cityGuides = collect();

        if ($currentCity) {
            $weather = $weatherService->getWeatherForCity($currentCity->name);

            // Fetch published guides for this city (either by city_id or city name string)
            $cityGuides = Guide::query()
                ->published()
                ->with(['category', 'author', 'image'])
                ->where(function ($q) use ($currentCity) {
                    $q->where('city_id', $currentCity->id)
                      ->orWhere('city', 'like', "%{$currentCity->name}%");
                })
                ->latest('published_at')
                ->take(6)
                ->get();
        }

        return view('destinations.index', compact(
            'countries',
            'allCities',
            'currentCity',
            'weather',
            'cityGuides'
        ));
    }
}
