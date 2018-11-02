<?php

namespace Blog\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Blog\User;
use DB;

class AuthController extends Controller
{

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $tokenRequest = app()->handle(Request::create(
            config('services.passport.endpoint'),
            'POST',
            [
                'username' => $request->email,
                'password' => $request->password,
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => DB::table('oauth_clients')->whereId(2)->first()->secret,
            ]
        ));

        $tokenResponse = json_decode((string) $tokenRequest->getContent(), true);

        $token = $tokenResponse['access_token'];
        $tokenType = $tokenResponse['token_type'];

        return response()->json("$tokenType $token");
    }

    public function auth(Request $request)
    {
        return $request->user();
    }

    public function signout()
    {
        $tokens = auth()->user()->tokens->pluck('id');

        DB::table('oauth_access_tokens')
            ->whereIn('id', $tokens)
            ->delete();

        DB::table('oauth_refresh_tokens')
            ->whereIn('access_token_id', $tokens)
            ->delete();

        return response()->json(null, 204);
    }
}
