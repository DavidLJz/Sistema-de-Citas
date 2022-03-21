<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class HttpException extends Exception
{
  protected $data;
  protected $public_data;

  public function __construct(string $message, int $code, array $data=[], bool $public_data=false, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);

    $this->data = $data;
    $this->public_data = $public_data;
  }

  public function __toString()
  {
    return __CLASS__ . ": [{$this->code}]: {$this->message}";
  }

  public function getData()
  {
    return $this->data;
  }

  public function isPublicData()
  {
    return $this->public_data;
  }
}