<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\User;

use Morris\Core\Rules\Rulesets\FieldRuleset;
use Illuminate\Validation\Rules\Password;

class PasswordRuleset extends FieldRuleset
{
    public function rules(): array
    {
        return [
            "confirmed",
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols(),
            "max:255",
        ];
    }
}
