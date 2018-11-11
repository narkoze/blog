<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersLocaleField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TYPE users_locale AS ENUM (
            'en',
            'lv'
        )");
        DB::statement("ALTER TABLE users ADD COLUMN locale users_locale DEFAULT 'en'");
    }
}
