<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());

$mod = Module::getModuleById($_GET['id']);
$title = "<div class='container container-mod'> \n <h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";
$p->appendContent($title);

if(Admin::isConnected()){
  $cmPart = <<<HTML
    <div class = "part">
      <h1 style ="text-align : left"> Cours magistraux </h2>
        <a href="#" data-toggle="modal" data-target="#myModal">
          <img src="img/document_add.png" width="32" height="32" alt="Ajouter un CM">
        </a>
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
        <a href="{$f->getCheminFichier()}">
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
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>\n</div>");

} else {
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
        <a href="{$f->getCheminFichier()}">
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
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>\n</div>");
}

$p->appendContent(<<<HTML
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Ajout d'un CM</h4>
        </div>

        <form name="addCM" method="POST" action="php/addFile.php" enctype="multipart/form-data">
        <!-- Modal body -->
        <div class="modal-body">
          <input name="cmFile" type="file" accept="application/pdf">
          <input name="descFile" type="text" placeholder="Description du fichier">
          <input name="typeFile" type="hidden" value="CM">
          <input name="idModule" type="hidden" value="{$_GET["id"]}">
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Valider</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
        </div>
        </form>

      </div>
    </div>
  </div>
HTML
);

echo $p->toHTML();
