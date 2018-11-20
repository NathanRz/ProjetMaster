<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());
$mod = Module::getModuleById($_GET['id']);

$title = "<div class='container container-mod'> \n <h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";

$cmPart = <<<HTML

  <div class = "part">
    <h1 style ="text-align : left"> Cours magistraux </h2>
    <hr class="hrPart"/>
HTML;

$tdPart = <<<HTML

  <div class = "part">
    <h1> Travaux dirigés </h2>
    <hr class="hrPart"/>
HTML;


$res = Fichier::getFichiersByModule($_GET['id']);

foreach ($res as $f) {
  if($f->getTypeFichier() == "CM"){
    $cmPart .= <<<HTML
    <div class="cmPart">
      <a href="">
        <h4>{$f->getNomFichier()}</h4>
        <p>{$f->getDescFichier()}</p>
      </a>
    </div>
    <hr>
HTML;
  }
  else if($f->getTypeFichier() == "TD"){
    $tdPart .= <<<HTML
    <div class="cmPart">
      <a href="">
        <h4>{$f->getNomFichier()}</h4>
      </a>
      <p>{$f->getDescFichier()}</p>
    </div>
    <hr>
HTML;
  }

}

$res = Fichier::getFichiersByModule($_GET['id']);



$p->appendContent($title);
$p->appendContent($cmPart . "</div>");
$p->appendContent($tdPart . "</div>");
echo $p->toHTML();
