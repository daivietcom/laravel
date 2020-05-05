<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chargers', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('status', 2);
            $table->float('amount')->default(0);
            $table->text('extend')->nullable();
            $table->integer('user_id')->references('id')->on('users');
            $table->char('type', 20)->default('card');
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
        Schema::drop('chargers');
    }
}
