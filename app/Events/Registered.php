<?php

namespace Blog\Events;

use Illuminate\Queue\SerializesModels;

class Registered
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    public $locale;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  String $locale
     * @return void
     */
    public function __construct($user, $locale)
    {
        $this->user = $user;
        $this->locale = $locale;
    }
}
