<?php
include_once("autoload.include.php");
require_once "../OneSheet/autoload.php";

if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $mod = Module::getModuleById($_GET["id"]);
    $grps = GroupeProjet::getGroupePrjByMod($_GET["id"]);

    $data = array();
    foreach($grps as $grp){
      foreach($grp->getEtudiants() as $etu){
        $row = array();
        array_push($row, Groupe::getGroupeById($grp->getIdGroupe())->getLib());
        array_push($row, $grp->getHoraire());
        array_push($row, $etu->getNom());
        array_push($row, $etu->getPrenom());
        array_push($row, $etu->getGrpTD()->getLib());
        array_push($row, $etu->getGrpTP()->getLib());
        array_push($data,$row);
      }
    }

    $os = new \OneSheet\Writer();
    $os->enableCellAutosizing();
    $headerStyle = new \OneSheet\Style\Style();
    $headerStyle->setFontSize(13)->setFontBold()->setFontColor('FFFFFF')->setFillColor('777777');

    $headers=array("Groupe TP", "Horaire","Nom","Prénom","TD","TP","Note");
    $os->addRow($headers,$headerStyle);


    $dataStyle1 = new \OneSheet\Style\Style();
    $dataStyle1->setFillColor('A8AAAD');

    $dataStyle2 = new \OneSheet\Style\Style();

    for($i = 0; $i < count($data); $i++){
        if ($i % 2) {
            $os->addRow($data[$i], $dataStyle1);
        } else {
            $os->addRow($data[$i], $dataStyle2);
        }
    }

    $path = "Liste groupe " . $mod->getLibModule() . ".xlsx";
    $os->writeToBrowser($path);
  }
}
