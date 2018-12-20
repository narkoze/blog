<?php

namespace Blog\Services;

use Illuminate\Support\Facades\Storage;
use DB;

class UserService
{

    public function delete($user)
    {
        $this->deleteImages($user);
        $this->signout($user);
        $user->delete();
    }

    public function signout($user)
    {
        $tokens = $user->tokens->pluck('id');

        DB::table('oauth_access_tokens')
            ->whereIn('id', $tokens)
            ->delete();

        DB::table('oauth_refresh_tokens')
            ->whereIn('access_token_id', $tokens)
            ->delete();
    }

    public function deleteImages($user)
    {
        if (!$user->image) {
            return;
        }

        Storage::disk('public')->delete([
            "user/original/{$user->image['filename']}",
            "user/medium/{$user->image['filename']}",
            "user/small/{$user->image['filename']}",
        ]);
    }
}
