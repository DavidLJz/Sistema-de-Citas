<?php

namespace App\Controller;

use App\Data\Query;
use App\Log;
use App\Response;
use App\Request;
use App\AdminCall;
use App\Exceptions\HttpException;
use Throwable;

class Archivos
{
  public function get(int $id=0)
  {
  }

  public function add(Request $request)
  {
    $cita_id = $_POST['cita_id'];

    if ( empty($cita_id) || !is_numeric($cita_id) ) {
      Response::failBadRequest('No se ha especificado el id de la cita');
    }

    $files = $_FILES['docs'];

    if ( empty($_FILES['docs']) ) {
      Response::failBadRequest('No se ha enviado ningún archivo');
    }

    $tipos_doc = $_POST['tipos_doc'];

    if ( empty($tipos_doc) ) {
      Response::failBadRequest('No se ha enviado ningún tipo de documento');
    }

    if ( count($tipos_doc) != count($files['name']) ) {
      Response::failBadRequest('No se han enviado todos los tipos de documentos');
    }

    $mimes = AdminCall::getMime();
    $filepath = AdminCall::getDirArchivos();
    
    $archivos_array = [];

    foreach ($files['name'] as $n => $name) {
      $tmp_name = $files['tmp_name'][$n];
      $error = $files['error'][$n];
      $size = $files['size'][$n];
      $type = $files['type'][$n];

      if ( $error !== UPLOAD_ERR_OK ) {
        throw new HttpException('Error al subir archivo: ' . $error, 500, compact('name'), true);
      }

      if ( $size > 1000000 ) {
        throw new HttpException('El archivo es demasiado grande', 400, compact('name'), true);
      }

      if ( !empty($mimes) && !in_array($type, $mimes) ) {
        $pass = false;
        
        foreach ($mimes as $mime) {
          $mime = explode('*', $mime)[0];

          if ( strpos($type, $mime) !== false ) {
            $pass = true;
            break;
          }
        }

        if (!$pass) {
          throw new HttpException('El tipo de archivo no esta permitido', 400, compact('name','mimes'), true);
        }
      }

      if ( empty($tipos_doc[$n]) || !is_string($tipos_doc[$n]) ) {
        throw new HttpException('No se ha enviado el tipo de documento', 400, compact('name'), true);
      }

      $path = $filepath . $name;

      if ( !@move_uploaded_file($tmp_name, $path) ) {
        throw new HttpException('Error al subir archivo: ' . $name, 500, compact('name'), true);
      }

      $archivo_data = [
        'tipo_documento' => $tipos_doc[$n],
        'nombre' => $name,
        'size' => $size,
        'mime' => $type,
        'cita_id' => $cita_id,
      ];

      try {
        $archivo_data['id'] = Query::addArchivo($archivo_data);

        $archivos_array[] = $archivo_data;
      }

      catch (Throwable $e) {
        Log::error($e);

        $archivos_array[] = ['nombre' => $name, 'error' => true];
      }
    }

    Response::apiResponse('Archivos subidos', $archivos_array);
  }

  public function delete(int $id, Request $request)
  {
  }
  
  public function update(int $id, Request $request)
  {
  }
}