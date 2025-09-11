<?php

use Matula\Sifter\Checks\ExcessiveCapitalsCheck;

it('flags too many uppercase letters', function () {
    $check = new ExcessiveCapitalsCheck();
    $result = $check->evaluate('ABCD', ['max_count' => 3]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Too many uppercase letters');
});

it('passes when uppercase letters are within limit', function () {
    $check = new ExcessiveCapitalsCheck();
    $result = $check->evaluate('Abc', ['max_count' => 3]);
    expect($result)->toBeNull();
});

