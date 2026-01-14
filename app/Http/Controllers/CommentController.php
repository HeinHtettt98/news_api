<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $comment = Comment::create($data);
        return response()->json([
            'message' => 'Comment is Successful',
        ], 200);
    }
}
