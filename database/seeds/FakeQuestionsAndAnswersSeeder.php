<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FakeQuestionsAndAnswersSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('answers')->truncate();
        DB::table('questions')->truncate();

        $now = now();

        $counts = [
            1 => 10,
            2 => 20,
            3 => 40,
        ];

        $faker = \Faker\Factory::create();

        foreach ($counts as $difficulty => $count) {
            for ($i = 0; $i < $count; $i++) {
                $questionText = Str::ucfirst($faker->sentence(rand(4, 8)));

                $questionId = DB::table('questions')->insertGetId([
                    'text' => $questionText,
                    'difficulty_id' => $difficulty,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $answers = collect([
                    ['text' => 'Correct answer', 'status' => true],
                    ['text' => 'Incorrect answer', 'status' => false],
                    ['text' => 'Incorrect answer', 'status' => false],
                    ['text' => 'Incorrect answer', 'status' => false],
                ])->shuffle()->map(function ($answer) use ($questionId, $now) {
                    return [
                        'question_id' => $questionId,
                        'text' => $answer['text'],
                        'status' => $answer['status'],
                    ];
                })->toArray();

                DB::table('answers')->insert($answers);
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
