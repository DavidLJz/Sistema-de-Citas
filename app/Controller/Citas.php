<?php

namespace App\Controller;

use App\Log;
use App\Response;
use App\Request;
use App\Data\Query;
use Throwable;

class Citas
{
  public function get(int $id=0)
  {
  }

  public function add(Request $request)
  {
    $request->requireValues([
      'tramite','ubicacion', 'nombres', 'fecha_cita'
    ]);

    $data = $request->getParams([
      'tramite',
      'ubicacion',
      'nombres',
      'apellidos',
      'telefono',
      'email',
      'fecha_cita',
      'comentario'
    ]);

    try {
      $id = Query::addCita($data);
    }

    catch (Throwable $e) {
      Log::error('[Citas.add]: ' . $e->getMessage(), $data);
      Log::error($e);

      Response::failInternal('Error al guardar la cita');
    }

    $data['id'] = $id;

    Response::apiResponse('Cita guardada', $data, 201);
  }

  public function delete(int $id, Request $request)
  {
  }
  
  public function update(int $id, Request $request)
  {
  }
}