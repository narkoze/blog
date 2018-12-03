<?php

namespace Blog\Http\Controllers\Admin;

use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\Storage;
use Blog\Repositories\ImageRepository;
use Blog\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Blog\ImageResourceCollection;
use Blog\ImageResource;
use Blog\Image;

class ImageController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
        'image' => 'image|max:5120',
    ];

    public function index(ImageRepository $imageRepo, Request $request)
    {
        $params = $request->all() + $imageRepo->params();

        $images = $imageRepo->images($params);
        if ($params['page']) {
            $images = new ImageResourceCollection($images->paginate(21));
        } else {
            $images = new ImageResourceCollection($images->get());
        }

        return $images->additional(compact('params'));
    }

    public function show($locale, Image $image)
    {
        return new ImageResource($image);
    }

    public function store(Request $request, $locale)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            Image::whereAuthorId($request->user()->id)->count() > 2,
            403,
            $locale == 'en'
                ? 'You can upload only 3 images'
                : 'Jūs drīkstat augšupielādēt tikai 3 attēlus'
        );

        $request->validate($this->rules);

        $image = new Image();
        $image->fill($request->all());
        $image->author()->associate($request->user());

        $file = $request->file('image');
        $image->filename = $file->store(null, 'public');
        $image->size = $file->getSize();

        $img = Img::make(storage_path("app/public/$image->filename"))->orientate();
        Storage::disk('public')->put(
            "image/original/$image->filename",
            (string) $img->stream()
        );
        Storage::disk('public')->put(
            "image/medium/$image->filename",
            (string) $img->fit(640, 360)->stream()
        );
        Storage::disk('public')->put(
            "image/small/$image->filename",
            (string) $img->fit(100, 100)->stream()
        );
        $img->destroy();

        Storage::disk('public')->delete($image->filename);

        $imgSize = getimagesize(storage_path("app/public/image/original/$image->filename"));
        $image->width = $imgSize[0];
        $image->height = $imgSize[1];

        $exif = $img->exif();
        if ($exif) {
            $model = [];
            if (isset($exif['Make'])) {
                $model[] = $exif['Make'];
            }
            if (isset($exif['Model'])) {
                $model[] = $exif['Model'];
            }
            if ($model) {
                $image->model = implode(" ", $model);
            }
        }

        $type = null;
        switch ($imgSize['mime']) {
            case "image/gif": $type = 'GIF'; break;
            case "image/jpeg": $type = 'JPEG'; break;
            case "image/png": $type = 'PNG'; break;
            case "image/bmp": $type = 'BMP'; break;
        }
        if ($type) {
            $image->type = $type;
        }
        $image->save();

        return new ImageResource($image);
    }

    public function update(Request $request, $locale, Image $image)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $image->author->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can edit only your images'
                : 'Jūs drīkstat labot tikai savus attēlus'
        );

        $request->validate($this->rules);

        $image->update($request->all());

        return new ImageResource($image);
    }

    public function destroy(Request $request, $locale, Image $image)
    {
        // Remove abort_if(current project is in production state)
        abort_if(
            !in_array($request->user()->role->id, [1, 3]) and
            $image->author->id != $request->user()->id,
            403,
            $locale == 'en'
                ? 'You can delete only your images'
                : 'Jūs drīkstat dzēst tikai savus attēlus'
        );

        Storage::disk('public')->delete("image/original/$image->filename");
        Storage::disk('public')->delete("image/medium/$image->filename");
        Storage::disk('public')->delete("image/small/$image->filename");

        $image->delete();

        return response()->json();
    }
}
