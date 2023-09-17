<?php

declare(strict_types=1);

namespace Morris\Core\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    protected string $message = "";
    protected array $headers = [];
    protected bool $isValid = true;
    protected array $data = [];
    protected int $code = Response::HTTP_OK;

    public function __construct(
        protected MessageBag $errors,
    ) {}

    public function create(): JsonResponse
    {
        return new JsonResponse(
            [
                "data" => $this->getData(),
                "errors" => $this->getErrors(),
                "message" => $this->getMessage(),
            ],
            $this->getCode(),
            $this->getHeaders(),
        );
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getErrors(): array
    {
        if (empty($this->errors) || $this->errors->isEmpty()) {
            return [];
        }
        return $this->errors->getMessages();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function addData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function addMessage(string $message): self
    {
        if (!empty($this->message)) {
            $this->message .= " ";
        }
        $this->message .= $message;

        return $this;
    }

    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($headers);
        return $this;
    }

    public function addErrors(array $errors): self
    {
        $this->errors->merge($errors);
        $this->invalidate();
        return $this;
    }

    public function addError(string $key, string $message): self
    {
        $this->errors->add($key, $message);
        $this->invalidate();
        return $this;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function notFound(string $message = ""): self
    {
        $message = empty($message) ? trans("responses.no_endpoint") : $message;
        return $this->handleError($message, Response::HTTP_NOT_FOUND);
    }

    public function validationFail(string $message = ""): self
    {
        $message = empty($message) ? trans("responses.validation_fail") : $message;
        return $this->handleError($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function unauthorizedAccess(string $message = ""): self
    {
        $message = empty($message) ? trans("responses.unauthorized") : $message;
        return $this->handleError($message, Response::HTTP_UNAUTHORIZED);
    }

    public function forbiddenAccess(string $message = ""): self
    {
        $message = empty($message) ? trans("responses.forbidden") : $message;
        return $this->handleError($message, Response::HTTP_FORBIDDEN);
    }

    public function serverError(string $message = ""): self
    {
        $message = empty($message) ? trans("responses.server_error") : $message;
        return $this->handleError($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function hasErrors(): bool
    {
        return $this->errors->any();
    }

    protected function handleError(string $message, int $code): self
    {
        $this->invalidate();
        $this->addMessage($message);

        return $this->setCode($code);
    }

    protected function invalidate(): void
    {
        $this->isValid = false;
    }
}
