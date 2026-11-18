<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ErasmusHub' : config('app.name', 'ErasmusHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-800 flex flex-col min-h-screen">
        @include('layouts.navigation')

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 border border-emerald-200">
                    <div class="flex">
                        <div class="flex-shrink-0 text-emerald-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm font-medium text-emerald-800">
                            {{ session('status') }}
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-rose-50 p-4 border border-rose-200">
                    <div class="flex">
                        <div class="flex-shrink-0 text-rose-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm font-medium text-rose-800">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-sky-600 bg-clip-text text-transparent">
                            🎓 ErasmusHub
                        </span>
                        <span class="text-gray-400">|</span>
                        <p class="text-xs text-gray-500">
                            {{ __('Design web avançado de serviços web') }}
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center space-x-6 text-sm text-gray-500">
                        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">{{ __('Home') }}</a>
                        <a href="{{ route('guides.index') }}" class="hover:text-indigo-600 transition">{{ __('Guides') }}</a>
                        <a href="{{ route('about') }}" class="hover:text-indigo-600 transition">{{ __('About') }}</a>
                        <a href="{{ url('/api/v1/guides') }}" target="_blank" class="hover:text-indigo-600 transition">REST API (JSON)</a>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400">
                    <p>&copy; {{ date('Y') }} ErasmusHub. {{ __('All rights reserved.') }}</p>
                    <div class="mt-2 sm:mt-0 flex items-center space-x-2">
                        <span>{{ __('Language') }}:</span>
                        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-indigo-600' : 'hover:text-gray-600' }}">🇬🇧 EN</a>
                        <span>•</span>
                        <a href="{{ route('locale.switch', 'pt') }}" class="{{ app()->getLocale() === 'pt' ? 'font-bold text-indigo-600' : 'hover:text-gray-600' }}">🇵🇹 PT</a>
                        <span>•</span>
                        <a href="{{ route('locale.switch', 'hr') }}" class="{{ app()->getLocale() === 'hr' ? 'font-bold text-indigo-600' : 'hover:text-gray-600' }}">🇭🇷 HR</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
