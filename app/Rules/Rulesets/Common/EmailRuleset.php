<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\Common;

use Morris\Core\Rules\Rulesets\FieldRuleset;

class EmailRuleset extends FieldRuleset
{
    public function rules(): array
    {
        return [
            "email",
            "max:255",
        ];
    }
}
