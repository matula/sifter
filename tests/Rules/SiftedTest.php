<?php

use Illuminate\Container\Container;
use Matula\Sifter\Rules\Sifted;
use Matula\Sifter\Sifter;

class AbortException extends RuntimeException
{
    public function __construct(public int $statusCode)
    {
        parent::__construct('Abort '.$statusCode, $statusCode);
    }
}

if (!function_exists('app')) {
    function app($abstract = null, array $parameters = [])
    {
        $container = Container::getInstance();

        if ($abstract === null) {
            return $container;
        }

        return $container->make($abstract, $parameters);
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = '', array $headers = [])
    {
        $GLOBALS['__sifter_abort_code'] = $code;

        throw new AbortException($code);
    }
}

it('aborts with provided status code when spam is detected', function () {
    $GLOBALS['__sifter_abort_code'] = null;

    $container = new Container();
    Container::setInstance($container);

    $container->instance(Sifter::class, new class () {
        public function isSpam(mixed $value): bool
        {
            return true;
        }
    });

    expect(fn() => (new Sifted(404))->passes('content', 'SPAMMY'))
        ->toThrow(AbortException::class)
        ->and($GLOBALS['__sifter_abort_code'])
        ->toBe(404);

    Container::setInstance(null);
});
