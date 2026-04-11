<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main Switch
    |--------------------------------------------------------------------------
    |
    | This option can be used to easily turn the entire spam detection
    | functionality on or off for your entire application.
    |
    */
    'enabled' => env('SIFTER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Spam Detection Checks
    |--------------------------------------------------------------------------
    |
    | Below you can configure the specific checks that will be performed on
    | input strings. You can enable or disable each check and fine-tune
    | its parameters to match your application's needs.
    |
    */
    'checks' => [

        // Fails if the string has more than a certain number of capital letters.
        // Good for catching random strings like "AbcDEfgHi".
        'excessive_capitals' => [
            'enabled' => true,
            'max_count' => 3,
        ],

        // Fails if the ratio of vowels to total letters is too low.
        // Real names in English almost always have a reasonable number of vowels.
        'vowel_ratio' => [
            'enabled' => true,
            'min_ratio' => 0.2, // At least 20% of letters should be vowels
        ],

        // Fails if there are too many consecutive consonants.
        // Catches things like "DlaKsvRqiFA".
        'consecutive_consonants' => [
            'enabled' => true,
            'max_consecutive' => 4,
        ],

        // Fails if there are too many consecutive vowels.
        // Less common for spam, but can catch things like "Jooohnaaa".
        'consecutive_vowels' => [
            'enabled' => true,
            'max_consecutive' => 2,
        ],

        // Fails if any character is repeated too many times in a row.
        // E.g., "aaabbbccc"
        'repetitive_chars' => [
            'enabled' => true,
            'max_repetitive' => 2,
        ],

        // Fails if the string contains any numeric characters.
        // Generally, names should not contain numbers.
        'numeric_chars' => [
            'enabled' => true,
        ],

        // Fails if the ratio of unique characters is too high, indicating randomness.
        // "abcdef" has a ratio of 1.0. "mississippi" has a low ratio.
        // "DlaKsvRqiFA" has a very high ratio.
        'high_entropy' => [
            'enabled' => true,
            'max_ratio' => 0.8, // Max 80% unique characters for strings > 5 chars
            'min_length' => 6, // Minimum string length before entropy check applies
        ],
    ],
];
