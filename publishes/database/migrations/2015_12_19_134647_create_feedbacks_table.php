<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id', 21)->index();
            $table->string('title')->nullable();
            $table->text('content');
            $table->boolean('status')->default(false);
            $table->char('model', 80);
            $table->bigInteger('parent')->nullable();
            $table->text('author')->nullable();
            $table->integer('created_by')->nullable()->references('id')->on('users');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedbacks');
    }
}
