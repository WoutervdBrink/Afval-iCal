<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Divisible implements ValidationRule
{
    private function __construct(private readonly int $by)
    {

    }

    public static function by(int $by): self
    {
        return new Divisible($by);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (intval($value) % $this->by !== 0) {
            $fail('validation.custom.divisible')->translate(['by' => $this->by]);
        }
    }
}
