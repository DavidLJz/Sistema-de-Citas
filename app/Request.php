<?php

namespace App;

use App\Response;

class Request
{
  protected $body = null;
  protected $method;

  function __construct(string $method)
  {
    $this->method = $method;

    if ( $method !== 'GET' ) {
      $this->body = @file_get_contents('php://input');
    
      if ( $this->givesJson() ) {
        $this->body = @json_decode($this->body, true);
      }
    }
  }

  public function givesJson() :bool
  {
    if ($this->method === 'GET') return false;

    $content_type = $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

    if (!$content_type) {
      $content_type = getallheaders()['Content-Type'] ?? '';
    }

    return $content_type === 'application/json';
  }

  public function getBody()
  {
    return $this->body;
  }
}