<x-app-layout>
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-blue-900 text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 mb-3">
                        🌍 {{ __('Erasmus Destinations & Climate Hub') }}
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        {{ __('Explore Erasmus Destinations') }}
                    </h1>
                    <p class="mt-2 text-indigo-200 text-sm sm:text-base max-w-2xl">
                        {{ __('Check out top European student destinations, local universities, cost of living insights, and live 7-day weather forecasts.') }}
                    </p>
                </div>

                <!-- City Selector Box -->
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 sm:min-w-[320px]">
                    <label for="city-select" class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-2">
                        📍 {{ __('Choose a Destination City') }}
                    </label>
                    <form action="{{ route('destinations.index') }}" method="GET">
                        <select id="city-select" name="city" onchange="this.form.submit()"
                            class="w-full bg-white text-gray-900 text-sm font-semibold rounded-xl border-0 shadow-lg focus:ring-2 focus:ring-indigo-400 py-2.5 px-3 cursor-pointer">
                            @foreach ($countries as $country)
                                <optgroup label="{{ $country->flag_emoji }} {{ __($country->name) }}">
                                    @foreach ($country->cities as $c)
                                        <option value="{{ $c->slug }}" {{ $currentCity && $currentCity->id === $c->id ? 'selected' : '' }}>
                                            {{ __($c->name) }} ({{ __($country->name) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        @if ($currentCity)
            <!-- Destination Details & Weather Hero Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- City Info Card -->
                <div class="lg:col-span-1 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-4xl">{{ $currentCity->country->flag_emoji ?? '🇪🇺' }}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                @if($currentCity->cost_of_living === 'Low') bg-emerald-100 text-emerald-800
                                @elseif($currentCity->cost_of_living === 'High') bg-rose-100 text-rose-800
                                @else bg-amber-100 text-amber-800 @endif">
                                {{ __('Cost') }}: {{ __($currentCity->cost_of_living) }}
                            </span>
                        </div>

                        @php
                            $cityImgPath = "images/cities/{$currentCity->slug}.jpg";
                        @endphp
                        @if (file_exists(public_path($cityImgPath)))
                            <div class="h-44 w-full rounded-2xl overflow-hidden mb-4 shadow-sm border border-gray-100">
                                <img src="{{ asset($cityImgPath) }}" alt="{{ __($currentCity->name) }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <h2 class="text-3xl font-extrabold text-gray-900">
                            {{ __($currentCity->name) }}
                        </h2>
                        <p class="text-sm font-medium text-indigo-600 mt-1">
                            {{ __($currentCity->country->name) }} ({{ $currentCity->country->currency }})
                        </p>

                        <div class="mt-5 space-y-3">
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">
                                    🎓 {{ __('Host University') }}
                                </span>
                                <span class="text-sm font-bold text-gray-900 mt-0.5 block">
                                    {{ __($currentCity->main_university ?? 'Multiple partner universities') }}
                                </span>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">
                                    🧭 {{ __('Coordinates') }}
                                </span>
                                <span class="text-xs font-mono text-gray-600 mt-0.5 block">
                                    {{ number_format($currentCity->latitude, 4) }}° N, {{ number_format($currentCity->longitude, 4) }}° E
                                </span>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mt-4 leading-relaxed">
                            {{ __($currentCity->description) }}
                        </p>
                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span>{{ __('Erasmus Destination ID') }}: #{{ $currentCity->id }}</span>
                        <a href="{{ route('guides.index', ['city' => $currentCity->name]) }}" class="text-indigo-600 font-semibold hover:underline">
                            {{ __('All Guides for this city') }} →
                        </a>
                    </div>
                </div>

                <!-- Weather Card (Current + 7-Day Forecast) -->
                <div class="lg:col-span-2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="flex items-center justify-between pb-6 border-b border-indigo-500/40">
                            <div>
                                <span class="text-xs uppercase tracking-wider font-semibold text-indigo-200">
                                    {{ __('Live Weather Integration') }} (Open-Meteo API)
                                </span>
                                <h3 class="text-xl sm:text-2xl font-bold mt-0.5 flex items-center gap-2">
                                    <span>{{ __($currentCity->name) }}</span>
                                    <span class="text-base font-normal text-indigo-200">· {{ __('Forecast') }}</span>
                                </h3>
                            </div>

                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold
                                    {{ !empty($weather['from_cache']) ? 'bg-amber-400/20 text-amber-200 border border-amber-300/30' : 'bg-emerald-400/20 text-emerald-200 border border-emerald-300/30' }}">
                                    ● {{ !empty($weather['from_cache']) ? __('Cached Data') : __('Live API') }}
                                </span>
                                <div class="text-[10px] text-indigo-200 mt-1">
                                    {{ __('Updated') }}: {{ $weather['last_updated'] ?? now()->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Current Condition Large Display -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 items-center">
                            <div class="flex items-center space-x-5">
                                <span class="text-6xl sm:text-7xl drop-shadow-md">
                                    {{ $weather['icon'] ?? '⛅' }}
                                </span>
                                <div>
                                    <div class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                                        {{ $weather['temperature'] ?? '--' }}{{ $weather['temperature_unit'] ?? '°C' }}
                                    </div>
                                    <div class="text-sm font-medium text-indigo-200 mt-1">
                                        {{ __($weather['description'] ?? 'Partly cloudy') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Weather metrics pills -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/10">
                                    <span class="text-xs text-indigo-200 block">💧 {{ __('Humidity') }}</span>
                                    <span class="text-base font-bold">{{ $weather['humidity'] ?? '--' }}%</span>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/10">
                                    <span class="text-xs text-indigo-200 block">💨 {{ __('Wind') }}</span>
                                    <span class="text-base font-bold">{{ $weather['wind_speed'] ?? '--' }} {{ $weather['wind_unit'] ?? 'km/h' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 7-Day Forecast Row -->
                        <div class="mt-4 pt-6 border-t border-indigo-500/40">
                            <h4 class="text-xs uppercase tracking-wider font-bold text-indigo-200 mb-4">
                                📅 {{ __('7-Day Forecast for Students Traveling to') }} {{ __($currentCity->name) }}
                            </h4>

                            @if (!empty($weather['forecast']))
                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2.5">
                                    @foreach ($weather['forecast'] as $day)
                                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 text-center border border-white/10 flex flex-col justify-between hover:bg-white/15 transition">
                                            <span class="text-xs font-bold text-indigo-100">
                                                {{ __($day['day_name']) }}
                                            </span>
                                            <span class="text-[10px] text-indigo-200">
                                                {{ $day['full_date'] }}
                                            </span>
                                            
                                            <span class="text-2xl my-2">
                                                {{ $day['icon'] }}
                                            </span>

                                            <div class="text-xs font-bold flex items-center justify-center space-x-1.5">
                                                <span class="text-amber-300" title="{{ __('High') }}">{{ $day['max_temp'] }}°</span>
                                                <span class="text-indigo-300">/</span>
                                                <span class="text-blue-200" title="{{ __('Low') }}">{{ $day['min_temp'] }}°</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-indigo-200">
                                    {{ __('Detailed weekly forecast currently cached or updating.') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 text-[11px] text-indigo-300/80 flex items-center justify-between">
                        <span>🛰️ {{ __('Data sourced via Open-Meteo European Weather Service with 3s timeout & 30m cache.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Guides & Tips for this City Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                            📚 {{ __('Student Knowledge Base') }}
                        </span>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">
                            {{ __('Guides & Tips for') }} {{ __($currentCity->name) }}
                        </h3>
                    </div>

                    <a href="{{ route('guides.index', ['city' => $currentCity->name]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-xl border border-indigo-200 transition">
                        {{ __('View All Guides') }} →
                    </a>
                </div>

                @if ($cityGuides->isEmpty())
                    <div class="py-12 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <span class="text-4xl">✍️</span>
                        <p class="mt-2 text-sm font-semibold text-gray-700">
                            {{ __('No guides published specifically for this city yet.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ __('Have you studied in') }} {{ __($currentCity->name) }}? {{ __('Share your experience with future Erasmus students!') }}
                        </p>
                        @auth
                            @if(Auth::user()->canManageGuides())
                                <a href="{{ route('admin.guides.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                    + {{ __('Write a Guide for') }} {{ __($currentCity->name) }}
                                </a>
                            @endif
                        @endauth
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($cityGuides as $guide)
                            <article class="bg-gray-50 rounded-2xl p-5 border border-gray-200/80 hover:border-indigo-300 hover:shadow-md transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                        <span class="px-2 py-0.5 rounded-lg bg-indigo-100 text-indigo-800 font-semibold">
                                            {{ __($guide->category->name) }}
                                        </span>
                                        <span>{{ $guide->created_at->format('d/m/Y') }}</span>
                                    </div>

                                    <h4 class="font-bold text-gray-900 text-base line-clamp-2 hover:text-indigo-600">
                                        <a href="{{ route('guides.show', $guide->slug) }}">
                                            {{ $guide->title }}
                                        </a>
                                    </h4>

                                    <p class="text-xs text-gray-600 mt-2 line-clamp-3 leading-relaxed">
                                        {{ $guide->summary }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200/60 flex items-center justify-between text-xs">
                                    <span class="text-gray-500">{{ __('By') }} {{ $guide->author->name }}</span>
                                    <a href="{{ route('guides.show', $guide->slug) }}" class="text-indigo-600 font-semibold hover:underline">
                                        {{ __('Read more') }} →
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- All EU Destinations Explorer Grid -->
        <div class="bg-gray-50 rounded-3xl p-6 sm:p-8 border border-gray-200">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">🇪🇺 {{ __('European Network') }}</span>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">
                    {{ __('All Partner Erasmus Countries & Cities') }}
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-2">
                    {{ __('Click on any city below to inspect its climate, university details, and student survival advice.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($countries as $country)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="text-3xl">{{ $country->flag_emoji }}</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base">{{ __($country->name) }}</h4>
                                <span class="text-xs text-gray-400">{{ count($country->cities) }} {{ __('cities') }} · {{ $country->currency }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($country->cities as $c)
                                <a href="{{ route('destinations.index', ['city' => $c->slug]) }}"
                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition
                                    {{ $currentCity && $currentCity->id === $c->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 border border-gray-200' }}">
                                    {{ __($c->name) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
