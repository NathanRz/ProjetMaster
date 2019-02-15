<?php

require_once "php/autoload.include.php";

$p = new BootstrapPage("Connexion");
$p->setLanguage("fr");
$p->appendContent(Layout::nav(3));
$p->appendContent('<div class="container">');
$p->appendContent(Admin::loginForm("login.php"));
$p->appendContent('</div>');
$p->appendContent(Layout::footer());

echo $p->toHTML();
