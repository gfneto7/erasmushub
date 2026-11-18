<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-indigo-900 via-indigo-800 to-sky-900 text-white overflow-hidden py-16 sm:py-24">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 mb-4 backdrop-blur-sm">
                🇪🇺 {{ __('Erasmus+ Community Portal') }}
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight">
                {{ __('Welcome to ErasmusHub') }}
            </h1>
            <p class="mt-6 max-w-3xl mx-auto text-lg sm:text-xl text-indigo-100 font-normal leading-relaxed">
                {{ __('Your ultimate companion for an unforgettable Erasmus experience. Discover local guides, survival tips, accommodation advice, and city insights curated by students, for students.') }}
            </p>

            <!-- Search Bar in Hero -->
            <form action="{{ route('guides.index') }}" method="GET" class="mt-8 max-w-2xl mx-auto">
                <div class="flex items-center bg-white rounded-2xl p-2 shadow-2xl">
                    <div class="pl-3 text-gray-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="{{ __('Search by title or keyword...') }}" class="w-full border-0 focus:ring-0 text-gray-800 text-base px-3 py-2 focus:outline-none placeholder-gray-400">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow transition">
                        {{ __('Search') }}
                    </button>
                </div>
            </form>

            <!-- Quick Action Buttons -->
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('guides.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-indigo-900 font-semibold shadow hover:bg-indigo-50 transition">
                    {{ __('Explore Guides') }} →
                </a>
                @auth
                    @if (Auth::user()->canManageGuides())
                        <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-indigo-600/80 hover:bg-indigo-600 text-white font-semibold border border-indigo-400/40 backdrop-blur-sm transition">
                            ✏️ {{ __('Create Guide') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-indigo-700/60 hover:bg-indigo-700 text-white font-semibold border border-indigo-500/40 backdrop-blur-sm transition">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Live Weather Highlight Bar (External API Integration demo) -->
    <div class="bg-white border-b border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🌤️</span>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-600">
                        {{ __('Local Weather in Popular Erasmus Destinations') }}
                    </h3>
                </div>
                <span class="text-xs text-gray-400">
                    {{ __('Powered by Open-Meteo API') }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($popularCitiesWeather as $cityName => $weather)
                    <div class="bg-gradient-to-r from-gray-50 to-indigo-50/30 rounded-xl p-4 border border-gray-200/80 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl">{{ $weather['icon'] ?? '⛅' }}</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base">{{ $cityName }}</h4>
                                <p class="text-xs text-gray-500">{{ $weather['description'] ?? 'Sunny' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-extrabold text-indigo-700">
                                {{ $weather['temperature'] ?? '--' }}{{ $weather['temperature_unit'] ?? '°C' }}
                            </span>
                            <p class="text-[10px] text-gray-400">
                                @if(!empty($weather['from_cache']))
                                    {{ __('Cached') }}
                                @else
                                    {{ __('Live') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Category Pills Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">{{ __('All Categories') }}</h2>
            <a href="{{ route('guides.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                {{ __('View all guides') }} &rarr;
            </a>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($categories as $category)
                <a href="{{ route('guides.index', ['category' => $category->slug]) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white border border-gray-200 text-gray-700 hover:border-indigo-400 hover:text-indigo-600 hover:shadow-sm transition">
                    <span>{{ $category->name }}</span>
                    <span class="ms-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 font-semibold">
                        {{ $category->guides_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Latest Guides Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Latest Guides') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Handpicked travel, academic and lifestyle tips from students.') }}</p>
            </div>
            <a href="{{ route('guides.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                {{ __('View all guides') }} &rarr;
            </a>
        </div>

        @if ($featuredGuides->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-200">
                <span class="text-4xl">📝</span>
                <p class="mt-4 text-base font-medium text-gray-600">{{ __('No guides found matching your criteria.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($featuredGuides as $guide)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden hover:shadow-md transition flex flex-col">
                        <!-- Image or default banner -->
                        <div class="h-48 w-full bg-gradient-to-tr from-indigo-500 to-sky-400 relative overflow-hidden flex items-center justify-center text-white">
                            @if ($guide->image)
                                <img src="{{ asset('storage/' . $guide->image->path) }}" alt="{{ $guide->image->alt_text ?? $guide->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4">
                                    <span class="text-4xl">🏛️</span>
                                    <p class="text-xs uppercase font-bold tracking-wider mt-2 opacity-90">{{ $guide->city ?? 'Europe' }}</p>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white/90 text-indigo-900 backdrop-blur-sm shadow-sm">
                                    {{ $guide->category->name }}
                                </span>
                            </div>
                            @if ($guide->city)
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-black/50 text-white backdrop-blur-sm">
                                        📍 {{ $guide->city }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition line-clamp-2">
                                    <a href="{{ route('guides.show', $guide->slug) }}">
                                        {{ $guide->title }}
                                    </a>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                    {{ $guide->summary }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-700">{{ $guide->author->name }}</span>
                                    <span>•</span>
                                    <span>{{ $guide->published_at?->format('d M Y') }}</span>
                                </div>
                                <a href="{{ route('guides.show', $guide->slug) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                    {{ __('Read more') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
