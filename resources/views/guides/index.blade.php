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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <!-- Standard Server Search Form -->
            <form action="{{ route('guides.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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

        </div>

        <!-- Guides Grid -->
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
                            <div class="h-48 w-full relative overflow-hidden">
                                @if ($guide->image)
                                    <img src="{{ asset('storage/' . $guide->image->path) }}" alt="{{ $guide->image->alt_text ?? $guide->title }}" class="w-full h-full object-cover">
                                @else
                                    <x-city-image :city="$guide->city" :alt="$guide->title" />
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
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
</x-app-layout>
