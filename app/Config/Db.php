<?php

namespace App\Config;

use PDO;
use Envms\FluentPDO\Query;

class Db {
  protected static $query;

  protected static $db = [
    'host' => 'mysql', # localhost
    'database' => 'dbcitas',
    'username' => 'dbuser',
    'password' => 'dbpass'
  ];

  public static function getQuery() :Query
  {
    if ( empty(self::$query) ) {
      $pdo = new PDO(
        'mysql:host=' . self::$db['host'] . ';dbname=' . self::$db['database'],
        self::$db['username'],
        self::$db['password']
      );

      self::$query = new Query($pdo);
    }

    return self::$query;
  }
}