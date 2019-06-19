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
        	QuestionsTableSeeder::class,
            AnswersTableSeeder::class,
            HintsTableSeeder::class,
        	HighscoresTableSeeder::class,
        ]);
    }
}
