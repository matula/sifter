<?php

use Matula\Sifter\Checks\ConsecutiveVowelsCheck;

it('flags too many consecutive vowels', function () {
    $check = new ConsecutiveVowelsCheck();
    $result = $check->evaluate('ae', ['max_consecutive' => 2]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Too many consecutive vowels');
});

it('passes when vowel runs are small', function () {
    $check = new ConsecutiveVowelsCheck();
    $result = $check->evaluate('a', ['max_consecutive' => 2]);
    expect($result)->toBeNull();
});

