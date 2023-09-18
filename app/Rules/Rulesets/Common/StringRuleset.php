<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\Common;

use Morris\Core\Rules\Rulesets\FieldRuleset;

class StringRuleset extends FieldRuleSet
{
    public function rules(): array
    {
        return [
            "string",
            "max:255",
        ];
    }
}
