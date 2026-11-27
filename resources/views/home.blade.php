<x-app-layout>
    <!-- 1. Banner Panorâmico Erasmus & Europa (Ocupa a largura total, altura moderada e elegante) -->
    <div class="w-full relative overflow-hidden bg-slate-900" style="height: 220px;">
        <img src="{{ asset('images/erasmus-banner.jpg') }}" alt="Erasmus+ European Community" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/40 to-transparent flex items-end">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-5 w-full flex items-center justify-between">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-600 text-white shadow-md">
                    🇪🇺 {{ __('Erasmus+ Community Portal') }}
                </span>
                <span class="text-xs font-semibold text-slate-200 hidden sm:inline-block drop-shadow">
                    🌍 {{ __('Explore 16+ Erasmus destinations with verified student tips and live weather.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Secção de Introdução e Pesquisa (Fundo Branco Limpo, Texto Escuro 100% Legível) -->
    <div class="bg-white border-b border-gray-200 py-8 sm:py-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Welcome to ErasmusHub') }}
                </h1>
                <p class="mt-3 text-base sm:text-lg text-gray-600 leading-relaxed">
                    {{ __('Your ultimate companion for an unforgettable Erasmus experience. Discover local guides, survival tips, accommodation advice, and city insights curated by students, for students.') }}
                </p>

                <!-- Barra de Pesquisa -->
                <form action="{{ route('guides.index') }}" method="GET" class="mt-6 max-w-xl">
                    <div class="flex items-center bg-gray-50 rounded-xl p-1.5 border border-gray-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                        <div class="pl-3 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="{{ __('Search by title or keyword...') }}" class="w-full bg-transparent border-0 focus:ring-0 text-gray-900 text-sm px-3 py-2 focus:outline-none placeholder-gray-400">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg text-sm shadow transition">
                            {{ __('Search') }}
                        </button>
                    </div>
                </form>

                <!-- Botões de Ação Rápida -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ route('guides.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-sm border border-indigo-200 transition">
                        📖 {{ __('Explore Guides') }}
                    </a>
                    <a href="{{ route('destinations.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold text-sm border border-gray-200 transition">
                        🌍 {{ __('Destinations & Weather') }}
                    </a>
                    @auth
                        @if (Auth::user()->canManageGuides())
                            <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition shadow-sm">
                                ✏️ {{ __('Create Guide') }}
                            </a>
                        @endif
                    @endauth
                </div>
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
                <div class="flex items-center space-x-3 text-xs">
                    <a href="{{ route('destinations.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                        {{ __('Explore 7-Day Forecasts & All Cities') }} →
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($popularCitiesWeather as $cityName => $weather)
                    <a href="{{ route('destinations.index', ['city' => Str::slug($cityName)]) }}" class="bg-gradient-to-r from-gray-50 to-indigo-50/30 hover:to-indigo-100/50 rounded-xl p-4 border border-gray-200/80 hover:border-indigo-300 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl">{{ $weather['icon'] ?? '⛅' }}</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base group-hover:text-indigo-600 transition">{{ __($cityName) }}</h4>
                                <p class="text-xs text-gray-500">{{ __($weather['description'] ?? 'Sunny') }}</p>
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
                                · {{ __('7-Day Forecast') }} →
                            </p>
                        </div>
                    </a>
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
                    <span>{{ __($category->name) }}</span>
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
                        <div class="h-48 w-full relative overflow-hidden bg-gray-100">
                            <img src="{{ $guide->image_full_url }}" alt="{{ $guide->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white/90 text-indigo-900 backdrop-blur-sm shadow-sm">
                                    {{ __($guide->category->name) }}
                                </span>
                            </div>
                            @if ($guide->city)
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-black/50 text-white backdrop-blur-sm">
                                        📍 {{ __($guide->city) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition line-clamp-2">
                                    <a href="{{ route('guides.show', $guide->slug) }}">
                                        {{ __($guide->title) }}
                                    </a>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3 leading-relaxed">
                                    {{ __($guide->summary) }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-700">{{ __($guide->author->name) }}</span>
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
