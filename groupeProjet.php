<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");
session_start();
$mod = Module::getModuleById($_GET['id']);
if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){

$p = new BootstrapPage("Enseignements");
$p->appendContent(Layout::nav(1));
$grps = GroupeProjet::getGroupePrjByMod($_GET["id"]);
if(Admin::isConnected()){
  $contentTable = <<<HTML
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
        <th scope="col">Prénom</th>
        <th scope="col">TD</th>
        <th scope="col">TP</th>
        <th scope="col">Rapport</th>
        <th scope="col">Source</th>
        <th scope="col">Image</th>
        <th scope="col">Date de dépôt</th>
      </tr>
    </thead>
    <tbody>
HTML;
  foreach ($grps as $grp) {
    $cpt = 0;
    foreach($grp->getEtudiants() as $etu) {
      if($cpt == 0){
        $span = "<td scope ='row' rowspan=" . count($grp->getEtudiants()) . ">" . $grp->getIdGroupePrj() . "</td>";
      } else{
        $span="";
      }

      $contentTable.= <<<HTML

      <tr>
        {$span}
        <td>{$etu->getNom()}</td>
        <td>{$etu->getPrenom()}</td>
HTML;
        $td = $etu->getGrpTD()->getLib();
        $tp = $etu->getGrpTP()->getLib();

        $contentTable .=<<<HTML
          <td>{$td}</td>
          <td>{$tp}</td>
HTML;
        if($grp->getIdProjet() != null){
          if($cpt == 0){
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "' class='bg-success'><a href='{$grp->getProjet()->getRapport()}'>Rapport</a></td>";
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "' class='bg-success'><a href='{$grp->getProjet()->getArchive()}'>Sources</a></td>";
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "' class='bg-success'><a href='{$grp->getProjet()->getImages()}'>Images</a></td>";
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'>{$grp->getProjet()->getDate()}</td>";
          }
        } else{
            if($cpt == 0){
              $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>Projet non rendu</td>";
              $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>Projet non rendu</td>";
              $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>Projet non rendu</td>";
              $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>-</td>";
            }
        }

      $contentTable.="</tr>";
      $cpt++;
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
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>
HTML;
foreach ($grps as $grp) {
  $cpt = 0;
  foreach($grp->getEtudiants() as $etu) {
    if($cpt == 0){
      $span = "<td scope ='row' rowspan=" . count($grp->getEtudiants()) . ">" . $grp->getIdGroupePrj() . "</td>";
    } else{
      $span="";
    }

    $contentTable.= <<<HTML

    <tr>
      {$span}
      <td>{$etu->getNom()}</td>
      <td>{$etu->getPrenom()}</td>
HTML;
      $td = $etu->getGrpTD()->getLib();
      $tp = $etu->getGrpTP()->getLib();

      $contentTable .=<<<HTML
        <td>{$td}</td>
        <td>{$tp}</td>
HTML;
      if($grp->getIdProjet() != null){
        if($cpt == 0){
          $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "' class='bg-success'>Oui</td>";
          $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'>{$grp->getProjet()->getDate()}</td>";
        }
      } else{
          if($cpt == 0){
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>Projet non rendu</td>";
            $contentTable .="<td scope ='row' rowspan='" .  count($grp->getEtudiants()) . "'class='bg-danger'>-</td>";
          }
      }

    $contentTable.="</tr>";
    $cpt++;
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
