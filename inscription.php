<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");

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
      <div id="firstRow" class="row">
        <div class="col-lg mt-3">
          <input name="nom[]" class="fancy-input" type="text" placeholder="Nom" required>
        </div>
        <div class="col-lg mt-3">
          <input name="prenom[]" class="fancy-input" type="text" placeholder="Prenom" required>
        </div>
        <div class="col-lg mt-3">
          <select name="grpTD[]">
            {$selTD}
          </select>
          <select name="grpTP[]">
            {$selTP}
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-lg mt-3">
          <button id="add" class="fancy-button">Ajouter un étudiant</button>
        </div>
      </div>
      <div class="row">
        <div class="col-lg mt-3">
          <input type="hidden" name="idMod" value="{$_GET['id']}">
          <input class="fancy-button" type="submit">
        </div>
      </div>
    </form>
  </div>

  <script>
    var line = '<div id="firstRow" class="row"><div class="col-lg mt-3"><input name="nom[]" class="fancy-input" type="text" placeholder="Nom" required></div><div class="col-lg mt-3"><input name="prenom[]" class="fancy-input" type="text" placeholder="Prenom" required></div><div class="col-lg mt-3"><select name="grpTD[]">{$selTD}</select><select name="grpTP[]">{$selTP}</select></div></div>';
    $("#add").on('click', function(e){
      $("#firstRow").after(line);
    });
  </script>
HTML
);


echo $p->toHTML();
