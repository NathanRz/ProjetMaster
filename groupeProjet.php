<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");
session_start();
$mod = Module::getModuleById($_GET['id']);
if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){

$p = new BootstrapPage("Enseignements");
$p->appendContent(Layout::nav(1));
$grps = GroupeProjet::getGrpByidMod($_GET["id"]);
if(Admin::isConnected()){
  $contentTable = <<<HTML
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
        <th scope="col">Prénom</th>
        <th scope="col">TD</th>
        <th scope="col">TP</th>
        <th scope="col">Projet</th>
      </tr>
    </thead>
    <tbody>
HTML;
  foreach ($grps as $grp) {
    for($i = 0; $i < count($grp["etudiants"]);$i++) {
      if($i == 0){
        $span = "<td scope ='row' rowspan=" . count($grp["etudiants"]) . ">" . $grp["idGroupePrj"] . "</td>";
      } else{
        $span="";
      }

      $contentTable.= <<<HTML

      <tr>
        {$span}
        <td>{$grp["etudiants"][$i]["nom"]}</td>
        <td>{$grp["etudiants"][$i]["prenom"]}</td>
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
          <td>{$td}</td>
          <td>{$tp}</td>
HTML;
        if($grp["archive"] != null){
          if($i == 0)
            $contentTable .="<td scope ='row' rowspan='" . count($grp["etudiants"]) . "' class='bg-success'><a href='{$grp["archive"]}'>Source</a></td>";
        } else{
            if($i == 0)
              $contentTable .="<td scope ='row' rowspan='" . count($grp["etudiants"]) . "'class='bg-danger'>Projet non rendu</td>";
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

      <table class="table" id="t2ex">
      {$contentTable}

      <form action="" name="export">
        <input type="hidden" id="inputId" value="{$_GET['id']}"/>
      </form>
      <button class="fancy-button" id="export">Exporter sous Excel</button>
    </div>
HTML
    );

    $p->appendJs(<<<JAVASCRIPT

      $("#export").on("click", function(){
        var id = $("#inputId").val();
        console.log(id);
        $.ajax({
            type: 'GET',
            url: 'php/exportExcel.php',
            contentType: false,
            processData: false,
            data: "id=" + id,
            success:function(response) {
              
            }
        });
      });

JAVASCRIPT
    );

} else {

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
        $span = "<td scope ='row' rowspan=" . count($grp["etudiants"]) . ">" . $grp["idGroupePrj"] . "</td>";
      } else{
        $span="";
      }

      $contentTable.= <<<HTML

      <tr>
        {$span}
        <td>{$grp["etudiants"][$i]["nom"]}</td>
        <td>{$grp["etudiants"][$i]["prenom"]}</td>
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
          <td>{$td}</td>
          <td>{$tp}</td>
HTML;
        if($grp["idProjet"] != null){
          if($i == 0)
            $contentTable .="<td scope ='row' rowspan=" . count($grp["etudiants"]) . "' class='bg-success'>Oui</td>";
        } else{
          if($i == 0)
            $contentTable .="<td scope ='row' rowspan=" . count($grp["etudiants"]) . "' class='bg-danger'>Non</td>";
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

      <table class="table" id="t2ex">
        {$contentTable}
    </div>
    <button id="export">Exporter</button>
HTML
  );
}

echo $p->toHTML();

}else{
  header("Location: index.php");
}
