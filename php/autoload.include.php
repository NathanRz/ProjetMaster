<?php

/** 
 * Include automatically a class/interface which hasn't been defined yet
 *
 * @param string $className class name to be included
 *
 * @return void
 */
spl_autoload_register(function ($className) {
  // file name pattern : classname.class.php
  $filename = strToLower($className) . '.class.php';

  if (file_exists($filename))
    require_once($filename);
  else if (file_exists("php/" . $filename))
    require_once("php/" . $filename);
});