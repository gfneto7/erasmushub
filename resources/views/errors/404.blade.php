<x-app-layout>
    <div class="min-h-[60vh] flex flex-col items-center justify-center px-4 py-16">
        <div class="max-w-md w-full bg-white shadow-sm border border-gray-200 rounded-3xl p-8 text-center">
            <span class="text-5xl">🔍</span>
            <p class="mt-4 text-xs font-bold uppercase tracking-widest text-indigo-600">
                HTTP 404 &bull; Not Found
            </p>
            <h1 class="mt-2 text-2xl font-extrabold text-gray-900">
                {{ __('Página Não Encontrada') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('O recurso ou página solicitada não existe ou foi removida.') }}
            </p>
            <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('home') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('guides.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    {{ __('Guides') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
