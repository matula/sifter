<?php

namespace Matula\Sifter;

use Matula\Sifter\Checks\CheckInterface;

class Sifter
{
    protected array $config;
    /** @var iterable<CheckInterface> */
    protected iterable $checks;

    public function __construct(array $config, iterable $checks = [])
    {
        $this->config = $config;
        $this->checks = $checks;
    }

    /**
     * Run all enabled checks against the input string.
     *
     * @param string $input
     * @return bool Returns true if it IS spam, false otherwise.
     */
    public function isSpam(string $input): bool
    {
        if (!($this->config['enabled'] ?? true)) {
            return false;
        }

        // Run modular checks (extensible via DI)
        foreach ($this->checks as $check) {
            $name = $check->name();
            if (!($this->config['checks'][$name]['enabled'] ?? false)) {
                continue;
            }
            $result = $check->evaluate($input, $this->config['checks'][$name] ?? []);
            if ($result !== null) {
                return true; // short-circuit on first trigger
            }
        }

        return false;
    }

    /**
     * Analyze the input and return the list of triggered checks with context.
     * Currently reports modular checks only.
     *
     * @param string $input
     * @return array<int, array{name:string,message:string,meta:array}>
     */
    public function analyze(string $input): array
    {
        $results = [];
        if (!($this->config['enabled'] ?? true)) {
            return $results;
        }

        foreach ($this->checks as $check) {
            $name = $check->name();
            if (!($this->config['checks'][$name]['enabled'] ?? false)) {
                continue;
            }
            $result = $check->evaluate($input, $this->config['checks'][$name] ?? []);
            if ($result !== null) {
                $results[] = $result + ['name' => $name];
            }
        }

        return $results;
    }

}
