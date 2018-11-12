<?php

require_once "php/autoload.include.php";
require_once "php/mypdo.include.php";

$pdo = myPDO::getInstance();

try{
	$admin = Admin::createFromAuth($_POST);
	$admin->saveIntoSession();

	if(Admin::isConnected())
		header("Location: index.php");

}catch(AuthenticationException $e){
	header("Location: index.php");
}

//header("Location: index.php");