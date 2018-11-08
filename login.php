<?php

require_once "php/autoload.include.php";
require_once "php/mypdo.include.php";

$pdo = myPDO::getInstance();

try{
	$_POST['pass'] = password_hash($_POST['pass'], PASSWORD_BCRYPT);
	$admin = Admin::createFromAuth($_POST);

	$admin->saveIntoSession();
	header("Location: index.php");
}catch(AuthenticationException $e){
	
}

header("Location: index.php");