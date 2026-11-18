<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    🏷️ {{ __('Categories Management') }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('Manage guide categories and enforce relationship integrity.') }}
                </p>
            </div>
            
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                + {{ __('Create Category') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">
                        <tr>
                            <th class="px-6 py-4">{{ __('Category Name') }}</th>
                            <th class="px-6 py-4">{{ __('Slug') }}</th>
                            <th class="px-6 py-4">{{ __('Description') }}</th>
                            <th class="px-6 py-4">{{ __('Guides Count') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($categories as $cat)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $cat->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                    {{ $cat->slug }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                                    {{ $cat->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $cat->guides_count > 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $cat->guides_count }} {{ __('Guides') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold space-x-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2.5 py-1.5 rounded-lg border border-indigo-200">
                                        {{ __('Edit') }}
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this category?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-50 px-2.5 py-1.5 rounded-lg border border-rose-200">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
