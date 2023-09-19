<?php

namespace Morris\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResetTokenMismatchException extends HttpException
{
    protected $code = Response::HTTP_BAD_REQUEST;

    public function __construct()
    {
        parent::__construct($this->code, trans("auth.password.reset.failed"), null, [], $this->code);
    }
}
