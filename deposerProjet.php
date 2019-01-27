<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");

$p = new BootstrapPage("Déposer un projet");

$p->appendContent(Layout::nav(1));

$grps = GroupeProjet::getGroupePrjByMod($_GET['id']);
$options = "";
foreach ($grps as $g) {
  $options .= "<option value='" . $g->getIdGroupePrj() . "'>";
  foreach ($g->getEtudiants() as $e) {
    $options .= $e->getNom() . " " . $e->getPrenom() . " | ";
  }

  $options .="</option>";
}
$p->appendContent(<<<HTML
  <div class='container container-edit'>
      <form name="depoFich" method="POST" action="" enctype="multipart/form-data">
        <div class="row">

            <div class="col-lg mt-4">
              <select name="binom" placeholder="Choisir un groupe">
                <option value="null">Choisir un groupe</option>
                {$options}
              </select>
            </div>
            <div class="col-lg mt-4">
              <input type="file" name="archive">
            </div>
            <div class="col-lg mt-4">
              <input type="file" name="images">
            </div>

        </div>
      </form>
  </div>

HTML
);




echo $p->toHTML();
