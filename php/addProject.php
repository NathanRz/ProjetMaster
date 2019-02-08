<?php

include_once("autoload.include.php");
require_once("utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(isset($_FILES['sources']['tmp_name']) && !empty($_FILES['sources']['tmp_name'])){
    $sources = $_FILES['sources']['tmp_name'];
  }
  Projet::addProjet($_POST['id'], $sources,null,null);
}
