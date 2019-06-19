<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            // GAME IDENTIFICATION
            $table->string('hash')->unique();
            // GAME INFORMATIONS
            $table->unsignedInteger('score');
            $table->string('username');
            // CURRENT QUESTION
            $table->unsignedBigInteger('question_id');
            // PAST QUESTIONS
            $table->string('questions_passed');
            // UPDATED AT IS MORE IMPORTANT SINCE IT'S USED WITH QUESTION TIME
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
