<?php
include_once("autoload.include.php");
if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    Etudiant::removeEtudiant($_GET['id']);
  }
}

header("Location: ../administration.php");
