<?php

use Matula\Sifter\Sifter;
use Matula\Sifter\Checks\ExcessiveCapitalsCheck;
use Matula\Sifter\Checks\VowelRatioCheck;
use Matula\Sifter\Checks\RepetitiveCharsCheck;
use Matula\Sifter\Checks\ConsecutiveConsonantsCheck;
use Matula\Sifter\Checks\ConsecutiveVowelsCheck;
use Matula\Sifter\Checks\NumericCharsCheck;
use Matula\Sifter\Checks\HighEntropyCheck;

function defaultConfig(): array {
    return [
        'enabled' => true,
        'checks' => [
            'excessive_capitals' => ['enabled' => true, 'max_count' => 3],
            'vowel_ratio' => ['enabled' => true, 'min_ratio' => 0.2],
            'consecutive_consonants' => ['enabled' => true, 'max_consecutive' => 4],
            'consecutive_vowels' => ['enabled' => true, 'max_consecutive' => 2],
            'repetitive_chars' => ['enabled' => true, 'max_repetitive' => 2],
            'numeric_chars' => ['enabled' => true],
            'high_entropy' => ['enabled' => true, 'max_ratio' => 0.8],
        ],
    ];
}

function defaultChecks(): array {
    return [
        new ExcessiveCapitalsCheck(),
        new VowelRatioCheck(),
        new RepetitiveCharsCheck(),
        new ConsecutiveConsonantsCheck(),
        new ConsecutiveVowelsCheck(),
        new NumericCharsCheck(),
        new HighEntropyCheck(),
    ];
}

it('returns true for spammy input via modular checks', function () {
    $sifter = new Sifter(defaultConfig(), defaultChecks());
    expect($sifter->isSpam('ABCD'))->toBeTrue(); // excessive capitals
});

it('returns false for benign input', function () {
    $sifter = new Sifter(defaultConfig(), defaultChecks());
    expect($sifter->isSpam('Abel'))
        ->toBeFalse();
});

it('analyzes and reports triggered checks', function () {
    $sifter = new Sifter(defaultConfig(), defaultChecks());
    $results = $sifter->analyze('ABCD');
    expect($results)->toBeArray()
        ->and($results)->not->toBeEmpty()
        ->and($results[0]['name'])->toBe('excessive_capitals')
        ->and($results[0]['message'])->toBeString();
});

