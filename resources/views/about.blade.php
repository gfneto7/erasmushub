<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">
            ℹ️ {{ __('About the Project Title') }}
        </h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <!-- Overview Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-200">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-4">
                🎓 ErasmusHub &bull; Project Overview
            </span>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Course Project Overview') }}
            </h2>
            <p class="mt-4 text-gray-600 leading-relaxed text-base">
                {{ __('About Description') }}
            </p>

            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 border-t border-gray-100 text-center">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <span class="text-2xl font-black text-indigo-600 block">PHP 8.5</span>
                    <span class="text-xs text-gray-500 font-medium">Backend & MVC</span>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <span class="text-2xl font-black text-indigo-600 block">Laravel</span>
                    <span class="text-xs text-gray-500 font-medium">Framework & ORM</span>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <span class="text-2xl font-black text-indigo-600 block">MySQL</span>
                    <span class="text-xs text-gray-500 font-medium">Relational DB</span>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <span class="text-2xl font-black text-indigo-600 block">REST + Open-Meteo</span>
                    <span class="text-xs text-gray-500 font-medium">Internal & External APIs</span>
                </div>
            </div>
        </div>

        <!-- 5 Key Pillars / Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <span class="text-3xl">🌐</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ __('Feature 1 Title') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Feature 1 Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center space-x-2 text-xs font-semibold text-indigo-600">
                    <span>🇬🇧 English</span>
                    <span>•</span>
                    <span>🇵🇹 Português</span>
                    <span>•</span>
                    <span>🇭🇷 Hrvatski</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <span class="text-3xl">✍️</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ __('Feature 2 Title') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Feature 2 Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500">
                    Draft &bull; Published &bull; Rich Text &bull; Images
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <span class="text-3xl">🛡️</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ __('Feature 3 Title') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Feature 3 Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500">
                    Visitor &bull; Registered &bull; Editor &bull; Admin
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <span class="text-3xl">⚙️</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ __('Feature 4 Title') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Feature 4 Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ url('/api/v1/guides') }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        View API Endpoint &rarr;
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <span class="text-3xl">🌤️</span>
                    <h3 class="text-lg font-bold text-gray-900 mt-3">{{ __('Feature 5 Title') }}</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ __('Feature 5 Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500">
                    Open-Meteo &bull; 30m Cache &bull; 3s Timeout
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-sky-700 text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-3xl">📑</span>
                    <h3 class="text-lg font-bold mt-3">{{ __('Seminar Topic') }}</h3>
                    <p class="mt-2 text-sm text-indigo-100 leading-relaxed">{{ __('Seminar Description') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-indigo-400/40 text-xs text-indigo-200">
                    Individual Presentation &bull; Academic Year 2025/2026
                </div>
            </div>
        </div>

        <!-- API Testing & JSON Schema Demo Section -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                🔌 {{ __('Own REST API & JSON Schema Documentation') }}
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                {{ __('Our REST API exposes standard endpoints with semantic status codes (200, 201, 204, 400, 401, 403, 404, 500) and strict JSON Schema validation.') }}
            </p>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">{{ __('Method') }}</th>
                            <th class="px-4 py-3">{{ __('Endpoint') }}</th>
                            <th class="px-4 py-3">{{ __('Authorization') }}</th>
                            <th class="px-4 py-3">{{ __('Description') }}</th>
                            <th class="px-4 py-3">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-600">GET</td>
                            <td class="px-4 py-3 font-mono text-gray-800"><a href="{{ url('/api/v1/guides') }}" target="_blank" class="hover:underline">/api/v1/guides</a></td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Public') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('List published guides (paginated, filters: search, city, category_id)') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">200 OK</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-600">GET</td>
                            <td class="px-4 py-3 font-mono text-gray-800">/api/v1/guides/{id}</td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Public') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('Get single published guide (draft returns 404)') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">200, 404</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-blue-600">POST</td>
                            <td class="px-4 py-3 font-mono text-gray-800">/api/v1/guides</td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Editor / Admin') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('Create new guide with validation') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">201, 400, 403</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-amber-600">PATCH</td>
                            <td class="px-4 py-3 font-mono text-gray-800">/api/v1/guides/{id}</td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Author / Admin') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('Update existing guide') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">200, 400, 403, 404</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-rose-600">DELETE</td>
                            <td class="px-4 py-3 font-mono text-gray-800">/api/v1/guides/{id}</td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Author / Admin') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('Delete guide (empty body on success)') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">204 No Content</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-emerald-600">GET</td>
                            <td class="px-4 py-3 font-mono text-gray-800"><a href="{{ url('/api/v1/categories') }}" target="_blank" class="hover:underline">/api/v1/categories</a></td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Public') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('List all categories with published guides count') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">200 OK</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-purple-600">POST</td>
                            <td class="px-4 py-3 font-mono text-gray-800">/api/v1/validate-guide-schema</td>
                            <td class="px-4 py-3 text-gray-500">{{ __('Public') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ __('Validates incoming payload against JSON Schema definition') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600">200 Valid, 400 Invalid</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Schema file location: <code class="bg-gray-100 px-2 py-0.5 rounded font-mono">/public/schemas/guide-schema.json</code></span>
                <a href="{{ url('/schemas/guide-schema.json') }}" target="_blank" class="text-indigo-600 font-bold hover:underline">
                    {{ __('View raw JSON Schema file') }} &rarr;
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
