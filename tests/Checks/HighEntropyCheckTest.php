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

