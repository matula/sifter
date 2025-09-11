<?php

namespace Matula\Sifter\Checks;

class VowelRatioCheck implements CheckInterface
{
    public function name(): string
    {
        return 'vowel_ratio';
    }

    public function evaluate(string $input, array $config): ?array
    {
        $alphaChars = preg_replace('/[^a-zA-Z]/', '', $input);
        if ($alphaChars === null || $alphaChars === '') {
            return null; // Not spam if there are no letters
        }

        preg_match_all('/[aeiouAEIOU]/', $alphaChars, $matches);
        $vowelCount = count($matches[0]);
        $ratio = $vowelCount / strlen($alphaChars);

        $min = (float) ($config['min_ratio'] ?? 0.2);
        if ($ratio < $min) {
            return [
                'message' => 'Vowel ratio below minimum',
                'meta' => [
                    'ratio' => $ratio,
                    'min_ratio' => $min,
                    'letters' => strlen($alphaChars),
                ],
            ];
        }

        return null;
    }
}
