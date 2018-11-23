<?php

namespace Blog\Http\Controllers;

use Illuminate\Http\Request;
use Blog\PostResourceCollection;
use Blog\PostResource;
use Blog\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();

        $query = Post::with([
            'author',
        ])
        ->whereNotNull('published_at')
        ->orderBy('published_at', 'desc');

        $posts = new PostResourceCollection($query->paginate(10));

        return $posts->additional(compact('params'));
    }

    public function show(Request $request, $locale, Post $post)
    {
        if (!$post->published_at) {
            abort_unless(auth('api')->user(), 404);
            abort_unless(in_array(auth('api')->user()->role->id, [1, 3, 4]), 404);
        }

        return new PostResource($post);
    }
}
