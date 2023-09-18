<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets\User;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Morris\Core\Enums\User\Role;

class ElectiveUserRoleRuleset extends UserRoleRuleset
{
    public function rules(): array
    {
        return [
            Rule::in(Role::getElectiveRoles()),
        ];
    }

    public function message(): string
    {
        return trans("validation.custom.elective_role", ["roles" => Role::getLabels(Role::getElectiveRoles())]);
    }
}
