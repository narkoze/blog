<?php

namespace Blog\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Blog\CommentResourceCollection;
use Blog\CommentResource;
use Blog\Comment;
use Blog\Post;

class PostCommentController extends Controller
{
    protected $rules = [
        'comment' => [
            'required',
        ],
    ];

    public function index($locale, Post $post)
    {
        $comments = $post->comments()
            ->orderBy('created_at', 'asc')
            ->get();

        return new CommentResourceCollection($comments);
    }

    public function store(Request $request, $locale, Post $post)
    {
        $request->validate($this->rules);

        $comment = new Comment();
        $comment->fill($request->all());
        $comment->author()->associate($request->user());
        $post->comments()->save($comment);

        return new CommentResource($comment);
    }

    public function update(Request $request, $locale, Post $post, Comment $comment)
    {
        abort_if($comment->author->id != auth()->user()->id and !in_array($request->user()->role->id, [1, 3]), 403);

        $request->validate($this->rules);

        $comment->fill($request->all());
        $post->comments()->save($comment);

        return new CommentResource($comment);
    }

    public function destroy(Request $request, $locale, Post $post, Comment $comment)
    {
        abort_if($comment->author->id != auth()->user()->id and !in_array($request->user()->role->id, [1, 3]), 403);

        $comment->delete();

        return response()->json();
    }
}
