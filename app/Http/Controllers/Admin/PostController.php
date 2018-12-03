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

    public function store(Request $request, $locale)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            Post::whereAuthorId($request->user()->id)->count() > 2,
            403,
            $locale == 'en'
                ? 'You can create only 3 posts'
                : 'Jūs drīkstat izveidot tikai 3 ziņas'
        );

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
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $post->author->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can edit only your posts'
                : 'Jūs drīkstat labot tikai savas ziņas'
        );

        $rules = $this->rules;
        $rules['title_en'][] = "unique:posts,title_en,{$post->id}";
        $rules['title_lv'][] = "unique:posts,title_lv,{$post->id}";
        $request->validate($rules);

        $this->save($request, $post);

        return new PostResource($post);
    }

    public function destroy(Request $request, $locale, Post $post)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $post->author->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can delete only your posts'
                : 'Jūs drīkstat dzēst tikai savas ziņas'
        );

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
        $post->tags()->sync(array_pluck($request->tags, 'id'));
    }
}
