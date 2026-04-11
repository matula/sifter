<?php

namespace Matula\Sifter\Checks;

class RepetitiveCharsCheck implements CheckInterface
{
    public function name(): string
    {
        return 'repetitive_chars';
    }

    public function evaluate(string $input, array $config): ?array
    {
        $threshold = (int) ($config['max_repetitive'] ?? 2);
        $pattern = '/(.)\\1{'.$threshold.',}/i';

        if ((bool) preg_match($pattern, $input)) {
            return [
                'message' => 'Repetitive characters detected',
                'meta' => [
                    'max_repetitive' => $threshold,
                ],
            ];
        }

        return null;
    }
}
