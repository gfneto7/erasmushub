<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    📰 {{ __('Manage Guides') }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('Create, edit and manage publication lifecycle for guides.') }}
                </p>
            </div>
            
            <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                + {{ __('Create Guide') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.guides.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by title or keyword...') }}" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select name="status" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('All') }}</option>
                        <option value="published" {{ $status === 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Category') }}</label>
                    <select name="category_id" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                                {{ __($cat->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl text-sm shadow transition">
                        {{ __('Search') }}
                    </button>
                    @if ($search || $status || $categoryId)
                        <a href="{{ route('admin.guides.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-3 rounded-xl text-sm transition">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Guides Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @if ($guides->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <span class="text-4xl">📝</span>
                    <p class="mt-2 text-base font-medium">{{ __('No guides found matching your criteria.') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">
                            <tr>
                                <th class="px-6 py-4">{{ __('Title') }}</th>
                                <th class="px-6 py-4">{{ __('Category') }}</th>
                                <th class="px-6 py-4">{{ __('City') }}</th>
                                <th class="px-6 py-4">{{ __('Author') }}</th>
                                <th class="px-6 py-4">{{ __('Status') }}</th>
                                <th class="px-6 py-4">{{ __('Date') }}</th>
                                <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($guides as $guide)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 line-clamp-1">
                                            <a href="{{ route('guides.show', $guide->slug) }}" target="_blank" class="hover:text-indigo-600 hover:underline">
                                                {{ __($guide->title) }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">
                                            {{ __($guide->summary) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700">
                                            {{ __($guide->category->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-xs">
                                        {{ $guide->city ? __($guide->city) : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        {{ __($guide->author->name) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($guide->status === 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                ● {{ __('Published') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                ○ {{ __('Draft') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $guide->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold space-x-2">
                                        @can('update', $guide)
                                            <a href="{{ route('admin.guides.edit', $guide) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2.5 py-1.5 rounded-lg border border-indigo-200">
                                                {{ __('Edit') }}
                                            </a>

                                            {{-- Quick Unpublish / Publish button --}}
                                            @if ($guide->status === 'published')
                                                <form action="{{ route('admin.guides.update', $guide) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="title" value="{{ $guide->title }}">
                                                    <input type="hidden" name="summary" value="{{ $guide->summary }}">
                                                    <input type="hidden" name="content" value="{{ $guide->content }}">
                                                    <input type="hidden" name="category_id" value="{{ $guide->category_id }}">
                                                    <input type="hidden" name="city" value="{{ $guide->city }}">
                                                    <input type="hidden" name="status" value="draft">
                                                    <button type="submit" class="text-amber-600 hover:text-amber-900 bg-amber-50 px-2.5 py-1.5 rounded-lg border border-amber-200"
                                                        title="{{ __('Withdraw from public view') }}">
                                                        {{ __('Unpublish') }}
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.guides.update', $guide) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="title" value="{{ $guide->title }}">
                                                    <input type="hidden" name="summary" value="{{ $guide->summary }}">
                                                    <input type="hidden" name="content" value="{{ $guide->content }}">
                                                    <input type="hidden" name="category_id" value="{{ $guide->category_id }}">
                                                    <input type="hidden" name="city" value="{{ $guide->city }}">
                                                    <input type="hidden" name="status" value="published">
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-200"
                                                        title="{{ __('Publish guide') }}">
                                                        {{ __('Publish') }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('delete', $guide)
                                            <form action="{{ route('admin.guides.destroy', $guide) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this guide?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-50 px-2.5 py-1.5 rounded-lg border border-rose-200">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $guides->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
