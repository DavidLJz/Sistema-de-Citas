<?php

require './vendor/autoload.php';

define('APP_PATH', __DIR__ . '/app');
define('BASE_PATH', __DIR__);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

use App\Log;
use App\Response;
use App\Config\Routes;
use App\Exceptions\HttpException;

try {
  Routes::callRoute($method, $uri);
}

catch (\Throwable $e) {
  if ( $e instanceof HttpException ) {
    Log::error($e, $e->getData());

    if (Response::wantsJson()) {
      $data = $e->isPublicData() ? $e->getData() : [];
      Response::apiResponse($e->getMessage(), $data, $e->getCode());
    } else {
      Response::htmlError($e->getMessage(), $e->getCode());
    }

    exit;
  }

  Log::error($e, compact('method', 'uri'));

  if (Response::wantsJson()) {
    Response::failInternal('Internal server error');
  } else {
    Response::htmlError('Internal server error');
  }
}