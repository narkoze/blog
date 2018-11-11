<?php

namespace Blog\Http\Controllers;

use Blog\Http\Resources\User as UserResource;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client as GuzzleClient;
use Blog\Services\UserService;
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

        $apiClient = DB::table('oauth_clients')->whereId(2)->first();
        if (!$apiClient) {
            return response()->json(['message' => 'API Client not found'], 500);
        }

        try {
            $guzzleClient = new GuzzleClient;
            $tokenRequest = $guzzleClient->post(config('services.passport.endpoint'), [
                'form_params' => [
                    'username' => $request->email,
                    'password' => $request->password,
                    'grant_type' => 'password',
                    'client_id' => $apiClient->id,
                    'client_secret' => $apiClient->secret,
                ],
            ]);
        } catch (RequestException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json([
            'token' => json_decode((string) $tokenRequest->getBody(), true),
            'user' => new UserResource(User::whereEmail($request->email)->first()),
        ]);
    }

    public function signout(Request $request, UserService $userServ)
    {
        $userServ->signout($request->user());

        return response()->json(null, 204);
    }
}
