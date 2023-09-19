<?php

declare(strict_types=1);

namespace Morris\Core\Actions\Auth;

use Exception;
use Illuminate\Routing\Router;
use Morris\Core\Enums\Frontend;
use Morris\Core\Http\Responses\ApiResponse;
use Morris\Core\Rules\Rulesets\Common\EmailRuleset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class SendPasswordResetEmail
{
    use AsController;

    public function handle(string $email, string $formUrl): void
    {
        ResetPassword::toMailUsing(function ($user, $token) use ($formUrl) {
            $url = $formUrl . "?token=" . $token . "&email=" . $user->email;
            $expireTime = config("auth.passwords." . config("auth.defaults.passwords") . ".expire");
            return (new MailMessage())
                ->subject(trans("email.reset.subject"))
                ->greeting(trans("email.reset.greeting"))
                ->line(trans("email.reset.reason"))
                ->action(Lang::get("email.reset.action"), $url)
                ->line(trans("email.reset.expire", ["count" => $expireTime]))
                ->line(trans("email.reset.ignore"));
        });

        Password::sendResetLink(["email" => $email]);
    }

    public function asController(ActionRequest $request, ApiResponse $apiResponse): JsonResponse
    {
        $formUrl = config("morris.frontends.{$request->validated("frontend")}.password_reset");

        try {
            $this->handle($request->validated("email"), $formUrl);
        } catch (Exception $e) {
            report($e);
        }

        return $apiResponse
            ->addMessage(trans("auth.password.reset.request"))
            ->create();
    }

    public function rules(): array
    {
        return [
            "email" => ["required", new EmailRuleset()],
            "frontend" => ["required", new Enum(Frontend::class)],
        ];
    }

    public static function routes(Router $router): void
    {
        $router
            ->post("api/password/reset/request", static::class)
            ->name("password.reset.request");
    }

    public function getControllerMiddleware(): array
    {
        return ["api", "throttle:api"];
    }
}
