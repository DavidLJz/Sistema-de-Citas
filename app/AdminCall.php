<?php

namespace App;

use App\Config\Admin;

class AdminCall
{
  protected static $admin;
  
  public static function __callStatic(string $method, array $args=[])
  {
    if ( empty(self::$admin) ) {
      self::$admin = new Admin();
    }
    
    if ( method_exists(self::$admin, $method) ) {
      return self::$admin->$method(...$args);
    }
    
    return null;
  }
}