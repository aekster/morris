<?php

declare(strict_types=1);

namespace Morris\Core\Actions\Auth;

use Illuminate\Routing\Router;
use Morris\Core\Http\Responses\ApiResponse;
use Morris\Core\Models\User;
use Morris\Core\Rules\Rulesets\Auth\PasswordRuleset;
use Morris\Core\Rules\Rulesets\Auth\UniqueEmailRuleset;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Morris\Core\Rules\Rulesets\Common\StringRuleset;

class RegisterUser
{
    use AsController;

    /**
     * @throws QueryException
     */
    public function handle(
        string $email,
        string $password,
        string $name
    ): void
    {
        User::create([
            "email" => $email,
            "password" => Hash::make($password),
            "name" => $name
        ]);
    }

    public function asController(
        ActionRequest $request,
        ApiResponse $apiResponse
    ): JsonResponse {
        $this->handle(
            $request->validated(["email"]),
            $request->validated(["password"]),
            $request->validated(["name"])
        );

        return $apiResponse
            ->addMessage(trans("auth.user_registered"))
            ->create();
    }

    public function rules(): array
    {
        return [
            "email" => ["required", new UniqueEmailRuleset()],
            "password" => ["required", "confirmed", new PasswordRuleset()],
            "name" => ["required", new StringRuleset()]
        ];
    }

    public static function routes(Router $router): void
    {
        $router
            ->post("api/register", static::class)
            ->name("register");
    }

    public function getControllerMiddleware(): array
    {
        return ["api", "throttle:api"];
    }

}
