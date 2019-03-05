<?php
include_once("php/autoload.include.php");

if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])){
    $m = Module::getModuleById($_GET["id"]);
    $p = new BootstrapPage('Modifier un module');
    $img = $m->getImgMod();
    if($img === null){
      $img = "img/notfound.png";
    }

    //On génère le tableau de groupes
    $grps = Groupe::getAllGrpForModule($m->getId());
    $tab = "";
    foreach ($grps as $g) {
      $tab .= "\n<tr><td>" . $g->getLib() ."</td><td>". $g->getTypeString() ."</td><td>". $g->getHoraire() ."</td><td>". $g->getDuree() ."</td><td><a href='php/gestionGrp.php?id=" .$g->getId(). "&idMod=" . $m->getId() ."'>Supprimer</a></td></tr>\n";
    }

    $p->appendContent(Layout::nav(1));
    $p->appendJsUrl("js/scripts/editmodule.js");
    $p->appendContent(<<<HTML
      <div class='container container-edit'>
        <h1>Module : {$m->getLibModule()}</h1>
        <hr style="background:white" />
        <form class="" name='edit' method="POST" action="php/modifyModule.php" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <fieldset>
                <legend>Changer le mot de passe du module</legend>
                <div class="form-group">
                    <label for="pass">Mot de passe : </label>
                    <input type="password" name="passMod" class="fancy-input">
                </div>
              </fieldset>
              <fieldset>
                <legend>Changer les dates des projets</legend>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label for="startDate">Date d'ouverture : </label>
                    <input id="dateP1" class="fancy-input" type="text" name="startDate" value="{$m->getStartDate()}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="endDate">Date de fermeture : </label>
                    <input id="dateP2" class="fancy-input" type="text" name="endDate" value="{$m->getEndDate()}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="duree">Duree d'un projet : </label>
                    <input class="fancy-input" type="time" name="duree" aria-describedby="dureeHelp" value="{$m->getDuree()}">
                    <small id="dureeHelp" class="form-text text-muted">Format HH:mm</small>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-6">
              <fieldset>
                <legend>Changer d'image</legend>
                <div class="box" style="margin:auto">
                  <div>
                    <img class="imgEdit" src="{$img}" alt="Image module"  width="160" height="160">
                  </div>
                  <div>
                    <label for="img"><a href="" id="upload_link"><strong>Choisissez un fichier</strong></a><span class="box__dragndrop"> ou deplacez le ici</span>.</label>
                    <input id="upload" type="file" name="img">
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
          <input type="hidden" name="idMod" value="{$_GET['id']}">
          <div class="row">
              <div class="col-md-6">
                <button type="submit" class="fancy-button buttonEdit">Valider</button>
              </div>
          </div>
        </form>
        <form onsubmit="return false;" name="addGrp">
          <div class="row mt-4">
            <div class="col-md-6">
              <fieldset>
                <legend>Ajouter des groupes de TD/TP</legend>
                  <div class="form-group">
                    <label for="title">Libellé groupe</label>
                    <input class="fancy-input" type="text" name="title">
                  </div>
                  <div class="form-group">
                    <label for="type">Type </label>
                    <select class="browser-default custom-select" name="type">
                      <option value="1">TD</option>
                      <option value="2">TP</option>
                    </select>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="horaire">Heure debut: </label>
                      <input class="fancy-input" type="time" aria-describedby="dureeHelp" name="horaire">
                      <small id="horaireHelp" class="form-text text-muted">Format HH:mm</small>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="duree">Durée du cours: </label>
                      <input class="fancy-input" type="time" aria-describedby="dureeHelp" name="duree">
                      <small id="dureeHelp" class="form-text text-muted">Format HH:mm</small>
                    </div>
                  </div>
                <input type="hidden" name="idMod" value="{$m->getId()}">
                <button id="addgrp" type="submit" class="fancy-button col-12 buttonAdd">Ajouter</button>
                </fieldset>
              </div>
        </form>
            <div id="listgrp" class="col-md-6 mt-4">
              <h4>Liste des groupes</h4>
              <div class="modules">
    						<div class="modules-body">
                  <table class="table .table-dark">
                    <thead>
                      <tr>
                        <th scope="col">Libellé</th>
                        <th scope="col">Type</th>
                        <th scope="col">Horaire</th>
                        <th scope="col">Durée</th>
                        <th scope="col"> - </th>
                      </tr>
                    </thead>
                    <tbody id="grps">
                      {$tab}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          </div>





HTML
);
    echo $p->toHTML();
  }
}else{
  header("index.php");
}
