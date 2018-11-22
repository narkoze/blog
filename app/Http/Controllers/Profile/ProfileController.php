<?php

namespace Blog\Http\Controllers\Profile;

use Blog\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Blog\Services\UserService;
use Illuminate\Http\Request;
use Blog\UserResource;
use Blog\User;

class ProfileController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'between:2,20',
        ],
        'email' => [
            'required',
            'max:255',
            'email',
        ],
        'locale' => [
            'in:en,lv',
        ],
        'password' => [
            'required_with:new_password,new_password_confirmation',
        ],
        'new_password' => [
            'required_with:password,new_password_confirmation',
            'min:6',
            'confirmed',
        ],
        'new_password_confirmation' => [
            'required_with:password,new_password',
            'min:6',
        ],
    ];

    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(Request $request, $locale)
    {
        $user = $request->user();

        $rules = $this->rules;
        $rules['email'][] = "unique:users,email,{$user->id}";
        $rules['password'][] = (
            function ($attribute, $value, $fail) use ($locale, $user) {
                if (!Hash::check($value, $user->password)) {
                    $fail(trans('validation.invalid', [], $locale));
                }
            }
        );
        $request->validate($rules);

        $user->fill($request->all());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendCustomEmailVerificationNotification($locale);
        }

        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return new UserResource($user);
    }

    public function destroy(Request $request, UserService $userServ, $locale)
    {
        $userServ->delete($request->user());

        return response()->json();
    }
}
