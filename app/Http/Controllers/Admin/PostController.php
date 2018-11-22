<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Http\Controllers\Controller;
use Blog\Repositories\PostRepository;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Blog\PostResourceCollection;
use Blog\PostResource;
use Blog\Post;

class PostController extends Controller
{
    protected $rules = [
        'title_en' => [
            'required',
            'max:255',
        ],
        'title_lv' => [
            'required',
            'max:255',
        ],
        'content_en' => 'required',
        'content_lv' => 'required',
    ];

    public function index(PostRepository $postRepo, Request $request, $locale)
    {
        $params = $request->all() + $postRepo->params();
        $posts = new PostResourceCollection($postRepo->posts($locale, $params)->paginate(10));

        return $posts->additional(compact('params'));
    }

    public function show($locale, Post $post)
    {
        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['title_en'][] = 'unique:posts,title_en';
        $rules['title_lv'][] = 'unique:posts,title_lv';
        $request->validate($rules);

        $post = new Post();
        $post->author()->associate($request->user());

        $this->save($request, $post);

        return new PostResource($post);
    }

    public function update(Request $request, $locale, Post $post)
    {
        $rules = $this->rules;
        $rules['title_en'][] = "unique:posts,title_en,{$post->id}";
        $rules['title_lv'][] = "unique:posts,title_lv,{$post->id}";
        $request->validate($rules);

        $this->save($request, $post);

        return new PostResource($post);
    }

    public function destroy($locale, Post $post)
    {
        $post->delete();

        return response()->json();
    }

    protected function save($request, $post)
    {
        $post->fill($request->all());

        if ($request->save) {
            $post->published_at = null;
        } elseif (!$post->published_at) {
            $post->published_at = Carbon::now();
        }

        $post->save();
    }
}
