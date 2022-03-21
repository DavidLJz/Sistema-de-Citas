<?php

namespace App;

class Response 
{
  public static function wantsJson() :bool
  {
    if ( !isset($_SERVER['HTTP_ACCEPT']) ) {
      return false;
    }

    return strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
  }

  public static function send(string $content, int $code = 200, array $headers=[], bool $exit=true):void
  {
    http_response_code($code);

    $headers = array_unique($headers);

    foreach ($headers as $header) {
      if ( !is_string($header) ) continue;
      header($header);
    }

    echo $content;

    if ($exit) exit;
  }

  public static function json(array $data, int $code = 200, array $headers=[], bool $exit=true) :void
  {
    $headers[] = 'Content-Type: application/json';
    $data = json_encode($data);

    self::send($data, $code, $headers, $exit);
  }

  public static function html(string $html, int $code = 200, array $headers=[], bool $exit=true) :void
  {
    $headers[] = 'Content-Type: text/html';

    self::send($html, $code, $headers, $exit);
  }

  public static function redirect(string $url, int $code = 302, array $headers=[], bool $exit=true) :void
  {
    $headers[] = 'Location: ' . $url;

    self::send('', $code, $headers, $exit);
  }

  public static function htmlError(string $message, int $code = 500, array $headers=[], bool $exit=true) :void
  {
    $headers[] = 'Content-Type: text/html';

    $html = '<h1>' . $message . '</h1>';

    self::send($html, $code, $headers, $exit);
  }

  public static function apiResponse(string $message, array $data=[], int $code=200, array $headers=[], bool $exit=true):void
  {
    $content = [
      'message' => $message,
      'data' => $data
    ];

    self::json($content, $code, $headers, $exit);
  }
  
  public static function failBadRequest(string $message, array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 400, $headers, $exit);
  }

  public static function failNotFound(string $message='Not found', array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 404, $headers, $exit);
  }

  public static function failInternal(string $message='Internal error', array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 500, $headers, $exit);
  }

  public static function failUnauthorized(string $message='Unauthorized', array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 401, $headers, $exit);
  }

  public static function failForbidden(string $message='Forbidden', array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 403, $headers, $exit);
  }

  public static function failMethodNotAllowed(string $message='Method not allowed', array $data=[], array $headers=[], bool $exit=true):void
  {
    self::apiResponse($message, $data, 405, $headers, $exit);
  }
}