<?php

include_once("autoload.include.php");

if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    Groupe::removeGroup($_GET['id']);
  }
}

header('Location: ../editModule.php?id='. $_GET['idMod']);
