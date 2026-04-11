<?php

namespace Matula\Sifter\Checks;

class ConsecutiveConsonantsCheck implements CheckInterface
{
    public function name(): string
    {
        return 'consecutive_consonants';
    }

    public function evaluate(string $input, array $config): ?array
    {
        $max = (int) ($config['max_consecutive'] ?? 4);
        $pattern = '/[b-df-hj-np-tv-zB-DF-HJ-NP-TV-Z]{'.($max + 1).',}/';
        if ((bool) preg_match($pattern, $input)) {
            return [
                'message' => 'Too many consecutive consonants',
                'meta' => [
                    'max_consecutive' => $max,
                ],
            ];
        }
        return null;
    }
}

