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


HTML
);
    $p->appendJs(<<<JAVASCRIPT
      $( function() {
        $( "#dateP1" ).datepicker({ dateFormat: 'yy-mm-dd' });
      });

      $( function() {
        $( "#dateP2" ).datepicker({ dateFormat: 'yy-mm-dd' });
      });

      var form = $('.edit');
      var box = $('.box');
      var droppedFile = false;
      box.on('drag dragstart dragend dragover dragenter dragleave drop', function(e){
        e.preventDefault();
        e.stopPropagation();
      })
      .on('dragover dragenter',function(){
        box.addClass('is-dragover');
      })
      .on('dragleave dragend drop', function(){
        box.removeClass('is-dragover');
      })
      .on('drop', function(e){
        droppedFile = e.originalEvent.dataTransfert.files;
      });

      var valid = $('.fancy-button');
      valid.on('click',function(){
        var formData = new FormData(form);
        formData.append(form.get(0));
        console.log(formData);
      })
      form.on('submit', function(e){
        if(form.hasClass('is-uploading')) return false;

        form.addClass('is-uploading').removeClass('is-error');

        //ajax

        e.preventDefault();
        var formData = new FormData(form);
        console.log(formData);
        if(droppedFile){
          formData.append($.input.attr(''))
        }

      })



JAVASCRIPT
);
    echo $p->toHTML();
  }
}else{
  header("index.php");
}
