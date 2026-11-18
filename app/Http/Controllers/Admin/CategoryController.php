<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display listing of categories with guide counts.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Category::class);

        $categories = Category::withCount('guides')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show form for creating a category.
     */
    public function create(): View
    {
        Gate::authorize('create', Category::class);

        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('status', __('Category created successfully.'));
    }

    /**
     * Show form for editing an existing category.
     */
    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('status', __('Category updated successfully.'));
    }

    /**
     * Delete a category, strictly forbidding deletion if guides are assigned.
     */
    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        // Section 5 Requirement: Proibir a exclusão de uma categoria enquanto ela contiver notícias/guias
        if ($category->guides()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', __('Cannot delete category because it contains associated guides.'));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('status', __('Category deleted successfully.'));
    }
}
