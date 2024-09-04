<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GenderMatchesIcNumber implements Rule
{
    public function passes($attribute, $value)
    {
        $icNumber = request()->input('ic_number');
        $lastDigit = intval(substr($icNumber, -1));
        $expectedGender = $lastDigit % 2 === 0 ? 'Female' : 'Male';
        return $value === $expectedGender;
    }

    public function message()
    {
        return 'The gender does not match the IC number.';
    }
}