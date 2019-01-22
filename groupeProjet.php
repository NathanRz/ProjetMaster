<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");

$p = new BootstrapPage("Enseignements");
$p->appendContent(Layout::nav(1));
$grps = GroupeProjet::getGrpByidMod($_GET["id"]);
var_dump($grps);
$contentTable = <<<HTML
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nom</th>
      <th scope="col">Prénom</th>
      <th scope="col">TD</th>
      <th scope="col">TP</th>
      <th scope="col">Projet rendu</th>
    </tr>
  </thead>
  <tbody>
HTML;
foreach ($grps as $grp) {
  for($i = 0; $i < count($grp["etudiants"]);$i++) {
    if($i == 0){
      $span = "<th scope ='row' rowspan=" . count($grp["etudiants"]) . ">" . $grp["idGroupePrj"] . "</th>";
    } else{
      $span="";
    }

    $contentTable.= <<<HTML

    <tr>
      {$span}
      <th>{$grp["etudiants"][$i]["nom"]}</th>
      <th>{$grp["etudiants"][$i]["prenom"]}</th>
HTML;
      $td ="";
      $tp="";
      if($grp["etudiants"][$i]["groupe"][0]["typeGroupe"] == "1"){
        $td = $grp["etudiants"][$i]["groupe"][0]["libGroupe"];
        $tp = $grp["etudiants"][$i]["groupe"][1]["libGroupe"];
      } else {
        $td = $grp["etudiants"][$i]["groupe"][1]["libGroupe"];
        $tp = $grp["etudiants"][$i]["groupe"][0]["libGroupe"];
      }

      $contentTable .=<<<HTML
        <th>{$td}</th>
        <th>{$tp}</th>
HTML;
      if($grp["idProjet"] != null){
        $contentTable .="<th class='bg-success'>Oui</th>";
      } else{
        $contentTable .="<th class='bg-danger  '>Non</th>";
      }

    $contentTable.="</tr>";
  }
}

$contentTable .=<<<HTML
    </tbody>
  </table>
HTML;


$p->appendContent(<<<HTML

  <div class="container container-edit">
    <h2>Groupes de projet</h2>

    <table class="table">
    {$contentTable}
  </div>

  <script>
  </script>
HTML
);



echo $p->toHTML();
