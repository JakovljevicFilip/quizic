<?php

namespace Quizic\Support;

class Difficulty
{
    public const EASY = 1;
    public const MODERATE = 2;
    public const HARD = 3;

    public static function label(int $value): string
    {
        switch ($value) {
            case self::EASY:
                return 'Easy';
            case self::MODERATE:
                return 'Moderate';
            case self::HARD:
                return 'Hard';
            default:
                return 'Unknown';
        }
    }

    public static function all(): array
    {
        return [
            ['id' => self::EASY, 'label' => 'Easy'],
            ['id' => self::MODERATE, 'label' => 'Moderate'],
            ['id' => self::HARD, 'label' => 'Hard'],
        ];
    }

    public static function fromLabel(string $label): ?int
    {
        switch (strtolower($label)) {
            case 'easy':
                return self::EASY;
            case 'moderate':
                return self::MODERATE;
            case 'hard':
                return self::HARD;
            default:
                return null;
        }
    }


    public static function getRandomDifficulty(): int
    {
        $all = self::all();
        $random = $all[array_rand($all)];

        return $random['id'];
    }
}
