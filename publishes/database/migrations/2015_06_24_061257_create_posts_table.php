<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('title')->nullable()->index();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image');
            $table->char('model', 80);
            $table->boolean('status')->default(true);
            $table->boolean('comment')->default(false);
            $table->text('extend')->nullable();
            $table->text('categories')->nullable();
            $table->bigInteger('parent')->nullable();
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('suppress_at')->nullable();
            $table->timestamps();
            $table->integer('created_by')->references('id')->on('users');
            $table->integer('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
