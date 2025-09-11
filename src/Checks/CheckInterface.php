<?php

namespace Matula\Sifter\Checks;

interface CheckInterface
{
    /** Unique config key, e.g., 'vowel_ratio' */
    public function name(): string;

    /**
     * Return a result array when the check triggers, or null when passing.
     * Include a short message and optional meta values.
     *
     * @param string $input
     * @param array $config Per-check configuration from config('sifter.checks.<name>')
     * @return array{message:string,meta:array}|null
     */
    public function evaluate(string $input, array $config): ?array;
}

