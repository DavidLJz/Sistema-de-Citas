<?php

namespace App\Config;

use App\Config\Db;

class Admin
{
  protected $mime = null;
  protected $dir_archivos = './archivos';
  protected $gapi_key = null;

  function __construct(string $env='prod')
  {
    $result = Db::getQuery()->from('app_config')->where('env', $env)->fetch();

    if ( !empty($result['tipos_mime']) ) {
      $this->mime = explode(',', $result['tipos_mime']);
    }

    if ( !empty($result) ) {
      $this->dir_archivos = $result['dir_archivos'];
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