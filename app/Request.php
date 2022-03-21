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

  public function getParams(array $only=[]) :array
  {
    if ( !empty($this->body) && $this->givesJson() ) {
      $params = $this->body;
    } elseif ( $this->method === 'GET' ) {
      $params = $_GET;
    } elseif ( $this->method === 'POST' ){
      $params = $_POST;
    }

    if ( empty($params) ) {
      return [];
    }

    if ( !empty($only) ) {
      $params = array_intersect_key($params, array_flip($only));
    }

    return $params;
  }

  public function requireValues(array $keys)
  {
    $params = $this->getParams();

    if ( empty($params) ) {
      Response::failBadRequest('Params not found', $keys);
    }

    foreach ($keys as $k) {
      if ( !isset($params[$k]) ) {
        Response::failBadRequest('Param not found', [$k]);
      }
    }
  }
}