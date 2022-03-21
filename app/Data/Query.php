<?php

namespace App\Data;

use App\Config\Db;

class Query
{
  public static function addCita(array $data)
  {
    $id = Db::getQuery()->insertInto('citas')->values($data)->execute();

    return $id;
  }
}