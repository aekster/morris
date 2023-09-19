<?php

declare(strict_types=1);

namespace Morris\Core\Actions\Auth;

use Exception;
use Illuminate\Routing\Router;
use Morris\Core\Exceptions\ResetTokenMismatchException;
use Morris\Core\Http\Responses\ApiResponse;
use Morris\Core\Rules\Rulesets\User\PasswordRuleset;
use Morris\Core\Rules\Rulesets\Common\EmailRuleset;
use Morris\Core\Rules\Rulesets\User\TokenRuleset;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResetPassword
{
    use AsController;

    /**
     * @throws ResetTokenMismatchException
     */
    public function handle(string $email, string $password, string $token): void
    {
        $status = Password::reset(
            [
                "email" => $email,
                "password" => $password,
                "token" => $token,
            ],
            function ($user, $password): void {
                $user->forceFill([
                    "password" => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new ResetTokenMismatchException();
        }
    }

    /**
     * @throws HttpException
     */
    public function asController(ActionRequest $request, ApiResponse $apiResponse): JsonResponse
    {
        try {
            $this->handle(
                $request->validated(["email"]),
                $request->validated(["password"]),
                $request->validated(["token"])
            );
        } catch (ResetTokenMismatchException $e) {
            // silence the exception and behave as if the reset succeeded
            report($e);
        }

        return $apiResponse
            ->addMessage(trans("auth.password.reset.success"))
            ->create();
    }

    public function rules(): array
    {
        return [
            "email" => ["required", new EmailRuleset()],
            "password" => [
                "required",
                new PasswordRuleset(request()->only("password_confirmation"))
            ],
            "token" => ["required", new TokenRuleSet()],
        ];
    }

    public static function routes(Router $router): void
    {
        $router
            ->post("api/password/reset", static::class)
            ->name("password.reset");
    }

    public function getControllerMiddleware(): array
    {
        return ["api", "throttle:api"];
    }
}
