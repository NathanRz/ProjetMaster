<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";

$acces = 0;
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_SESSION['password'])){
    unset($_SESSION['password']);
  }

  $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT passModule
    FROM module
    WHERE idModule = :id
SQL
);

  $stmt->execute(array(":id" => secureInput($_POST['idMod'])));
  $res = $stmt->fetch();

  if($res['passModule'] == $_POST['password']){
    $acces = 1;
    $_SESSION['password'] = $_POST['password'];

  }

}

echo json_encode($acces);
