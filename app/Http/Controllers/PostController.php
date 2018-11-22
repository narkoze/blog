<?php

namespace Blog\Http\Controllers;

use Blog\PostResourceCollection;
use Illuminate\Http\Request;
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
        // $post = Post::whereId($postId);
        // if ($request->user()->isAdmin()) {
        //     return new PostResource($post->first());
        // }

        // $post = $post->whereNotNull('published_at')->firstOrFail();

        return new PostResource($post);
    }
}
