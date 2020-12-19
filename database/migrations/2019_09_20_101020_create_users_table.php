<?php


class CreateUsersTable extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->string("password");
            $table->timestamps();
        });
    }
}
