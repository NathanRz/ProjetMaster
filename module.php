<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");

$p->appendContent(Layout::nav());
$mod = Module::getModuleById($_GET['id']);
$p->appendContent(<<<HTML
  <div class="container  container-edit">
    <ul>
      <li><a href="inscription.php?id={$mod->getId()}">S'inscrire</a></li>
    </ul>
  </div>



HTML
);

echo $p->toHTML();
