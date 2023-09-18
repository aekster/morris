<?php

declare(strict_types=1);

namespace Morris\Core\Traits\Enum;

use ReflectionClass;
use ReflectionException;

trait HasLabels
{
    public function label(): string
    {
        return trans("enums.{$this->getRoot()}.{$this->value}");
    }

    public static function getLabels(array $enums): array
    {
        $labels = [];

        foreach ($enums as $enum) {
            $labels[] = $enum->label();
        }

        return $labels;
    }

    public function getRoot(): string {
        $class = static::class;
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException) {
            return "";
        }

        $path = $reflection->getFileName();
        $pathInfo = pathinfo($path);
        $directory = $pathInfo["dirname"];
        $filenameWithoutExtension = $pathInfo["filename"];
        $path = "{$directory}/{$filenameWithoutExtension}";

        $enumPathRoot = "Enums/";
        $enumPosition = strpos($path, $enumPathRoot);

        if ($enumPosition === false) {
            return "";
        }

        $afterEnum = substr($path, $enumPosition + strlen($enumPathRoot));

        return strtolower(str_replace('/', '.', $afterEnum));
    }
}
