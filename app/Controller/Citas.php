<?php

namespace App\Controller;

use App\Log;
use App\Response;
use App\Request;

class Citas
{
  public function get(int $id=0)
  {
    echo 'hola mudnom ' . $id;
  }

  public function add(Request $request)
  {
  }

  public function delete(int $id, Request $request)
  {
  }
  
  public function update(int $id, Request $request)
  {
  }
}