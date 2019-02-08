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

$_POST['id'] = $_GET['id'];
$p->appendContent(<<<HTML
  <div class='container container-edit'>
      <h1>Dépôt de projet</h1>
      <form name="depoFich" method="POST" action="php/addProject.php" enctype="multipart/form-data">
        <div class="row">

            <div class="col-lg mt-4">
              <select name="binom" placeholder="Choisir un groupe">
                <option value="null">Choisir un groupe</option>
                {$options}
              </select>
            </div>
            <div class="col-lg mt-4">
              <input type="file" name="sources" accept=".zip">
            </div>
            <div class="col-lg mt-4">
              <input type="file" name="rapport" accept=".pdf">
            </div>

        </div>
        <div class="row rowImg">
            <div class="col-lg mt-3">
              <div class="addImgPrj">
                <span class="plus">+</span>
              </div>
            </div>
        </div>
        <input type="submit" value="Envoyer">
      </form>
  </div>

HTML
);

$p->appendContent(Layout::footer());



echo $p->toHTML();
