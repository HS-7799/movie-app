<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actor_movie', function (Blueprint $table) {
            $table->primary(['movie_id','actor_id']);
            $table->uuid('movie_id');
            $table->uuid('actor_id');

            $table->string('role');

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('actor_id')->references('id')->on('actors')->onDelete('cascade');


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
        Schema::dropIfExists('actor_movie');
    }
}
