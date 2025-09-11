<?php

namespace Matula\Sifter\Checks;

class NumericCharsCheck implements CheckInterface
{
    public function name(): string
    {
        return 'numeric_chars';
    }

    public function evaluate(string $input, array $config): ?array
    {
        if ((bool) preg_match('/\d/', $input)) {
            return [
                'message' => 'Contains numeric characters',
                'meta' => [],
            ];
        }
        return null;
    }
}

