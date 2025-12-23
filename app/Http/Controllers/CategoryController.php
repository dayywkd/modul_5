<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::withCount('tickets as request_count')->orderBy('name')->get());
    }

    public function show($slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return response()->json($category);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($data);

        return response()->json(['message' => 'Category created', 'data' => $category], 201);
    }

    public function update(Request $request, $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($data);

        return response()->json(['message' => 'Category updated', 'data' => $category]);
    }

    public function destroy($slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
