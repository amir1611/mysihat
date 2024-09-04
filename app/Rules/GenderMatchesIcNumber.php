<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class GenderMatchesIcNumber implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        $icNumber = request()->input('ic_number');
        $lastDigit = intval(substr($icNumber, -1));
        $expectedGender = $lastDigit % 2 === 0 ? 'Female' : 'Male';
        
        if ($value !== $expectedGender) {
            $fail('The gender does not match the IC number.');
        }
    }
}