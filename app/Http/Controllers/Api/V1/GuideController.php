<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    /**
     * Retrieve published guides with pagination, search, category, and city filters.
     * Status 200.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $city = $request->query('city');
        $perPage = min((int) ($request->query('per_page', 10)), 50);

        $query = Guide::query()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name', 'image:id,guide_id,path,alt_text'])
            ->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($city) {
            $query->where('city', $city);
        }

        $paginated = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
            'links' => [
                'first' => $paginated->url(1),
                'last' => $paginated->url($paginated->lastPage()),
                'prev' => $paginated->previousPageUrl(),
                'next' => $paginated->nextPageUrl(),
            ],
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Retrieve single published guide.
     * Prevents access to unpublished/draft guides (returns 404).
     */
    public function show(string $id): JsonResponse
    {
        $guide = Guide::with(['category', 'author:id,name', 'image'])
            ->find($id);

        if (! $guide || $guide->status !== 'published') {
            return response()->json([
                'status' => 'error',
                'message' => 'Guide not found or unpublished.',
            ], 404, ['Content-Type' => 'application/json']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $guide,
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Create a new guide via API with authorization and validation.
     * Status 201 on success, 400 on validation failure, 401/403 on authorization failure.
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401, ['Content-Type' => 'application/json']);
        }

        if (! Gate::forUser($user)->allows('create', Guide::class)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. Only editors and administrators can create guides.',
            ], 403, ['Content-Type' => 'application/json']);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:draft,published'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400, ['Content-Type' => 'application/json']);
        }

        $validated = $validator->validated();
        $status = $validated['status'] ?? 'published';

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Guide::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $guide = Guide::forceCreate([
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'],
            'content' => strip_tags($validated['content'], '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><blockquote>'),
            'category_id' => $validated['category_id'],
            'author_id' => $user->id,
            'city' => $validated['city'] ?? null,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Guide created successfully.',
            'data' => $guide->load(['category', 'author:id,name']),
        ], 201, ['Content-Type' => 'application/json']);
    }

    /**
     * Update an existing guide via API.
     * Status 200 on success, 400 on validation failure, 403 on forbidden, 404 on not found.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401, ['Content-Type' => 'application/json']);
        }

        $guide = Guide::find($id);
        if (! $guide) {
            return response()->json([
                'status' => 'error',
                'message' => 'Guide not found.',
            ], 404, ['Content-Type' => 'application/json']);
        }

        if (! Gate::forUser($user)->allows('update', $guide)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to modify this guide.',
            ], 403, ['Content-Type' => 'application/json']);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'summary' => ['sometimes', 'required', 'string', 'max:500'],
            'content' => ['sometimes', 'required', 'string'],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'city' => ['nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'required', 'in:draft,published'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400, ['Content-Type' => 'application/json']);
        }

        $validated = $validator->validated();

        if (isset($validated['content'])) {
            $validated['content'] = strip_tags($validated['content'], '<p><br><strong><em><b><i><u><h2><h3><h4><ul><ol><li><a><blockquote>');
        }

        if (isset($validated['status']) && $validated['status'] === 'published' && ! $guide->published_at) {
            $validated['published_at'] = now();
        }

        $guide->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Guide updated successfully.',
            'data' => $guide->fresh(['category', 'author:id,name']),
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Delete guide via API.
     * Status 204 with NO body as required by the specification.
     */
    public function destroy(string $id): Response|JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401, ['Content-Type' => 'application/json']);
        }

        $guide = Guide::find($id);
        if (! $guide) {
            return response()->json([
                'status' => 'error',
                'message' => 'Guide not found.',
            ], 404, ['Content-Type' => 'application/json']);
        }

        if (! Gate::forUser($user)->allows('delete', $guide)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to delete this guide.',
            ], 403, ['Content-Type' => 'application/json']);
        }

        $guide->delete();

        // 204 No Content response has NO body
        return response()->noContent();
    }
}
