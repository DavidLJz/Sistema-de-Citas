<?php

namespace App\Config;

use App\Config\Db;
use Exception;

class Admin
{
  protected $mime = null;
  protected $dir_archivos = './uploads/';
  protected $gapi_key = null;

  function __construct(string $env='prod')
  {
    $result = Db::getQuery()->from('app_config')->where('env', $env)->fetch();

    if ( !empty($result['tipos_mime']) ) {
      $this->mime = explode(',', $result['tipos_mime']);
    }

    if ( !empty($result) ) {
      $this->dir_archivos = $result['dir_archivos'];

      if ( stripos($this->dir_archivos, '/', -1) !== strlen($this->dir_archivos)-1 ) {
        $this->dir_archivos .= '/';
      }

      if ( !is_dir($this->dir_archivos) ) {
        if ( !@mkdir($this->dir_archivos, 0777, true) ) {
          throw new Exception('No se pudo crear el directorio de archivos');
        }
      }

      $this->gapi_key = $result['gapi_key'];
    }
  }

  public function getMime()
  {
    return $this->mime;
  }

  public function getDirArchivos()
  {
    return $this->dir_archivos;
  }

  public function getGapiKey()
  {
    return $this->gapi_key;
  }
}