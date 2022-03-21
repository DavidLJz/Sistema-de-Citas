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
    $cita = Db::getQuery()->from('citas')->where('id', $id)->fetch();

    return $cita;
  }

  public static function getCitas()
  {
    $cita = Db::getQuery()->from('citas')->fetchAll();

    return $cita;
  }
}