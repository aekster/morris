<?php

declare(strict_types=1);

namespace Morris\Core\Actions\Auth;

use Illuminate\Routing\Router;
use Morris\Core\Enums\User\Role;
use Morris\Core\Http\Responses\ApiResponse;
use Morris\Core\Models\User;
use Morris\Core\Rules\Rulesets\User\ElectiveUserRoleRuleset;
use Morris\Core\Rules\Rulesets\User\PasswordRuleset;
use Morris\Core\Rules\Rulesets\User\UniqueEmailRuleset;
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
        string $name,
        Role $role
    ): void
    {
        User::create([
            "email" => $email,
            "password" => Hash::make($password),
            "name" => $name,
            "role" => $role
        ]);
    }

    public function asController(
        ActionRequest $request,
        ApiResponse $apiResponse
    ): JsonResponse {
        $this->handle(
            $request->validated(["email"]),
            $request->validated(["password"]),
            $request->validated(["name"]),
            $request->validated(["role"])
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
            "name" => ["required", new StringRuleset()],
            "role" => [new ElectiveUserRoleRuleset()]
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
