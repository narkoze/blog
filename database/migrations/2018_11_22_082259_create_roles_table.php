<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name_en');
            $table->string('name_lv');
            $table->text('description_en');
            $table->text('description_lv');

            $table->timestamps();
        });

        $now = now();
        $roles = [
            [
                'name_en' => 'Developer',
                'name_lv' => 'Izstrādātājs',
                'description_en' => 'Blog maintainer',
                'description_lv' => 'Emuāra uzturētājs',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'User',
                'name_lv' => 'Lietotājs',
                'description_en' => 'Default role',
                'description_lv' => 'Noklusējuma loma',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Administrator',
                'name_lv' => 'Administrators',
                'description_en' => 'Full access to user information',
                'description_lv' => 'Pilna pieeja lietotāju informācijai',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Writer',
                'name_lv' => 'Rakstnieks',
                'description_en' => 'Writes posts',
                'description_lv' => 'Raksta ziņas',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        DB::table('roles')->insert($roles);

        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->nullable();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('set null');
        });

        DB::table('users')
            ->whereId(1)
            ->update(['role_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
}
