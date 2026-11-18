<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                👥 {{ __('Edit User') }}: <span class="text-indigo-600">{{ $user->name }}</span>
            </h1>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8 space-y-6">
            @csrf
            @method('PATCH')

            <!-- User Info (Read-only) -->
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('Name') }}:</span>
                    <span class="font-bold text-gray-800">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('Email') }}:</span>
                    <span class="font-mono text-gray-800">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('Status') }}:</span>
                    <span class="font-semibold {{ $user->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Role') }} <span class="text-rose-500">*</span>
                </label>
                <select name="role_id" id="role_id" required class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                            @if ($role->name === 'admin')
                                (Full access to all guides, categories and users)
                            @elseif ($role->name === 'editor')
                                (Can create and edit own guides)
                            @else
                                (Standard registered user)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow transition">
                    💾 {{ __('Update User') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
