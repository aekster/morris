<?php

declare(strict_types=1);

namespace Morris\Core\Actions\Docs;

use Illuminate\Routing\Router;
use Lorisleiva\Actions\Concerns\AsController;
use Symfony\Component\Yaml\Yaml;

class ParseOpenApiFile
{
    use AsController;

    public function asController(string $file): array
    {
        $openApiSpecification = file_get_contents(storage_path("docs/api/{$file}"));
        return Yaml::parse($openApiSpecification);
    }

    public static function routes(Router $router): void
    {
        $router
            ->get("docs/{file}", static::class)
            ->name("docs.openapi.file");
    }
}
