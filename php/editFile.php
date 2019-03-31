<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
    var_dump($_POST);
    var_dump($_FILES);

    $fichier = Fichier::getFichierById($_POST['idFichier']);
    $q = array();
    $newPathFile="";
    $newPathImg="";
    $newPathSrc="";
    $pathCurrentFile = $fichier->getCheminFichier();
    $pathCurrentImg = $fichier->getCheminImg();
    $pathCurrentSrc = $fichier->getCheminSource();

    if(trim($_FILES['addedFile']['tmp_name']) !== ""){
      $q[] = "nomFichier = :nomFichier";
      $q[] = "cheminFichier = :cheminFichier";
      if(unlink("../" . $pathCurrentFile)){
        if(move_uploaded_file($_FILES["addedFile"]["tmp_name"], "../" . substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . $_FILES['addedFile']['name'])){
          $newPathFile = substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . $_FILES['addedFile']['name'];
        }
      }
    }

    if(trim($_FILES['imgDesc']['tmp_name']) !== ""){
      $q[] = "cheminImg = :cheminImg";
      $nameFile="";
      $extImage = substr($_FILES["imgDesc"]["name"],strpos($_FILES["imgDesc"]["name"],"."));

      if(trim($_FILES['addedFile']['tmp_name']) !== ""){
        $nameTmp = substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . substr($_FILES['addedFile']['name'],strrpos($_FILES['addedFile']['name'],"/")) ;
        $nameFile = substr($nameTmp,0,strrpos($nameTmp,"."));
      } else{
        $nameTmp = substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . substr($pathCurrentFile, strrpos($pathCurrentFile,"/")+1);
        $nameFile = substr($nameTmp,0,strrpos($nameTmp,"."));
      }

      $nameImg = $nameFile . "_Image" . $extImage;

      if($pathCurrentImg != ""){
        if(unlink("../" . $pathCurrentImg)){
          if(move_uploaded_file($_FILES["imgDesc"]["tmp_name"], "../" . $nameImg))
            $newPathImg = $nameImg;
        }
      } else{
        if(move_uploaded_file($_FILES["imgDesc"]["tmp_name"], "../" . $nameImg))
          $newPathImg = $nameImg;
      }
    } else{
      $q[] = "cheminImg = :cheminImg";
      $newPathImg="";
    }

    if(array_key_exists('srcFile', $_FILES) && trim($_FILES['srcFile']['tmp_name']) !== ""){
        $q[] = "cheminSource = :cheminSource";

        if(trim($_FILES['addedFile']['tmp_name']) !== ""){
          $nameTmp = substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . substr($_FILES['addedFile']['name'],strrpos($_FILES['addedFile']['name'],"/")) ;
          $nameFile = substr($nameTmp,0,strrpos($nameTmp,"."));
        } else{
          $nameTmp = substr($pathCurrentFile,0,strrpos($pathCurrentFile,"/")+1) . substr($pathCurrentFile, strrpos($pathCurrentFile,"/")+1);
          $nameFile = substr($nameTmp,0,strrpos($nameTmp,"."));
        }

          $nameSrc = $nameFile . "_Sources.zip";
          var_dump($nameSrc);

          if($pathCurrentSrc != ""){
            if(unlink("../" . $pathCurrentSrc)){
              if(move_uploaded_file($_FILES["srcFile"]["tmp_name"], "../" . $nameSrc))
                $newPathSrc = $nameSrc;
            }
          } else{
            if(move_uploaded_file($_FILES["srcFile"]["tmp_name"], "../" . $nameSrc))
              $newPathSrc = $nameSrc;
          }

    }

    $comma = "";
    if(sizeof($q)>0)
      $comma= ",";

    $query = "UPDATE fichier SET descFichier = :descFichier" . $comma . implode(", ", $q) . " WHERE idFichier = :idFichier";
    $stmt = myPDO::getInstance()->prepare($query);
    $stmt->bindParam(":idFichier", $_POST["idFichier"]);
    $stmt->bindParam(":descFichier", $_POST["descFile"]);
    $stmt->bindParam(":cheminImg", $newPathImg);
    if(trim($_FILES['addedFile']['tmp_name']) !== ""){
      $stmt->bindParam(":nomFichier", $_FILES["addedFile"]["name"]);
      $stmt->bindParam(":cheminFichier", $newPathFile);
    }
    if(array_key_exists('srcFile', $_FILES) && trim($_FILES['srcFile']['tmp_name']) !== ""){
      $stmt->bindParam(":cheminSource", $newPathSrc);
    }
    $stmt->execute();

  }

	header('Location: ../module.php?id='. $_POST['idModule']);
}
