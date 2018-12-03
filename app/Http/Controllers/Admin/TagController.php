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

    public function store(Request $request, $locale)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            Tag::whereCreatedBy($request->user()->id)->count() > 2,
            403,
            $locale == 'en'
                ? 'You can create only 3 tags'
                : 'Jūs drīkstat izveidot tikai 3 tēmturus'
        );

        $rules = $this->rules;
        $rules['name_en'][] = 'unique:tags,name_en';
        $rules['name_lv'][] = 'unique:tags,name_lv';
        $request->validate($rules);

        $tag = new Tag();
        $tag->fill($request->all());
        $tag->createdBy()->associate($request->user());
        $tag->save();

        return new TagResource($tag);
    }

    public function update(Request $request, $locale, Tag $tag)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $tag->createdBy->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can edit only your tags'
                : 'Jūs drīkstat labot tikai savus tēmturus'
        );

        $rules = $this->rules;
        $rules['name_en'][] = "unique:tags,name_en,{$tag->id}";
        $rules['name_lv'][] = "unique:tags,name_lv,{$tag->id}";
        $request->validate($rules);

        $tag->update($request->all());

        return new TagResource($tag);
    }

    public function destroy($locale, Tag $tag)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $tag->createdBy->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can delete only your tags'
                : 'Jūs drīkstat dzēst tikai savus tēmturus'
        );

        $tag->delete();

        return response()->json();
    }
}
