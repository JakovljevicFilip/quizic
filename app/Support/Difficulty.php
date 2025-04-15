<?php

namespace Quizic\Support;

class Difficulty
{
    public const EASY = 1;
    public const MODERATE = 2;
    public const HARD = 3;

    public static function label(int $value): string
    {
        return match ($value) {
            self::EASY => 'Easy',
            self::MODERATE => 'Moderate',
            self::HARD => 'Hard',
            default => 'Unknown',
        };
    }

    public static function all(): array
    {
        return [
            self::EASY => 'Easy',
            self::MODERATE => 'Moderate',
            self::HARD => 'Hard',
        ];
    }

    public static function fromLabel(string $label): ?int
    {
        return match (strtolower($label)) {
            'easy' => self::EASY,
            'moderate' => self::MODERATE,
            'hard' => self::HARD,
            default => null,
        };
    }

    public static function getRandomDifficulty(): int
    {
        return self::all()[array_rand(self::all())];
    }
}
