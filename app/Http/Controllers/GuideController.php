<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Display a paginated listing of published guides with search & category filters.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $city = $request->query('city');

        $query = Guide::query()
            ->published()
            ->with(['category', 'author', 'image'])
            ->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($city) {
            $query->where('city', $city);
        }

        $guides = $query->paginate(6)->withQueryString();

        $categories = Category::withCount(['guides' => function ($q) {
            $q->published();
        }])->get();

        $cities = Guide::query()
            ->published()
            ->whereNotNull('city')
            ->select('city')
            ->distinct()
            ->pluck('city');

        return view('guides.index', compact('guides', 'categories', 'cities', 'search', 'categorySlug', 'city'));
    }

    /**
     * Display a specific guide.
     */
    public function show(string $slug, WeatherService $weatherService): View
    {
        $guide = Guide::where('slug', $slug)
            ->with(['category', 'author', 'image', 'destinationCity'])
            ->firstOrFail();

        // Check view authorization (drafts can only be seen by author or admin)
        Gate::authorize('view', $guide);

        // Fetch live weather from external API for this guide's city
        $cityName = $guide->destinationCity?->name ?? $guide->city;
        if (! $cityName && $guide->title) {
            foreach (['lisboa', 'porto', 'coimbra', 'braga', 'madrid', 'barcelona', 'valencia', 'paris', 'lyon', 'roma', 'bolonha', 'berlim', 'munique', 'zagreb', 'split', 'dubrovnik', 'praga'] as $c) {
                if (\Illuminate\Support\Str::contains(strtolower($guide->title), $c)) {
                    $cityName = ucfirst($c);
                    break;
                }
            }
        }
        $weather = $cityName ? $weatherService->getWeatherForCity($cityName) : null;

        $relatedGuides = Guide::query()
            ->published()
            ->where('category_id', $guide->category_id)
            ->where('id', '!=', $guide->id)
            ->with(['category', 'image'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('guides.show', compact('guide', 'weather', 'relatedGuides'));
    }
}
