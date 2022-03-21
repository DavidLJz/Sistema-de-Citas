<?php

namespace App\Controller;

use App\Log;
use App\Response;
use App\Request;
use App\Data\Query;
use Throwable;

class Tramites
{
  public function get()
  {
    $tramites = Query::getTramites();

    Response::apiResponse('tramites', $tramites);
  }

  public function add(Request $request)
  {
    $request->requireValues(['name']);

    $data = $request->getParams(['name']);

    try {
      Query::addTramite($data['name']);
    }

    catch (Throwable $e) {
      Log::error('[Tramites.add]: ' . $e->getMessage(), $data);
      Log::error($e);

      Response::failInternal('Error al guardar nombre de tramite');
    }

    Response::apiResponse('Nombre de tramite guardada', [], 201);
  }

  public function delete(string $name)
  {
    try {
      $success = Query::deleteTramite($name);
    }

    catch (Throwable $e) {
      Log::error('[Tramites.delete]: ' . $e->getMessage(), compact('name'));
      Log::error($e);

      Response::failInternal('Error al eliminar nombre de tramite');
    }

    Response::apiResponse('tramite eliminado', compact('success'));
  }
  
  public function update(string $name, Request $request)
  {
  }
}