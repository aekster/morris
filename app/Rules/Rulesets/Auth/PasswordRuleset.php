<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\Auth;

use Morris\Core\Rules\Rulesets\FieldRuleset;
use Illuminate\Validation\Rules\Password;

class PasswordRuleset extends FieldRuleset
{
    public function rules(): array
    {
        return [
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            "max:255",
        ];
    }
}
