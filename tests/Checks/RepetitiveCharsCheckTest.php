<?php

use Matula\Sifter\Checks\RepetitiveCharsCheck;

it('flags repetitive characters beyond threshold', function () {
    $check = new RepetitiveCharsCheck();
    $result = $check->evaluate('helloooo world', ['max_repetitive' => 2]);
    expect($result)->toBeArray()
        ->and($result['message'])->toBe('Repetitive characters detected');
});

it('flags when repetitions exceed max by one', function () {
    $check = new RepetitiveCharsCheck();
    // 3 l's exceeds max_repetitive of 2
    $result = $check->evaluate('helllo world', ['max_repetitive' => 2]);
    expect($result)->toBeArray();
});

it('passes when repetitions are at or below threshold', function () {
    $check = new RepetitiveCharsCheck();
    $result = $check->evaluate('hello world', ['max_repetitive' => 2]);
    expect($result)->toBeNull();
});
