<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                🏷️ {{ __('Create Category') }}
            </h1>
            <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Category Name') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full text-base rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. Nightlife & Events">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Description') }}
                </label>
                <textarea name="description" id="description" rows="4" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Short description of what guides belong to this category...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow transition">
                    💾 {{ __('Save Category') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
