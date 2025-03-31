<?php

namespace App\Exception;

use Throwable;

class CartException extends \RuntimeException
{
    public function __construct(
        string $message = "An error occurred during cart operation",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
