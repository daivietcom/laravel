<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('source');
            $table->string('thumb')->nullable();
            $table->char('type', 20);
            $table->char('mime', 20)->nullable();
            $table->integer('size')->unsigned();
            $table->char('width', 4)->nullable();
            $table->char('height', 4)->nullable();
            $table->char('md5', 32)->nullable();
            $table->char('sha1', 40)->nullable();
            $table->integer('folder');
            $table->text('extend')->nullable();
            $table->char('model', 80);
            $table->timestamps();
            $table->integer('created_by')->references('id')->on('users');
            $table->integer('updated_by')->references('id')->on('users');
            $table->char('disk', 20)->default('local');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medias');
    }
}
