<?php

use Matula\Sifter\Checks\ConsecutiveConsonantsCheck;

it('flags too many consecutive consonants', function () {
    $check = new ConsecutiveConsonantsCheck();
    $result = $check->evaluate('bcdfg', ['max_consecutive' => 4]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Too many consecutive consonants');
});

it('passes when consonant runs are at or below max', function () {
    $check = new ConsecutiveConsonantsCheck();
    $result = $check->evaluate('bcdf', ['max_consecutive' => 4]);
    expect($result)->toBeNull();
});

it('passes when consonant runs are small', function () {
    $check = new ConsecutiveConsonantsCheck();
    $result = $check->evaluate('bcd', ['max_consecutive' => 4]);
    expect($result)->toBeNull();
});
