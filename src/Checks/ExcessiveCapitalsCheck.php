<?php

namespace Matula\Sifter\Checks;

class ExcessiveCapitalsCheck implements CheckInterface
{
    public function name(): string
    {
        return 'excessive_capitals';
    }

    public function evaluate(string $input, array $config): ?array
    {
        preg_match_all('/[A-Z]/', $input, $matches);
        $count = count($matches[0]);
        $max = (int) ($config['max_count'] ?? 3);

        if ($count > $max) {
            return [
                'message' => 'Too many uppercase letters',
                'meta' => [
                    'uppercase' => $count,
                    'max_count' => $max,
                ],
            ];
        }

        return null;
    }
}

