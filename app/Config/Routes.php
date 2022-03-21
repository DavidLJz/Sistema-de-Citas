<?php

namespace App\Config;

use App\Controller\Archivos;
use App\Controller\Citas;
use App\Exceptions\HttpException;
use App\Request;
use Exception;

class Routes
{
  public const LIST = [
    'citas/{int}' => [
      'GET' => [Citas::class,'get'],
      'DELETE' => [Citas::class, 'delete'],
      'PATCH' => [Citas::class, 'update'],
    ],

    'citas' => [
      'GET' => [Citas::class, 'get'],
      'POST' => [Citas::class, 'add'],
    ],

    'archivos' => [
      'GET' => [Archivos::class, 'get'],
      'POST' => [Archivos::class, 'add'],
    ],

    'archivos/{int}' => [
      'GET' => [Archivos::class, 'get'],
      'PATCH' => [Archivos::class, 'update'],
      'DELETE' => [Archivos::class, 'delete'],
    ],
  ];

  public static function findRoute(string $route, string $method, array $uri_params=[]) :?callable
  {
    foreach (self::LIST as $route_name => $methods) {
      if ( $route === $route_name ) {
        if ( empty($methods[ $method ]) ) {
          throw new HttpException('Method not allowed', 405);
        }

        $func = $methods[ $method ];
        
      } else {
        if ( strpos($route, explode('/', $route_name)[0]) !== 0 ) {
          continue;
        }

        preg_match_all('/{([^{}]+)}/u', $route_name, $xmatches);
        $preset_params = $xmatches[1];

        if ( count($preset_params) !== count($uri_params) ) {
          continue;
        }

        foreach ($preset_params as $n => $preset) {
          $param = $uri_params[$n];

          if ( $preset === 'int' && !is_numeric($param) ) {
            continue 2;
          }
        }

        $func = $methods[ $method ];
      }

      if ( !is_callable($func) ) {
        throw new Exception('Invalid route');
      }

      return $func;
    }

    return null;
  }

  public static function callRoute(string $method, string $uri) :void
  {
    $uri = urldecode($uri);

    $route = explode('?', $uri)[0];
    $route = trim($route, " \t\n\r\0\x0B\\/");

    $self = preg_replace('/\/(?:index.php)?/', '', $_SERVER['PHP_SELF']);

    if ( $self !== '/' && $self ) {
      $route = preg_replace('/^' . preg_quote($self, '/') . '/', '', $route);
      $route = trim($route, " \t\n\r\0\x0B\\/");
    }

    $uri_params = explode('/', $route);
    unset($uri_params[0]);
    $uri_params = array_values($uri_params);

    $func = self::findRoute($route, $method, $uri_params);

    if ( empty($func) ) {
      throw new HttpException('Not found', 404);
    }

    $params = $uri_params ?? [];

    if ( $method !== 'GET' ) {
      $params[] = new Request($method);
    }

    call_user_func_array($func, $params);
  }
}