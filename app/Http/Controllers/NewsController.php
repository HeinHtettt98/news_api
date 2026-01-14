<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\FilterNewsRequest;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    function index(FilterNewsRequest $request)
    {
        try {
            $category = $request->validated('category'); // from FormRequest
            $search   = $request->query('search');

            $news = News::with('category')
                ->when($category, function ($query) use ($category) {
                    $query->whereHas('category', function ($q) use ($category) {
                        $q->where('slug', $category);
                    });
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                })

                ->latest()
                ->paginate(6);

            return response()->json([
                'data' => $news->items(),
                'meta' => [
                    'current_page' => $news->currentPage(),
                    'last_page'    => $news->lastPage(),
                    'per_page'     => $news->perPage(),
                    'total'        => $news->total(),
                ],
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    function store(StoreNewsRequest $newRequest)
    {
        try {
            $data = $newRequest->validated();
            if ($newRequest->hasFile('image')) {
                $imagePath = $newRequest->file('image')->store('news_images', 'public');
                $data['image'] = $imagePath;
            }
            $data['user_id'] = Auth::id();
            $news = News::create($data);
            return response()->json($news, 201); //code...
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e,
            ], 500);
        }
    }

    public function show(News $news)
    {
        return response()->json(
            $news->load('comments'),
            200
        );
    }

    function destory(News $news)
    {
        try {
            $news->delete();
            return response()->json([
                'message' => 'News article deleted successfully',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete news article',
            ], 500);
        }
    }
}
