<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\User;

use Morris\Core\Rules\Rulesets\Common\EmailRuleset;
use Morris\Core\Rules\Rulesets\FieldRuleset;

class UniqueEmailRuleset extends FieldRuleset
{
    public function rules(): array
    {
        return [
            new EmailRuleset(),
            "unique:users,email",
        ];
    }
}
