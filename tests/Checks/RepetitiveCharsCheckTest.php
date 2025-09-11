<?php

use Matula\Sifter\Checks\RepetitiveCharsCheck;

it('flags repetitive characters beyond threshold', function () {
    $check = new RepetitiveCharsCheck();
    // max_repetitive=2 -> triggers on 4 same chars in a row
    $result = $check->evaluate('helloooo world', ['max_repetitive' => 2]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Repetitive characters detected');
});

it('passes when repetitions are below threshold', function () {
    $check = new RepetitiveCharsCheck();
    $result = $check->evaluate('hello world', ['max_repetitive' => 2]);
    expect($result)->toBeNull();
});

