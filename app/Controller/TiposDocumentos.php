<?php

namespace App\Controller;

use App\Log;
use App\Response;
use App\Request;
use App\Data\Query;
use Throwable;

class TiposDocumentos
{
  public function get()
  {
    $TiposDocumentos = Query::getTiposDocumentos();

    Response::apiResponse('TiposDocumentos', $TiposDocumentos);
  }

  public function add(Request $request)
  {
    $request->requireValues(['name']);

    $data = $request->getParams(['name']);

    try {
      Query::addTipoDocumento($data['name']);
    }

    catch (Throwable $e) {
      Log::error('[TiposDocumentos.add]: ' . $e->getMessage(), $data);
      Log::error($e);

      Response::failInternal('Error al guardar nombre de tipo documento');
    }

    Response::apiResponse('Nombre de tipo documento guardada', [], 201);
  }

  public function delete(string $name)
  {
    try {
      $success = Query::deleteTipoDocumento($name);
    }

    catch (Throwable $e) {
      Log::error('[TiposDocumentos.delete]: ' . $e->getMessage(), compact('name'));
      Log::error($e);

      Response::failInternal('Error al eliminar nombre de tipo documento');
    }

    Response::apiResponse('tipo documento eliminado', compact('success'));
  }
  
  public function update(string $name, Request $request)
  {
  }
}