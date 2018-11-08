<?php

require_once "php/autoload.include.php";

$p = new BootstrapPage("Connexion");
$p->setLanguage("fr");
$p->appendContent(Layout::nav());

$p->appendContent(Admin::loginForm("login.php"));

$p->appendContent(Layout::footer());

echo $p->toHTML();