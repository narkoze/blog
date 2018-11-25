<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Http\Controllers\Controller;
use Blog\Repositories\TagRepository;
use Illuminate\Http\Request;
use Blog\TagResourceCollection;
use Blog\TagResource;
use Blog\Tag;

class TagController extends Controller
{
    protected $rules = [
        'name_en' => [
            'required',
            'max:255',
        ],
        'name_lv' => [
            'required',
            'max:255',
        ],
    ];

    public function index(TagRepository $tagRepo, Request $request, $locale)
    {
        $params = $request->all() + $tagRepo->params();

        $tags = $tagRepo->tags($locale, $params);
        if ($params['page']) {
            $tags = new TagResourceCollection($tags->paginate(10));
        } else {
            $tags = new TagResourceCollection($tags->get());
        }

        return $tags->additional(compact('params'));
    }

    public function show($locale, Tag $tag)
    {
        return new TagResource($tag);
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['name_en'][] = 'unique:tags,name_en';
        $rules['name_lv'][] = 'unique:tags,name_lv';
        $request->validate($rules);

        return new TagResource(Tag::create($request->all()));
    }

    public function update(Request $request, $locale, Tag $tag)
    {
        $rules = $this->rules;
        $rules['name_en'][] = "unique:tags,name_en,{$tag->id}";
        $rules['name_lv'][] = "unique:tags,name_lv,{$tag->id}";
        $request->validate($rules);

        $tag->update($request->all());

        return new TagResource($tag);
    }

    public function destroy($locale, Tag $tag)
    {
        $tag->delete();

        return response()->json();
    }
}
