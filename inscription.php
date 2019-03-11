<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");
session_start();

if(isset($_GET['id']) && !empty($_GET['id'])){
  $mod = Module::getModuleById($_GET['id']);
  if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){
  $p = new BootstrapPage("Enseignements");

  $p->appendContent(Layout::nav(1));
  $groupsTD = Groupe::getAllGrpForModuleByType($_GET['id'],1);
  $groupsTP = Groupe::getAllGrpForModuleByType($_GET['id'],2);

  $selTD = "";
  $selTP = "";

  foreach ($groupsTD as $grp) {
    $selTD .= '\n<option value="' . $grp->getId() . '">' . $grp->getTypeString() . ' ' . $grp->getLib() . '</option>';
  }

  foreach ($groupsTP as $grp) {
    $selTP .= '\n<option value="' . $grp->getId() . '">' . $grp->getTypeString() . ' ' . $grp->getLib() . '</option>';
  }
  $p->appendContent(<<<HTML

    <div class="container container-edit">
      <h2>Inscription</h2>
      <p>Saisissez le nom et prénom ainsi que les groupes de TP et TD de chaque membre du groupe</p>
      <form id="fInsc" name="inscr" method="POST" action="php/addGroupePrj.php">
        <fieldset id="rowsEtu">
          <legend>Etudiants</legend>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="nom[]">Nom</label>
                <input name="nom[]" class="fancy-input" type="text" placeholder="Nom" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="prenom[]">Prénom</label>
                <input name="prenom[]" class="fancy-input" type="text" placeholder="Prenom" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="grpTD[]">Groupe de TD</label>
                <select class="browser-default custom-select" name="grpTD[]">
                  {$selTD}
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="grpTP[]">Groupe de TP</label>
                <select class="browser-default custom-select" name="grpTP[]">
                  {$selTP}
                </select>
              </div>
            </div>
            <div class="col-md-2 d-flex align-items-center">
              <div>
                <a id="add" href="#"><img class="img-fluid" width="25" src="img/add.png"/></a>
              <div>
            </div>
          </div>
        </fieldset>
        <input type="hidden" name="idMod" value="{$_GET['id']}">
        <div class="row">
          <div class="col-md-3 offset-4 mt-5">
            <input class="fancy-button" style="width:100%" type="submit">
          </div>
        </div>
      </form>
    </div>

    <script>
      var line = '<div class="row addedEtu">'+
                    '<div class="col-md-3">'+
                      '<div class="form-group">'+
                        '<label for="nom[]">Nom</label>'+
                        '<input name="nom[]" class="fancy-input" type="text" placeholder="Nom" required>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                      '<div class="form-group">'+
                        '<label for="prenom[]">Prénom</label>'+
                        '<input name="prenom[]" class="fancy-input" type="text" placeholder="Prenom" required>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                      '<div class="form-group">'+
                        '<label for="grpTD[]">Groupe de TD</label>'+
                        '<select class="browser-default custom-select" name="grpTD[]">{$selTD}</select>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                      '<div class="form-group">'+
                        '<label for="grpTP[]">Groupe de TP</label>'+
                        '<select class="browser-default custom-select" name="grpTP[]">{$selTP}</select>'+
                      '</div>'+
                    '</div>'+
                    '<div class="col-md-2 d-flex align-items-center">'+
                      '<div><a class="delEtuRow" href="#"><img class="img-fluid" width="25" src="img/delete.png"/></a><div>'+
                    '</div>'+
                  '</div>';
      $("#add").on('click', function(e){
        $("#rowsEtu").append(line).children(':last').hide().fadeIn(300);
      });

      $("body").on('click', 'a.delEtuRow', function(e){
        console.log($("#rowsEtu").last());
        $(".addedEtu").last().fadeOut(300, function(){jQuery(this).remove()});
      });
    </script>
HTML
  );


  echo $p->toHTML();
  }else{
    header("Location: index.php");
  }
}else{
  header("Location: index.php");
}
