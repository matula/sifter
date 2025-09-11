<?php

use Matula\Sifter\Checks\NumericCharsCheck;

it('flags presence of numeric characters', function () {
    $check = new NumericCharsCheck();
    $result = $check->evaluate('john1', []);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Contains numeric characters');
});

it('passes when no digits are present', function () {
    $check = new NumericCharsCheck();
    $result = $check->evaluate('john', []);
    expect($result)->toBeNull();
});

