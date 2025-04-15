<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
        	DifficultiesTableSeeder::class,
            // Real questions and answers pairs.
            QuestionsAndAnswersSeeder::class,
            // Correct answers are labeled - useful for testing.
            //FakeQuestionsAndAnswersSeeder::class,
            HintsTableSeeder::class,
        	HighscoresTableSeeder::class,
        ]);
    }
}
