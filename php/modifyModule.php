<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

$uploadOk = true;
if(Admin::isConnected()){
	if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_FILES['img']) && !empty($_FILES['img'])){
      $target_dir = "../img/modImg/";
      $dir = "img/modImg/" . basename($_FILES["img"]["name"]);
      $name = basename($_FILES["img"]["name"]);
      $target_file = $target_dir . basename($_FILES["img"]["name"]);

      $check = getimagesize($_FILES["img"]["tmp_name"]);

      if($check == false)
        $uploadOk = false;

      if($uploadOk && file_exists($target_file))
        $uploadOk = false;

      if($uploadOk && $_FILES["img"]["size"] > 5000000)
        $uploadOk = false;

      $mod = Module::getModuleById($_POST['idMod']);

        if($mod->getImgMod() != null){
          if(unlink($mod->getImgMod())){
            if(move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)){
              $stmt = myPDO::getInstance()->prepare(<<<SQL
                UPDATE module SET imgMod = :img
                WHERE idModule = :id
SQL
        );

              $stmt->execute(array(':img' => secureInput($dir),
                                   ':id' => secureInput($_POST['idMod'])));
            }else{
              //erreur move_uploaded_file
            }
          }else{
            //erreur unlink
          }
        }else{
          if(move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)){
            $stmt = myPDO::getInstance()->prepare(<<<SQL
              UPDATE module SET imgMod = :img
              WHERE idModule = :id
SQL
      );

            $stmt->execute(array(':img' => secureInput($dir),
                                 ':id' => secureInput($_POST['idMod'])));
          }else{
            //erreur move_uploaded_file
          }
        }
    }

    if(isset($_POST['passMod']) && !empty($_POST['passMod'])){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        UPDATE module SET passModule = :pass
        WHERE idModule = :id
SQL
    );
      $stmt->execute(array(':pass' => secureInput($_POST['passMod']),
                           ':id'   => secureInput($_POST['idMod'])));
    }

    if(isset($_POST['startDate']) && !empty($_POST['startDate'])){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        UPDATE module SET startDate = :date
        WHERE idModule = :id
SQL
    );
      $stmt->execute(array(':date' => secureInput($_POST['startDate']),
                           ':id'   => secureInput($_POST['idMod'])));

    }

    if(isset($_POST['endDate']) && !empty($_POST['endDate'])){
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        UPDATE module SET endDate = :date
        WHERE idModule = :id
SQL
    );
      $stmt->execute(array(':date' => secureInput($_POST['endDate']),
                           ':id'   => secureInput($_POST['idMod'])));

    }

	}


}


//header("Location: ../enseignements.php");
