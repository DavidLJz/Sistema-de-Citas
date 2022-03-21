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
    $include_files = $_GET['files'] ?? false;

    if ( $id ) {
      $cita = Query::getCita($id);

      if ( empty($cita) ) {
        return Response::failNotFound();
      }

      if ( $include_files ) {
        $cita['archivos'] = Query::getCitaArchivos($id);
      }

      Response::apiResponse('cita', $cita);
    }

    $citas = Query::getCitas();

    if ( $include_files ) {
      foreach ($citas as &$cita) {
        $cita['archivos'] = Query::getCitaArchivos($cita['id']);
      }
    }

    Response::apiResponse('citas', $citas);
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
    try {
      $result = Query::deleteCita($id);
    }

    catch (Throwable $e) {
      Log::error('[Citas.delete]: ' . $e->getMessage(), compact('id'));
      Log::error($e);

      Response::failInternal('Error al eliminar la cita');
    }

    Response::apiResponse('Cita eliminada');
  }
  
  public function update(int $id, Request $request)
  {
  }
}