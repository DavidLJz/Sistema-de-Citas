<?php

namespace App;

class Log
{
  protected static $path = BASE_PATH . '/logs/';

  public static function log(string $message, string $level='ERROR') :bool
  {
    $message = '[' . date('Y-m-d H:i:s') . '] ' . $level . ' - ' . $message . PHP_EOL;

    if (!is_dir(self::$path)) {
      if ( !mkdir(self::$path, 0777, true) ) {
        error_log("Could not create log directory");
        error_log($message);
        
        return false;
      }
    }

    $log_path = self::$path . date('Y-m-d') . '.log';
    
    $i = 1;

    while (@filesize($log_path) > 1000000) {
      $log_path = self::$path . date('Y-m-d') . '_' . $i . '.log';
    }

    return @file_put_contents($log_path, $message, FILE_APPEND);
  }

  public static function error(string $error, array $data = []) :bool
  {
    $error = $error . ' - ' . json_encode($data);

    return self::log($error, 'ERROR');
  }

  public static function debug(string $msg, array $data = []) :bool
  {
    $msg = $msg . ' - ' . json_encode($data);

    return self::log($msg, 'DEBUG');
  }
}