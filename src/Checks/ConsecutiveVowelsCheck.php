<?php

namespace Matula\Sifter\Checks;

class ConsecutiveVowelsCheck implements CheckInterface
{
    public function name(): string
    {
        return 'consecutive_vowels';
    }

    public function evaluate(string $input, array $config): ?array
    {
        $max = (int) ($config['max_consecutive'] ?? 2);
        $pattern = '/[aeiouAEIOU]{'.$max.',}/';
        if ((bool) preg_match($pattern, $input)) {
            return [
                'message' => 'Too many consecutive vowels',
                'meta' => [
                    'max_consecutive' => $max,
                ],
            ];
        }
        return null;
    }
}

