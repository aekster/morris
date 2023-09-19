<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\User;


use Morris\Core\Rules\Rulesets\FieldRuleset;

class TokenRuleset extends FieldRuleSet
{
    public function rules(): array
    {
        return [
            "alpha_num",
            "max:255",
        ];
    }
}
