<?php

namespace Matula\Sifter\Checks;

class HighEntropyCheck implements CheckInterface
{
    public function name(): string
    {
        return 'high_entropy';
    }

    public function evaluate(string $input, array $config): ?array
    {
        $length = strlen($input);
        if ($length < 6) {
            return null; // Entropy not meaningful for very short strings
        }
        $uniqueChars = count(array_unique(str_split($input)));
        $ratio = $uniqueChars / $length;
        $max = (float) ($config['max_ratio'] ?? 0.8);

        if ($ratio > $max) {
            return [
                'message' => 'High character uniqueness (entropy) detected',
                'meta' => [
                    'ratio' => $ratio,
                    'max_ratio' => $max,
                    'length' => $length,
                    'unique' => $uniqueChars,
                ],
            ];
        }
        return null;
    }
}

