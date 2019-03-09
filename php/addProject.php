<?php

include_once("autoload.include.php");
require_once("mypdo.include.php");
require_once("utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $mod = Module::getModuleById($_POST['idMod']);
  //var_dump($_POST);
  $grp = GroupeProjet::getGroupePrjById($_POST['binom'],$mod->getId());

  //Si il n'y a pas déjà un projet déposé par ce groupe.
  if($grp->getIdProjet() == null){

    $nameEtu = "";
    $etudiants = $grp->getEtudiants();
    foreach ($etudiants as $e) {
      $nameEtu .= $e->getNom();
    }


    $srcName = "src_".$nameEtu.".zip";
    $rapName = "rapport_".$nameEtu.".pdf";
    $imgName = "images_".$nameEtu.".zip";
    $target_dirSrc = "docs/". $mod->getLibModule() . "/Projets/Sources/".$srcName;
    $target_dirRapport = "docs/". $mod->getLibModule() . "/Projets/Rapports/".$rapName;
    $target_dirImages = "docs/". $mod->getLibModule() . "/Projets/Images/".$imgName;
    $dir_uploadImg = "../docs/". $mod->getLibModule() . "/Projets/Images/";
    $dir_uploadSrc= "../docs/". $mod->getLibModule() . "/Projets/Sources/";
    $dir_uploadRap = "../docs/". $mod->getLibModule() . "/Projets/Rapports/";

    if(isset($_FILES['sources']['tmp_name']) && !empty($_FILES['sources']['tmp_name'])){
      $sources = $_FILES['sources']['tmp_name'];
      move_uploaded_file($sources, $dir_uploadSrc . $srcName);
      /*if(file_exists($dir_upload . $_FILES['sources']['name']))
        $zip->addFile($dir_upload . $_FILES['sources']['name'], "src.zip");*/

    }

    if(isset($_FILES['rapport']['tmp_name']) && !empty($_FILES['rapport']['tmp_name'])){
      $rapport = $_FILES['rapport']['tmp_name'];
      move_uploaded_file($rapport, $dir_uploadRap . $rapName);
      /*if(file_exists($dir_upload . $_FILES['rapport']['name']))
        $zip->addFile($dir_upload . $_FILES['rapport']['name'], $_FILES['rapport']['name']);*/

    }

    if(isset($_FILES['images']) &&  !empty($_FILES['images'])){
      $zip = new ZipArchive;
      $zip->open($dir_uploadImg . $imgName, ZipArchive::CREATE);

      $images = $_FILES['images'];
      for($i = 0; $i < count($images['tmp_name']); $i++) {
        move_uploaded_file($images['tmp_name'][$i], $dir_uploadImg . $images['name'][$i]);
        if(file_exists($dir_uploadImg . $images['name'][$i]))
          $zip->addFile($dir_uploadImg . $images['name'][$i], $images['name'][$i]);
      }

      $zip->close();

      if(isset($_FILES['images']) &&  !empty($_FILES['images'])){

        $images = $_FILES['images'];
        for($i = 0; $i < count($images['name']); $i++) {
          unlink($dir_uploadImg . $images['name'][$i]);
        }
      }
    }

    Projet::addProjet($target_dirSrc,$target_dirRapport,$target_dirImages, $_POST['binom']);
  }

}


header("Location: ../groupeProjet.php?id=".$_POST['idMod']);
