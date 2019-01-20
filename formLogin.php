<?php

require_once "php/autoload.include.php";

$p = new BootstrapPage("Connexion");
$p->setLanguage("fr");
$p->appendContent(Layout::nav(2));

$p->appendContent(Admin::loginForm("login.php"));

$p->appendContent(Layout::footer());

echo $p->toHTML();
