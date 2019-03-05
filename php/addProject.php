<?php

include_once("autoload.include.php");
require_once("mypdo.include.php");
require_once("utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $mod = Module::getModuleById($_POST['idMod']);
  //var_dump($_POST);
  $grp = GroupeProjet::getGroupePrjById($_POST['binom']);

  //Si il n'y a pas déjà un projet déposé par ce groupe.
  if($grp->getIdProjet() == null){

    $zipName = "";
    $etudiants = $grp->getEtudiants();
    foreach ($etudiants as $e) {
      $zipName .= $e->getNom();
    }

    $target_dir = "docs/". $mod->getLibModule() . "/Projets/" . $zipName . ".zip";
    $dir_upload = "../docs/". $mod->getLibModule() . "/Projets/";
    $zip = new ZipArchive;
    $zip->open($dir_upload . $zipName . ".zip", ZipArchive::CREATE);

    if(isset($_FILES['sources']['tmp_name']) && !empty($_FILES['sources']['tmp_name'])){
      $sources = $_FILES['sources']['tmp_name'];
      move_uploaded_file($sources, $dir_upload . $_FILES['sources']['name']);
      if(file_exists($dir_upload . $_FILES['sources']['name']))
        $zip->addFile($dir_upload . $_FILES['sources']['name'], "src.zip");

    }

    if(isset($_FILES['rapport']['tmp_name']) && !empty($_FILES['rapport']['tmp_name'])){
      $rapport = $_FILES['rapport']['tmp_name'];
      move_uploaded_file($rapport, $dir_upload . $_FILES['rapport']['name']);
      if(file_exists($dir_upload . $_FILES['rapport']['name']))
        $zip->addFile($dir_upload . $_FILES['rapport']['name'], $_FILES['rapport']['name']);

    }

    if(isset($_FILES['images']) &&  !empty($_FILES['images'])){

      $images = $_FILES['images'];
      for($i = 0; $i < count($images['tmp_name']); $i++) {
        move_uploaded_file($images['tmp_name'][$i], $dir_upload . $images['name'][$i]);
        if(file_exists($dir_upload . $images['name'][$i]))
          $zip->addFile($dir_upload . $images['name'][$i], $images['name'][$i]);
      }

    }

    $zip->close();
    if(isset($_FILES['images']) &&  !empty($_FILES['images'])){

      $images = $_FILES['images'];
      for($i = 0; $i < count($images['name']); $i++) {
        unlink($dir_upload . $images['name'][$i]);
      }
    }
    unlink($dir_upload . $_FILES['rapport']['name']);
    unlink($dir_upload . $_FILES['sources']['name']);
    Projet::addProjet($target_dir, $_POST['binom']);
  }
}

header("Location: ../groupeProjet.php?id=".$_POST['idMod']);
