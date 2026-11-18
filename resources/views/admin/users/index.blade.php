<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    👥 {{ __('User Management') }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('View registered users, modify assigned roles, and toggle account activation.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search by title or keyword...') }}" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Role') }}</label>
                    <select name="role_id" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">-- {{ __('All') }} --</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}" {{ (string)$roleId === (string)$r->id ? 'selected' : '' }}>
                                {{ ucfirst($r->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select name="status" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">-- {{ __('All') }} --</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl text-sm shadow transition">
                        {{ __('Search') }}
                    </button>
                    @if ($search || $roleId || $status)
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-3 rounded-xl text-sm transition">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">
                        <tr>
                            <th class="px-6 py-4">{{ __('Name') }}</th>
                            <th class="px-6 py-4">{{ __('Email') }}</th>
                            <th class="px-6 py-4">{{ __('Role') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Registered') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs uppercase">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-xs font-mono">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->isAdmin())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-purple-100 text-purple-800 border border-purple-200">
                                            👑 Administrator
                                        </span>
                                    @elseif ($user->isEditor())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-sky-100 text-sky-800 border border-sky-200">
                                            ✍️ Editor
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 border border-gray-200">
                                            👤 Registered
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            ● {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                            ✕ {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2.5 py-1.5 rounded-lg border border-indigo-200">
                                        {{ __('Edit') }}
                                    </a>

                                    @if (Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg border {{ $user->is_active ? 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }}">
                                                {{ $user->is_active ? __('Deactivate') : __('Activate') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs italic px-2 py-1">({{ __('Current') }})</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
