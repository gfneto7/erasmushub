<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Guide;
use App\Models\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Display listing of guides for CMS.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Guide::class);

        $user = Auth::user();
        $search = $request->query('search');
        $status = $request->query('status');
        $categoryId = $request->query('category_id');

        $query = Guide::query()
            ->with(['category', 'author', 'image'])
            ->latest();

        // If editor, only view their own guides
        if (! $user->isAdmin()) {
            $query->where('author_id', $user->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['draft', 'published'], true)) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $guides = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.guides.index', compact('guides', 'categories', 'search', 'status', 'categoryId'));
    }

    /**
     * Show form for creating a new guide.
     */
    public function create(): View
    {
        Gate::authorize('create', Guide::class);

        $categories = Category::all();

        return view('admin.guides.create', compact('categories'));
    }

    /**
     * Store a newly created guide with validation, HTML sanitization, and transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Guide::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        // Clean & sanitize HTML content (protecting against XSS)
        $cleanContent = strip_tags($validated['content'], '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><blockquote>');

        // Generate a unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Guide::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // Use database transaction as required when creating multiple related records (Guide + Image)
        DB::transaction(function () use ($validated, $slug, $cleanContent, $request) {
            $guide = Guide::forceCreate([
                'title' => $validated['title'],
                'slug' => $slug,
                'summary' => $validated['summary'],
                'content' => $cleanContent,
                'category_id' => $validated['category_id'],
                'author_id' => Auth::id(),
                'city' => $validated['city'] ?? null,
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'published' ? now() : null,
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('guides', 'public');

                Image::create([
                    'guide_id' => $guide->id,
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'alt_text' => $validated['alt_text'] ?? $guide->title,
                ]);
            }
        });

        return redirect()->route('admin.guides.index')
            ->with('status', __('Guide created successfully.'));
    }

    /**
     * Show guide detail in admin context (redirects to public view).
     */
    public function show(Guide $guide): RedirectResponse
    {
        Gate::authorize('update', $guide);

        return redirect()->route('guides.show', $guide->slug);
    }

    /**
     * Show form for editing an existing guide.
     */
    public function edit(Guide $guide): View
    {
        Gate::authorize('update', $guide);

        $categories = Category::all();

        return view('admin.guides.edit', compact('guide', 'categories'));
    }

    /**
     * Update an existing guide.
     */
    public function update(Request $request, Guide $guide): RedirectResponse
    {
        Gate::authorize('update', $guide);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published'],
            'remove_image' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $cleanContent = strip_tags($validated['content'], '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><blockquote>');

        DB::transaction(function () use ($validated, $guide, $cleanContent, $request) {
            // Check if status changed to published for the first time
            $publishedAt = $guide->published_at;
            if ($validated['status'] === 'published' && ! $publishedAt) {
                $publishedAt = now();
            } elseif ($validated['status'] === 'draft') {
                $publishedAt = null;
            }

            $guide->update([
                'title' => $validated['title'],
                'summary' => $validated['summary'],
                'content' => $cleanContent,
                'category_id' => $validated['category_id'],
                'city' => $validated['city'] ?? null,
                'status' => $validated['status'],
                'published_at' => $publishedAt,
            ]);

            // Handle image removal
            if ($request->boolean('remove_image') && $guide->image) {
                Storage::disk('public')->delete($guide->image->path);
                $guide->image->delete();
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                if ($guide->image) {
                    Storage::disk('public')->delete($guide->image->path);
                    $guide->image->delete();
                }

                $file = $request->file('image');
                $path = $file->store('guides', 'public');

                Image::create([
                    'guide_id' => $guide->id,
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'alt_text' => $validated['alt_text'] ?? $guide->title,
                ]);
            }
        });

        return redirect()->route('admin.guides.index')
            ->with('status', __('Guide updated successfully.'));
    }

    /**
     * Delete guide and its associated image.
     */
    public function destroy(Guide $guide): RedirectResponse
    {
        Gate::authorize('delete', $guide);

        DB::transaction(function () use ($guide) {
            if ($guide->image) {
                Storage::disk('public')->delete($guide->image->path);
                $guide->image->delete();
            }
            $guide->delete();
        });

        return redirect()->route('admin.guides.index')
            ->with('status', __('Guide deleted successfully.'));
    }
}
