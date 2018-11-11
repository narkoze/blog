<?php

namespace Blog\Http\Controllers\Profile;

use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\Storage;
use Blog\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Blog\User;

class ImageController extends Controller
{
    protected $rules = [
        'image' => [
            'image',
            'max:5120',
        ],
    ];

    public function index(Request $request)
    {
        $request->validate($this->rules);

        $user = $request->user();

        if ($user->image) {
            Storage::disk('public')->delete([
                "user/original/{$user->image['filename']}",
                "user/medium/{$user->image['filename']}",
                "user/small/{$user->image['filename']}",
            ]);
        }

        $file = $request->file('image');
        $filename = $file->store(null, 'public');

        $img = Img::make(storage_path("app/public/$filename"))->orientate();
        Storage::disk('public')->put(
            "user/original/$filename",
            (string) $img->stream()
        );
        Storage::disk('public')->put(
            "user/medium/$filename",
            (string) $img->fit(640, 640)->stream()
        );
        Storage::disk('public')->put(
            "user/small/$filename",
            (string) $img->fit(100, 100)->stream()
        );
        $img->destroy();

        Storage::disk('public')->delete($filename);

        $imgSize = getimagesize(storage_path("app/public/user/original/$filename"));
        $user->image = collect([
            'filename' => $filename,
            'width' => $imgSize[0],
            'height' => $imgSize[1],
        ]);

        $user->save();

        return response()->json([
            'image' => $user->image,
            'images' => $user->images,
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Storage::disk('public')->delete([
            "user/original/{$user->image['filename']}",
            "user/medium/{$user->image['filename']}",
            "user/small/{$user->image['filename']}",
        ]);

        $user->image = null;
        $user->save();

        return response()->json();
    }
}
