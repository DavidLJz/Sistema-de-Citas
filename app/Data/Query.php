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

  public static function getTramites()
  {
    $results = Db::getQuery()->from('tramites')->fetchAll();

    if ( empty($results) ) {
      return [];
    }

    return array_column($results, 'nombre');
  }

  public static function addTramite(string $tramite)
  {
    Db::getQuery()->insertInto('tramites')->values(['nombre' => $tramite])->execute();
  }

  public static function deleteTramite(string $tramite)
  {
    return Db::getQuery()->deleteFrom('citas')->where('tramite', $tramite)->execute();
  }
}