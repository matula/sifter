<?php

use Matula\Sifter\Checks\VowelRatioCheck;

it('flags low vowel ratio', function () {
    $check = new VowelRatioCheck();
    $result = $check->evaluate('bcdfg', ['min_ratio' => 0.2]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Vowel ratio below minimum');
});

it('passes when vowel ratio meets minimum', function () {
    $check = new VowelRatioCheck();
    $result = $check->evaluate('badge', ['min_ratio' => 0.2]);
    expect($result)->toBeNull();
});

