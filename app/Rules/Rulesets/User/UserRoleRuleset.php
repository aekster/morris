<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\User;

use Illuminate\Validation\Rules\Enum;
use Morris\Core\Enums\User\Role;
use Morris\Core\Rules\Rulesets\FieldRuleset;

class UserRoleRuleset extends FieldRuleSet
{
    public function rules(): array
    {
        return [
            new Enum(Role::class),
        ];
    }
}
