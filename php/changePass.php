<?php

include_once("autoload.include.php");
include_once("utils.php");

if(Admin::isConnected()){
  $a = Admin::createFromSession();
  if(isset($_POST['pass']) && !empty($_POST['pass'])){
    $stmt = myPDO::getInstance()->prepare(<<<SQL
      UPDATE admin
      SET password = :p
      WHERE idAdmin = :id

SQL
    );

    $stmt->execute(array(':id' => secureInput($a->getId()),
                         ':p' => secureInput(password_hash($_POST['pass'],PASSWORD_BCRYPT))));
  }
}

header('Location: ../index.php');
