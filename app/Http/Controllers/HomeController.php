<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, WeatherService $weatherService): View
    {
        $featuredGuides = Guide::query()
            ->published()
            ->with(['category', 'author', 'image'])
            ->latest('published_at')
            ->take(6)
            ->get();

        $categories = Category::withCount(['guides' => function ($query) {
            $query->published();
        }])->get();

        // Sample weather for 3 main Erasmus cities
        $popularCitiesWeather = [
            'Lisboa' => $weatherService->getWeatherForCity('Lisboa'),
            'Porto' => $weatherService->getWeatherForCity('Porto'),
            'Zagreb' => $weatherService->getWeatherForCity('Zagreb'),
        ];

        return view('home', compact('featuredGuides', 'categories', 'popularCitiesWeather'));
    }
}
