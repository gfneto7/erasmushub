<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <nav class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-indigo-600">{{ __('Home') }}</a>
                <span>/</span>
                <a href="{{ route('guides.index') }}" class="hover:text-indigo-600">{{ __('Guides') }}</a>
                <span>/</span>
                <span class="text-gray-900 font-medium truncate max-w-xs">{{ __($guide->title) }}</span>
            </nav>

            @can('update', $guide)
                <a href="{{ route('admin.guides.edit', $guide) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-100 transition">
                    ✏️ {{ __('Edit Guide') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content (2 Columns) -->
            <div class="lg:col-span-2 space-y-8">
                <article class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-gray-200">
                    <!-- Badges & Metadata -->
                    @php
                        $displayCity = $guide->destinationCity?->name ?? $guide->city;
                        if (! $displayCity && $guide->title) {
                            foreach (['lisboa', 'porto', 'coimbra', 'braga', 'madrid', 'barcelona', 'valencia', 'paris', 'lyon', 'roma', 'bolonha', 'berlim', 'munique', 'zagreb', 'split', 'dubrovnik', 'praga'] as $c) {
                                if (\Illuminate\Support\Str::contains(strtolower($guide->title), $c)) {
                                    $displayCity = ucfirst($c);
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ __($guide->category->name) }}
                        </span>
                        @if ($displayCity)
                            <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 border border-gray-200">
                                📍 {{ __($displayCity) }}
                            </span>
                        @endif
                        @if ($guide->status === 'draft')
                            <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-800 border border-amber-200">
                                ⚠️ {{ __('Draft') }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ __($guide->title) }}
                    </h1>

                    <!-- Author & Published Date -->
                    <div class="mt-4 pb-6 border-b border-gray-100 flex items-center space-x-3 text-sm text-gray-500">
                        <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs uppercase">
                            {{ substr($guide->author->name, 0, 2) }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-800">{{ $guide->author->name }}</span>
                            <span class="mx-1">•</span>
                            <span>{{ $guide->published_at ? $guide->published_at->format('d M Y, H:i') : __('Draft') }}</span>
                        </div>
                    </div>

                    <!-- Summary Highlight Callout -->
                    <div class="my-6 p-4 rounded-2xl bg-indigo-50/60 border-l-4 border-indigo-500 text-indigo-950 font-medium text-base leading-relaxed">
                        {{ __($guide->summary) }}
                    </div>

                    <!-- Complementary Image -->
                    <div class="my-8 rounded-2xl overflow-hidden shadow-md border border-gray-200">
                        <img src="{{ $guide->image_full_url }}" alt="{{ $guide->image?->alt_text ?? __($guide->title) }}" class="w-full h-auto max-h-[500px] object-cover">
                        @if ($guide->image?->alt_text)
                            <p class="text-xs text-center text-gray-400 py-2 bg-gray-50 italic border-t border-gray-100">
                                {{ $guide->image->alt_text }}
                            </p>
                        @elseif ($displayCity)
                            <p class="text-xs text-center text-gray-400 py-2 bg-gray-50 italic border-t border-gray-100">
                                📍 {{ __($displayCity) }}
                            </p>
                        @endif
                    </div>

                    <!-- Guide Content (Rich text, sanitized) -->
                    <div class="prose max-w-none text-gray-800 leading-relaxed text-base">
                        {!! __($guide->content) !!}
                    </div>

                    <!-- Back Button -->
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <a href="{{ route('guides.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                            &larr; {{ __('Back to Guides') }}
                        </a>
                    </div>
                </article>
            </div>

            <!-- Sidebar (1 Column) -->
            <div class="space-y-6">
                <!-- External API Weather Widget -->
                @if ($weather)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl">🌤️</span>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm">{{ __('Local Weather') }}</h3>
                                    <p class="text-xs text-gray-500">{{ __($displayCity) }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-semibold border border-sky-200">
                                Open-Meteo
                            </span>
                        </div>

                        @if ($weather['success'])
                            <div class="flex items-center justify-between my-2">
                                <div class="flex items-center space-x-3">
                                    <span class="text-4xl">{{ $weather['icon'] }}</span>
                                    <div>
                                        <div class="text-3xl font-extrabold text-indigo-700">
                                            {{ $weather['temperature'] }}{{ $weather['temperature_unit'] }}
                                        </div>
                                        <div class="text-xs font-medium text-gray-600">{{ __($weather['description']) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-600">
                                <div class="bg-gray-50 rounded-xl p-2.5">
                                    <span class="text-gray-400 block">{{ __('Wind') }}:</span>
                                    <span class="font-bold text-gray-800">{{ $weather['wind_speed'] }} {{ $weather['wind_unit'] }}</span>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-2.5">
                                    <span class="text-gray-400 block">{{ __('Conditions') }}:</span>
                                    <span class="font-bold text-gray-800">{{ __($weather['description']) }}</span>
                                </div>
                            </div>

                            <!-- Cache and Last Fetch Notice (Section 9 Requirement) -->
                            <div class="mt-4 pt-3 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
                                <span>
                                    @if ($weather['from_cache'])
                                        📦 {{ __('Cached data from') }}
                                    @else
                                        ⚡ {{ __('Last updated') }}:
                                    @endif
                                </span>
                                <span class="font-mono text-gray-500">{{ $weather['last_updated'] }}</span>
                            </div>
                        @else
                            <!-- Fallback error state (Section 9 requirement) -->
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                                <p class="font-bold">⚠️ {{ __('Weather service temporarily unavailable') }}</p>
                                <p class="mt-1 text-amber-700">{{ $weather['error'] ?? 'Connection error' }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Related Guides in same category -->
                @if ($relatedGuides->isNotEmpty())
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-900 text-sm mb-4 pb-2 border-b border-gray-100">
                            {{ __('More in') }} {{ __($guide->category->name) }}
                        </h3>

                        <div class="space-y-4">
                            @foreach ($relatedGuides as $related)
                                <div class="group">
                                    <a href="{{ route('guides.show', $related->slug) }}" class="block text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition line-clamp-2">
                                        {{ __($related->title) }}
                                    </a>
                                    <span class="text-xs text-gray-400 mt-1 block">
                                        {{ $related->published_at?->format('d M Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Project Info Widget -->
                <div class="bg-gradient-to-br from-indigo-50 to-sky-50 rounded-3xl p-6 border border-indigo-100">
                    <h4 class="font-bold text-indigo-900 text-sm mb-2">🎓 ErasmusHub</h4>
                    <p class="text-xs text-indigo-950/80 leading-relaxed">
                        {{ __('Created for Erasmus students to share local tips, navigate university life, find housing, and explore host cities.') }}
                    </p>
                    <a href="{{ route('about') }}" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        {{ __('About the Project') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
