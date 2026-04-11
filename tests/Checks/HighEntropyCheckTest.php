<?php

use Matula\Sifter\Checks\HighEntropyCheck;

it('flags strings with high uniqueness ratio', function () {
    $check = new HighEntropyCheck();
    $result = $check->evaluate('abcdef', ['max_ratio' => 0.8]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('High character uniqueness (entropy) detected');
});

it('passes for low uniqueness ratio', function () {
    $check = new HighEntropyCheck();
    $result = $check->evaluate('mississippi', ['max_ratio' => 1.0]);
    // uniqueness ratio is low; with a generous max it should pass
    expect($result)->toBeNull();
});

it('ignores very short strings', function () {
    $check = new HighEntropyCheck();
    $result = $check->evaluate('abc', ['max_ratio' => 0.8]);
    expect($result)->toBeNull();
});

it('respects configurable min_length', function () {
    $check = new HighEntropyCheck();
    // 'abcde' is 5 chars with ratio 1.0, but default min_length=6 skips it
    $result = $check->evaluate('abcde', ['max_ratio' => 0.8]);
    expect($result)->toBeNull();

    // Same string with min_length=4 should trigger
    $result = $check->evaluate('abcde', ['max_ratio' => 0.8, 'min_length' => 4]);
    expect($result)->toBeArray();
});
