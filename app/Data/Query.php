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
    return Db::getQuery()->deleteFrom('tramites')->where('nombre', $tramite)->execute();
  }

  public static function getTiposDocumentos()
  {
    $results = Db::getQuery()->from('tipos_documento')->fetchAll();

    if ( empty($results) ) {
      return [];
    }

    return array_column($results, 'nombre');
  }

  public static function addTipoDocumento(string $nombre)
  {
    Db::getQuery()->insertInto('tipos_documento')->values(['nombre' => $nombre])->execute();
  }

  public static function deleteTipoDocumento(string $nombre)
  {
    return Db::getQuery()->deleteFrom('tipos_documento')->where('nombre', $nombre)->execute();
  }

  public static function getUbicaciones()
  {
    $results = Db::getQuery()->from('ubicaciones')->fetchAll();

    if ( empty($results) ) {
      return [];
    }

    return array_column($results, 'nombre');
  }

  public static function addUbicacion(string $nombre)
  {
    Db::getQuery()->insertInto('ubicaciones')->values(['nombre' => $nombre])->execute();
  }

  public static function deleteUbicacion(string $nombre)
  {
    return Db::getQuery()->deleteFrom('ubicaciones')->where('nombre', $nombre)->execute();
  }

  public static function addArchivo(array $data)
  {
    return Db::getQuery()->insertInto('archivos')->values($data)->execute();
  }
}