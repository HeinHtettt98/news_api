<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function index()
    {
        $category = Category::all();
        return response()->json($category);
    }

    function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);
        return response()->json([
            'message' => 'Category successfully',
        ], 200);
    }
}
