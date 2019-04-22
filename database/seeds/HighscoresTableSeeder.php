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
        factory(App\Highscore::class, 10);
    }
}
