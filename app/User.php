<?php

namespace Blog;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Blog\Notifications\EmailVerificationNotification;
use Blog\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'locale',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $dates = [
        'email_verified_at',
    ];

    protected $casts = [
        'image' => 'collection',
    ];

    public function getImagesAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return [
            'original' => Storage::disk('public')->url("user/original/{$this->image['filename']}"),
            'medium' => Storage::disk('public')->url("user/medium/{$this->image['filename']}"),
            'small' => Storage::disk('public')->url("user/small/{$this->image['filename']}"),
        ];
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendCustomEmailVerificationNotification($locale)
    {
        $this->notify(new EmailVerificationNotification($locale));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}
