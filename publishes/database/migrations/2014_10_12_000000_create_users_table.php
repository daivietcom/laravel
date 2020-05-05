<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable()->unique();
            $table->char('phone', 15)->nullable()->unique();
            $table->char('password', 60);
            $table->string('display_name');
            $table->boolean('status')->default(false);
            $table->char('group_code', 40)->references('code')->on('groups');
            $table->text('money')->nullable();
            $table->text('social')->nullable();
            $table->text('option')->nullable();
            $table->text('extend')->nullable();
            $table->char('api_token', 60)->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
