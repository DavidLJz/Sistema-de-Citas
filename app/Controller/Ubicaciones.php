<?php

namespace App\Controller;

use App\Log;
use App\Response;
use App\Request;
use App\Data\Query;
use Throwable;

class Ubicaciones
{
  public function get()
  {
    $Ubicaciones = Query::getUbicaciones();

    Response::apiResponse('Ubicaciones', $Ubicaciones);
  }

  public function add(Request $request)
  {
    $request->requireValues(['name']);

    $data = $request->getParams(['name']);

    try {
      Query::addUbicacion($data['name']);
    }

    catch (Throwable $e) {
      Log::error('[Ubicaciones.add]: ' . $e->getMessage(), $data);
      Log::error($e);

      Response::failInternal('Error al guardar nombre de ubicacion');
    }

    Response::apiResponse('Nombre de ubicacion guardada', [], 201);
  }

  public function delete(string $name)
  {
    try {
      $success = Query::deleteUbicacion($name);
    }

    catch (Throwable $e) {
      Log::error('[Ubicaciones.delete]: ' . $e->getMessage(), compact('name'));
      Log::error($e);

      Response::failInternal('Error al eliminar nombre de ubicacion');
    }

    Response::apiResponse('ubicacion eliminado', compact('success'));
  }
  
  public function update(string $name, Request $request)
  {
  }
}