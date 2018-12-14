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
    $p->appendContent(Layout::nav());
    $p->appendJsUrl("js/scripts/editmodule.js");
    $p->appendContent(<<<HTML
      <div class='container container-mod'>
        <h1>Module : {$m->getLibModule()}</h1>
        <hr style="background:white" />
        <form name='edit' method="POST" action="php/modifyModule.php" enctype="multipart/form-data">
          <h4 class="editTitle">Changer le mot de passe du module</h4>
          <div class="row">
            <div class="col-6">
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
            </div>
            <div class="col-6">
              <div class="row" >
                  <div class="box" style="margin:auto">
                    <img class="imgEdit" src="{$img}" alt="Image module"  width="160" height="160">
                    <label for="img"><strong>Choisissez un fichier</strong><span class="box__dragndrop"> ou deplacez le ici</span>.</label>
                    <input type="file" name="img">
                  </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="idMod" value="{$_GET['id']}">
          <div class="row">
            <button type="submit" class="fancy-button">Valider</a>
          </div>
          </form>
          <div class="row">
            <div class="col-8">
              <h4>Ajouter des groupes de TD/TP</h4>
              <form onsubmit="return false;" name="addGrp">
                <label for="title">Libellé groupe: </label>
                <input class="fancy-input" type="text" name="title">
                <label for="">Type : </label>
                <select name="type">
                  <option value="TD">TD</option>
                  <option value="TP">TP</option>
                </select>
                <input type="hidden" name="idMod" value="{$m->getId()}">
                <button id="addgrp" type="submit" class="fancy-button">Ajouter</button>
              </form>
            </div>
            <div id="listgrp" class="col-4">
            </div>
          </div>





HTML
);
    echo $p->toHTML();
  }
}else{
  header("index.php");
}
