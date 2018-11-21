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
    $p->appendContent(<<<HTML
      <div class='container container-mod'>
        <h1>Module : {$m->getLibModule()}</h1>
        <hr style="background:white" />
        <form name='edit' method="POST">
          <h4 class="editTitle">Changer le mot de passe du module</h4>
          <div class="row">
            <div class="col-6">
              <div class="row editMod">
                <div class="col-6">
                  <label for="pass">Mot de passe : </label>
                </div>
                <div class="col-6">
                  <input type="password" class="fancy-input">
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
                  <span style="margin:auto">
                    <img class="imgEdit" src="{$img}" alt="Image module"  width="200" height="200">
                  </span>
              </div>
            </div>
          </div>
          <div class="row">
            <a onclick="forms['edit'].submit()" class="fancy-button">Valider</a>
          </div>

        </form>


HTML
);
    $p->appendJs(<<<JAVASCRIPT
      $( function() {
        $( "#dateP1" ).datepicker();
      });

      $( function() {
        $( "#dateP2" ).datepicker();
      });


JAVASCRIPT
);
    echo $p->toHTML();
  }
}else{
  header("index.php");
}
