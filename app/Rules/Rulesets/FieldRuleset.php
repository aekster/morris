<?php

declare(strict_types=1);

namespace Morris\Core\Rules\Rulesets;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator as AbstractValidator;
use Illuminate\Validation\Validator;

abstract class FieldRuleset implements ValidationRule
{
    protected Validator $validator;

    public function __construct(
        protected array $data = [],
    ) {}

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $validatedField = [$attribute => $value];
        $data = empty($this->data) ? $validatedField : array_merge($this->data, $validatedField);

        $this->validator = AbstractValidator::make($data, [
            $this->dotAsArray($attribute) => collect($this->rules())->toArray(),
        ]);

        if (!$this->validator->passes()) {
            $fail($this->validator->errors()->first());
        }
    }

    abstract public function rules(): array;

    // we will be using dot notation only for arrays
    protected function dotAsArray(string $attribute): string
    {
        return str_replace(".", "\.", $attribute);
    }
}
