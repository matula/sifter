<?php

namespace Matula\Sifter\Rules;

use Illuminate\Contracts\Validation\Rule;
use Matula\Sifter\Sifter;

class Sifted implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // We only validate strings. Other types pass automatically.
        if (!is_string($value)) {
            return true;
        }

        $detector = app(Sifter::class);

        // The rule passes if the detector returns false (i.e., it is NOT spam).
        return !$detector->isSpam($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute does not pass validation.';
    }
}
