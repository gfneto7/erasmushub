<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Retrieve all categories.
     * Status 200.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount(['guides' => function ($q) {
            $q->published();
        }])
        ->orderBy('name')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ], 200, ['Content-Type' => 'application/json']);
    }
}
