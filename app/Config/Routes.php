<?php

namespace App\Config;

use App\Controller\Archivos;
use App\Controller\Citas;

class Routes
{
  public const LIST = [
    'GET' => [
      'citas' => [Citas::class, 'get'],
      'archivos' => [Archivos::class, 'get']
    ],

    'POST' => [
      'citas' => [Citas::class, 'add'],
      'archivos' => [Archivos::class, 'add'],
    ],

    'PATCH' => [
      'citas' => [Citas::class, 'update'],
      'archivos' => [Archivos::class, 'update'],
    ],

    'DELETE' => [
      'citas' => [Citas::class, 'delete'],
      'archivos' => [Archivos::class, 'delete'],
    ],
  ];

  public static function callRoute(string $method, string $uri) :void
  {
    $uri = urldecode($uri);

    $route = explode('?', $uri)[0];

    // remove trailing slash
    $route = trim($route, " \t\n\r\0\x0B\\/");

    $query = explode('&', explode('?', $uri)[1]);

    exit(var_dump($query));

    if ( empty(self::LIST[$method][$route]) ) {
      throw new \Exception("Route not found");
    }
    
    call_user_func(self::LIST[$method][$route], $query);
  }
}