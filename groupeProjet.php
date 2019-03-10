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
        <th scope="col">Horaire</th>
        <th scope="col">Groupe de passage</th>
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
  $id = 0;
  foreach ($grps as $grp) {

    $cpt = 0;
    foreach($grp->getEtudiants() as $etu) {
      if($cpt == 0){
        $horaire = $grp->getHoraire() != null ? $grp->getHoraire() : "Pas encore défini";
        $span = "<td scope ='row' rowspan=" . count($grp->getEtudiants()) . ">" . $horaire . "</td>";
        $span .="<td class='grpHo' id='grpHo". $grp->getIdGroupePrj() ."' scope ='row' rowspan=" . count($grp->getEtudiants()) . ">" . Groupe::getGroupeById($grp->getIdGroupe())->getLib() . "</td>";
      } else{
        $span="";
      }

      $contentTable.= <<<HTML

      <tr class="testClick">
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
    $id++;
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
      <button class="fancy-button"><a href="php/calculHoraires.php?id={$mod->getId()}">Générer les horaires</a></button>
    </div>
HTML
    );

    $p->appendJs(<<<JAVASCRIPT

      var idGrp;
      function activate(){
        $(".testClick").on("click",'.grpHo',function(){
          var active = "#" + $(this).attr("id");
          $(".testClick").off("click");
          var id = $("#inputId").val();
          idGrp = active.substr(6);
          $(active).empty();
          $.ajax({
            type: 'GET',
            url: 'php/getGrpPass.php',
            contentType: false,
            processData: false,
            data: "id=" + id+"&idGrp="+idGrp,
            success:function(response) {
              var grp = JSON.parse(response);
              var options="";
              for(var i in Object.keys(grp)){
                options += "<option value='"+ Object.keys(grp)[i] +"'>"+ grp[Object.keys(grp)[i]] +"</option>";
              }
              var select =  "<select onchange='updateHoraire(this)' name='passage'>"+
                              "<option value=''></option>" +
                              options +
                            "</select>";
              $(active).append(select);
            }
          });
        });
      }
      activate();


      function updateHoraire(select){
        var newGrp = select.value;
        $.ajax({
          type: 'GET',
          url: 'php/updatePassage.php',
          contentType: false,
          processData: false,
          data: "idGrpPrj=" + idGrp +"&idGrpPass="+newGrp,
          success:function(response) {
            $("#grpHo" + idGrp).empty();
            $("#grpHo" + idGrp).append(response);
            activate();
          }
        });
      }

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
        <th scope="col">Horaire</th>
        <th scope="col">Groupe de Passage</th>
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
    $span="";
    if($cpt == 0){
      $horaire = $grp->getHoraire() != null ? $grp->getHoraire() : "Pas encore défini";
      $span = "<td scope ='row' rowspan='" . count($grp->getEtudiants()) . "'>" . $horaire . "</td>";
      $span .= "<td scope ='row' rowspan='" . count($grp->getEtudiants()) . "'>" . Groupe::getGroupeById($grp->getIdGroupe())->getLib() . "</td>";
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
  </div>
HTML
  );
}
echo $p->toHTML();

}else{
  header("Location: index.php");
}
