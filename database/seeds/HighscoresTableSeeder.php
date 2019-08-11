<?php

use Illuminate\Database\Seeder;

class HighscoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Quizic\Highscore::class, 10)->create();
    }
}
