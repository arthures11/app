<?php

namespace App\Exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Order not found.", $statusCode = 404)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
