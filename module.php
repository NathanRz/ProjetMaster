<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());
$mod = Module::getModuleById($_GET['id']);

$title = "<div class='container container-mod'> \n <h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";

$cmPart = <<<HTML

  <div class = "cmPart">
    <h2 style ="text-align : left"> Cours magistraux </h2>
    <hr id="hrMod"/>


HTML;
$res = Fichier::getFichiersByModule($_GET['id']);

foreach ($res as $f) {
  $cmPart .= <<<HTML
  <div>
    <a href="">
      <h4>{$f->getNomFichier()}</h4>
    </a>
    <p>{$f->getDescFichier()}</p>
  </div>
HTML;
}

$res = Fichier::getFichiersByModule($_GET['id']);



$p->appendContent($title);
$p->appendContent($cmPart);
echo $p->toHTML();
