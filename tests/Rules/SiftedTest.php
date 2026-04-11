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

    expect(fn() => (new Sifted(404))->validate('content', 'SPAMMY', fn($m) => null))
        ->toThrow(AbortException::class)
        ->and($GLOBALS['__sifter_abort_code'])
        ->toBe(404);

    Container::setInstance(null);
});

it('calls fail closure when spam is detected without abort code', function () {
    $container = new Container();
    Container::setInstance($container);

    $container->instance(Sifter::class, new class () {
        public function isSpam(mixed $value): bool
        {
            return true;
        }
    });

    $failCalled = false;
    $failMessage = null;
    (new Sifted())->validate('content', 'SPAMMY', function ($msg) use (&$failCalled, &$failMessage) {
        $failCalled = true;
        $failMessage = $msg;
    });

    expect($failCalled)->toBeTrue()
        ->and($failMessage)->toBe('The :attribute is not a valid value.');

    Container::setInstance(null);
});

it('passes non-string values without calling fail', function () {
    $failCalled = false;
    (new Sifted())->validate('content', 123, function ($msg) use (&$failCalled) {
        $failCalled = true;
    });
    expect($failCalled)->toBeFalse();
});
