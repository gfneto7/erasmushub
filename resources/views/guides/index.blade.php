<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    📖 {{ __('Guides') }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('Discover essential Erasmus guides and tips.') }}
                </p>
            </div>
            
            @auth
                @if (Auth::user()->canManageGuides())
                    <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        ✏️ {{ __('Create Guide') }}
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="guidesApp()">
        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                <!-- Mode Switcher: Server-side vs Client-side API fetch -->
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Mode') }}:</span>
                    <button type="button" @click="apiMode = false" :class="!apiMode ? 'bg-indigo-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-xs transition">
                        📄 {{ __('Standard Search') }}
                    </button>
                    <button type="button" @click="toggleApiMode()" :class="apiMode ? 'bg-indigo-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-xs transition flex items-center space-x-1">
                        <span>⚡</span>
                        <span>{{ __('Live API Search') }}</span>
                    </button>
                </div>

                <div x-show="apiMode" class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 flex items-center space-x-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ __('Results powered by our REST API') }} (/api/v1/guides)</span>
                </div>
            </div>

            <!-- Standard Server Search Form -->
            <form x-show="!apiMode" action="{{ route('guides.index') }}" method="GET" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search by keyword -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by title or keyword...') }}" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Category') }}</label>
                    <select name="category" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->guides_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- City Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('City') }}</label>
                    <select name="city" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('Filter by City') }}</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>
                                {{ $c }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl text-sm shadow transition">
                        {{ __('Search') }}
                    </button>
                    @if ($search || $categorySlug || $city)
                        <a href="{{ route('guides.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-3 rounded-xl text-sm transition">
                            ✕
                        </a>
                    @endif
                </div>
            </form>

            <!-- Live REST API Search Form -->
            <div x-show="apiMode" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Search via REST API') }}</label>
                    <input type="text" x-model="apiSearch" @input.debounce.400ms="fetchGuidesFromApi()" placeholder="{{ __('Search by title or keyword...') }}" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Category') }}</label>
                    <select x-model="apiCategoryId" @change="fetchGuidesFromApi()" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('City') }}</label>
                    <select x-model="apiCity" @change="fetchGuidesFromApi()" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('Filter by City') }}</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="button" @click="resetApiFilters()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-xl text-sm transition">
                        {{ __('Reset Filters') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- 1. Standard Server-Rendered Guides Grid -->
        <div x-show="!apiMode">
            @if ($guides->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border border-gray-200">
                    <span class="text-4xl">🔍</span>
                    <h3 class="mt-4 text-base font-bold text-gray-900">{{ __('No guides found matching your criteria.') }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Try adjusting your filters or search keywords.') }}</p>
                    @if ($search || $categorySlug || $city)
                        <div class="mt-4">
                            <a href="{{ route('guides.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                {{ __('Reset Filters') }}
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($guides as $guide)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col">
                            <!-- Image Header -->
                            <div class="h-48 w-full bg-gradient-to-tr from-indigo-500 to-sky-400 relative overflow-hidden flex items-center justify-center text-white">
                                @if ($guide->image)
                                    <img src="{{ asset('storage/' . $guide->image->path) }}" alt="{{ $guide->image->alt_text ?? $guide->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center p-4">
                                        <span class="text-4xl">🏛️</span>
                                        <p class="text-xs uppercase font-bold tracking-wider mt-2 opacity-90">{{ $guide->city ?? 'Erasmus' }}</p>
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

                            <!-- Content -->
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

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $guides->links() }}
                </div>
            @endif
        </div>

        <!-- 2. Live API Search Rendered via JavaScript -->
        <div x-show="apiMode">
            <!-- Loading Indicator -->
            <div x-show="apiLoading" class="bg-white rounded-2xl p-12 text-center border border-gray-200">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-600 border-t-transparent mb-3"></div>
                <p class="text-sm font-semibold text-gray-600">{{ __('Loading results from API...') }}</p>
            </div>

            <!-- API Error Message -->
            <div x-show="apiError" class="bg-rose-50 rounded-2xl p-8 text-center border border-rose-200 mb-6">
                <span class="text-3xl">⚠️</span>
                <p class="mt-2 text-sm font-bold text-rose-800">{{ __('API request failed. Please try again.') }}</p>
                <p class="text-xs text-rose-600 mt-1" x-text="apiErrorMessage"></p>
            </div>

            <!-- API Empty State -->
            <div x-show="!apiLoading && !apiError && apiGuides.length === 0" class="bg-white rounded-2xl p-12 text-center border border-gray-200">
                <span class="text-4xl">🔍</span>
                <h3 class="mt-4 text-base font-bold text-gray-900">{{ __('No guides found matching your criteria.') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('Try adjusting your filters or search keywords.') }}</p>
            </div>

            <!-- API Guides Grid -->
            <div x-show="!apiLoading && !apiError && apiGuides.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="guide in apiGuides" :key="guide.id">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col">
                        <div class="h-48 w-full bg-gradient-to-tr from-indigo-500 to-sky-400 relative overflow-hidden flex items-center justify-center text-white">
                            <template x-if="guide.image && guide.image.path">
                                <img :src="'/storage/' + guide.image.path" :alt="guide.title" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!guide.image || !guide.image.path">
                                <div class="text-center p-4">
                                    <span class="text-4xl">🏛️</span>
                                    <p class="text-xs uppercase font-bold tracking-wider mt-2 opacity-90" x-text="guide.city || 'Erasmus'"></p>
                                </div>
                            </template>
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white/90 text-indigo-900 backdrop-blur-sm shadow-sm" x-text="guide.category ? guide.category.name : ''"></span>
                            </div>
                            <template x-if="guide.city">
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-black/50 text-white backdrop-blur-sm" x-text="'📍 ' + guide.city"></span>
                                </div>
                            </template>
                        </div>

                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition line-clamp-2">
                                    <a :href="'/guides/' + guide.slug" x-text="guide.title"></a>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3 leading-relaxed" x-text="guide.summary"></p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-700" x-text="guide.author ? guide.author.name : ''"></span>
                                </div>
                                <a :href="'/guides/' + guide.slug" class="font-semibold text-indigo-600 hover:text-indigo-800">
                                    {{ __('Read more') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Alpine.js script to handle Live API consumption -->
    <script>
        function guidesApp() {
            return {
                apiMode: false,
                apiLoading: false,
                apiError: false,
                apiErrorMessage: '',
                apiSearch: '',
                apiCategoryId: '',
                apiCity: '',
                apiGuides: [],

                toggleApiMode() {
                    this.apiMode = true;
                    if (this.apiGuides.length === 0) {
                        this.fetchGuidesFromApi();
                    }
                },

                resetApiFilters() {
                    this.apiSearch = '';
                    this.apiCategoryId = '';
                    this.apiCity = '';
                    this.fetchGuidesFromApi();
                },

                async fetchGuidesFromApi() {
                    this.apiLoading = true;
                    this.apiError = false;
                    this.apiErrorMessage = '';

                    const params = new URLSearchParams();
                    if (this.apiSearch) params.append('search', this.apiSearch);
                    if (this.apiCategoryId) params.append('category_id', this.apiCategoryId);
                    if (this.apiCity) params.append('city', this.apiCity);

                    try {
                        const response = await fetch('/api/v1/guides?' + params.toString(), {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status + ' (' + response.statusText + ')');
                        }

                        const data = await response.json();
                        this.apiGuides = data.data || [];
                    } catch (err) {
                        this.apiError = true;
                        this.apiErrorMessage = err.message;
                        this.apiGuides = [];
                    } finally {
                        this.apiLoading = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>
