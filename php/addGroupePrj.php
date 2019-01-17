<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  for($i = 0; $i < count($_POST['nom']); $i++){
    $etu = Etudiant::getEtudiantByName($_POST['prenom'][$i],$_POST['nom'][$i]);
    if($etu != null){

    }else{
      $etu = Etudiant::addEtudiant($_POST['nom'][$i], $_POST['prenom'][$i]);
      var_dump($etu);
    }
  }

}
