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
        'password',
        'email',
    ];

    protected $dates = [
        'email_verified_at',
    ];

    protected $casts = [
        'image' => 'collection',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id')
            ->whereNotNull('published_at');
    }

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

    public function getEmailMaskedAttribute()
    {
        $parts = explode("@", $this->email);
        $name = implode(array_slice($parts, 0, count($parts) -1), '@');
        $len  = floor(strlen($name) / 2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($parts);
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
