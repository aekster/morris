<?php

declare(strict_types=1);

namespace Morris\Core\Traits\Enum;

trait HasLabels
{
    public function label(): string
    {
        return trans("enum.{$this->className()}.{$this->value}");
    }
    
    protected static function className():string {
        return static::class;
    }
}
