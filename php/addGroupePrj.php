<?php

include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $etu = array();
  for($i = 0; $i < count($_POST['nom']); $i++){
    $e = Etudiant::getEtudiantByName($_POST['prenom'][$i],$_POST['nom'][$i]);// PROBLEME : SI DEUX ETUDIANTS ONT LE MEME NOM & PRENOM ?
    //Si l'étudiant existe déjà
    if($e != null){
      //on verfie qu'aucun des étudiants n'a deja de groupe de projet pour ce module
      $res = GroupeProjet::getGroupePrjByModAndEtu($_POST['idMod'],$e->getId());
      //var_dump($res);
      //si il appartient deja a un groupe pour ce module STOP
      if($res != null){
        exit("Impossible d'ajouter un nouveau groupe: un des membres du groupe fait déjà parti d'un groupe de projet pour ce module.");
      //sinon on l'ajoute au tableau d'étudiants
      }else{
        array_push($etu, $e);
      }
    //sinon on l'insert dans la table étudiant et on l'ajoute au tableau
    }else{
      array_push($etu,Etudiant::addEtudiant($_POST['nom'][$i], $_POST['prenom'][$i], $_POST['grpTD'][$i], $_POST['grpTP'][$i]));
    }
  }

  //Si tout est ok on créé un nouveau groupe de projet
  if($etu != null){
    GroupeProjet::addGroupePrj($_POST['idMod'],$etu);
  }


}

//header('Location: index.php');
