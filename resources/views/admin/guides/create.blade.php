<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                ✏️ {{ __('Create Guide') }}
            </h1>
            <a href="{{ route('admin.guides.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                &larr; {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('admin.guides.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8 space-y-6">
            @csrf

            <!-- Validation Errors Callout -->
            @if ($errors->any())
                <div class="rounded-2xl bg-rose-50 p-4 border border-rose-200">
                    <div class="text-sm font-bold text-rose-800 mb-2">
                        ⚠️ {{ __('Please correct the errors below:') }}
                    </div>
                    <ul class="list-disc pl-5 text-xs text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Title') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full text-base rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. Best Neighborhoods for Student Accommodation in Lisbon">
                @error('title')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & City & Status Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        {{ __('Category') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">-- {{ __('Select') }} --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        {{ __('City') }} ({{ __('Erasmus Destination') }})
                    </label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="e.g. Lisboa, Zagreb, Porto..." class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    @error('city')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        {{ __('Status') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>📝 {{ __('Draft') }}</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>🚀 {{ __('Published') }}</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Summary -->
            <div>
                <label for="summary" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Summary') }} ({{ __('Introductory excerpt, max 500 chars') }}) <span class="text-rose-500">*</span>
                </label>
                <textarea name="summary" id="summary" rows="3" required maxlength="500" class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Brief summary of the guide...">{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content (Rich Text Input) -->
            <div>
                <label for="content" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    {{ __('Content') }} ({{ __('Rich text HTML supported') }}) <span class="text-rose-500">*</span>
                </label>
                <!-- Helpful HTML formatting toolbar -->
                <div class="flex flex-wrap gap-1 p-2 bg-gray-50 border border-gray-300 border-b-0 rounded-t-xl text-xs">
                    <button type="button" onclick="insertTag('<strong>', '</strong>')" class="px-2 py-1 bg-white border border-gray-200 rounded font-bold hover:bg-gray-100">B</button>
                    <button type="button" onclick="insertTag('<em>', '</em>')" class="px-2 py-1 bg-white border border-gray-200 rounded italic hover:bg-gray-100">I</button>
                    <button type="button" onclick="insertTag('<h2>', '</h2>')" class="px-2 py-1 bg-white border border-gray-200 rounded font-semibold hover:bg-gray-100">H2</button>
                    <button type="button" onclick="insertTag('<h3>', '</h3>')" class="px-2 py-1 bg-white border border-gray-200 rounded font-semibold hover:bg-gray-100">H3</button>
                    <button type="button" onclick="insertTag('<p>', '</p>')" class="px-2 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">&lt;p&gt;</button>
                    <button type="button" onclick="insertTag('<ul>\n  <li>', '</li>\n</ul>')" class="px-2 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">&bull; List</button>
                    <button type="button" onclick="insertTag('<blockquote>', '</blockquote>')" class="px-2 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">&ldquo; Quote</button>
                </div>
                <textarea name="content" id="guide_content" rows="10" required class="w-full text-sm font-mono rounded-b-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="<p>Full detailed content of the guide...</p>">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload & Alt Text -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <label for="image" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        {{ __('Upload Image') }} ({{ __('JPG, PNG, WEBP, max 2MB') }})
                    </label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('image')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alt_text" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        {{ __('Alt Text (Accessibility)') }}
                    </label>
                    <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text') }}" placeholder="Description of the image..." class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.guides.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow transition">
                    💾 {{ __('Create Guide') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function insertTag(openTag, closeTag) {
            const textarea = document.getElementById('guide_content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selectedText = text.substring(start, end);
            const replacement = openTag + selectedText + closeTag;
            textarea.value = text.substring(0, start) + replacement + text.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + openTag.length, end + openTag.length);
        }
    </script>
</x-app-layout>
