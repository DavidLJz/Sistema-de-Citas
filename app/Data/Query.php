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

  public static function getCita(int $id)
  {
    return Db::getQuery()->from('citas')->where('id', $id)->fetch();
  }

  public static function getCitas()
  {
    return Db::getQuery()->from('citas')->fetchAll();
  }

  public static function deleteCita(int $id)
  {
    return Db::getQuery()->deleteFrom('citas', $id)->execute();
  }
}