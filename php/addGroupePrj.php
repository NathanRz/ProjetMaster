<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $etu
  for($i = 0; $i < count($_POST['nom']); $i++){
    $etu = Etudiant::getEtudiantByName($_POST['prenom'][$i],$_POST['nom'][$i]);
    if($etu != null){

      //on verfie qu'aucun des étudiants n'a deja de groupe de projet pour ce module
      $res = GroupeProjet::getGroupePrjByModAndEtu($_POST['idMod'],$etu->getId());

      if($res != null){
        return;
      }

    }else{
      $etu = Etudiant::addEtudiant($_POST['nom'][$i], $_POST['prenom'][$i]);
    }
  }

}
