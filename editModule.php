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
      $tab .= "\n<tr><td>" . $g->getLib() ."</td><td>". $g->getTypeString() ."</td><td>". $g->getHoraire() ."</td><td><a href='php/gestionGrp.php?id=" .$g->getId(). "&idMod=" . $m->getId() ."'>Supprimer</a></td></tr>\n";
    }


    $p->appendContent(Layout::nav(1));
    $p->appendJsUrl("js/scripts/editmodule.js");
    $p->appendContent(<<<HTML
      <div class='container container-edit'>
        <h1>Module : {$m->getLibModule()}</h1>
        <hr style="background:white" />
        <form name='edit' method="POST" action="php/modifyModule.php" enctype="multipart/form-data">
          <h4 class="editTitle">Changer le mot de passe du module</h4>
          <div class="row rowBase">
            <div class="col-lg mt-6">
              <div class="row editMod">
                <div class="col-6">
                  <label for="pass">Mot de passe : </label>
                </div>
                <div class="col-6">
                  <input type="password" name="passMod" class="fancy-input">
                </div>
              </div>
              <h4 class="editTitle">Changer les dates des projets</h4>
              <div class="row editMod">
                <div class="col-6">
                  <label for="startDate">Date d'ouverture : </label>
                </div>
                <div class="col-6">
                  <input id="dateP1" class="fancy-input" type="text" name="startDate" value="{$m->getStartDate()}">
                </div>
              </div>
              <div class="row padRow">
                <div class="col-6">
                  <label for="endDate">Date de fermeture : </label>
                </div>

                <div class="col-6">
                  <input id="dateP2" class="fancy-input" type="text" name="endDate" value="{$m->getEndDate()}">
                </div>
              </div>
              <div class="row padRow">
                <div class="col-6">
                  <label for="duree">Duree d'un projet : </label>
                </div>
                <div class="col-6">
                  <input class="fancy-input" type="text" name="duree" value="{$m->getDuree()}">
                </div>
              </div>
            </div>
            <div class="col-lg mt-6 imgUp">
              <div class="row" >
                  <div class="box" style="margin:auto">
                    <div>
                      <img class="imgEdit" src="{$img}" alt="Image module"  width="160" height="160">
                    </div>
                    <div>
                      <label for="img"><a href="" id="upload_link"><strong>Choisissez un fichier</strong></a><span class="box__dragndrop"> ou deplacez le ici</span>.</label>
                      <input id="upload" type="file" name="img">
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="idMod" value="{$_GET['id']}">
          <div class="row">
              <button type="submit" class="fancy-button buttonEdit">Valider</button>
          </div>
          </form>

          <div class="row rowBase">
            <form onsubmit="return false;" name="addGrp">

            <div class="col-lg mt-6">
              <h4>Ajouter des groupes de TD/TP</h4>

              <div class="row">
                <div class="col-6">
                  <label for="title">Libellé groupe: </label>
                </div>
                <div class="col-6">
                  <input class="fancy-input" type="text" name="title">
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label for="">Type : </label>
                </div>
                <div class="col-6">
                  <select name="type">
                    <option value="1">TD</option>
                    <option value="2">TP</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label for="horaire">Heure debut: </label>
                </div>
                <div class="col-6">
                  <input class="fancy-input" type="text" name="horaire">
                </div>
              </div>
              <input type="hidden" name="idMod" value="{$m->getId()}">
            </div>
            <button id="addgrp" type="submit" class="fancy-button col-12 buttonAdd">Ajouter</button>
            </form>

            <div id="listgrp" class="col-lg mt-6">
              <h4>Liste des groupes</h4>
              <div class="modules">
    						<div class="modules-body">
                  <table class="table .table-dark">
                    <thead>
                      <tr>
                        <th scope="col">Libellé</th>
                        <th scope="col">Type</th>
                        <th scope="col">Horaire</th>
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





HTML
);
    echo $p->toHTML();
  }
}else{
  header("index.php");
}
