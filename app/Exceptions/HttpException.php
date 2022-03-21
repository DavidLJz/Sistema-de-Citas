<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
  public function __construct(string $message, int $code, \Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }

  public function __toString()
  {
    return __CLASS__ . ": [{$this->code}]: {$this->message}";
  }
}