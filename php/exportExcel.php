<?php
include_once("autoload.include.php");
require_once "../OneSheet/autoload.php";

$grps = GroupeProjet::getGrpByidMod(1);


$data = array();
foreach($grps as $grp){
  for($i = 0; $i < count($grp["etudiants"]); $i++){
    $row = array();
    array_push($row, $grp["idGroupePrj"]);
    array_push($row, $grp["etudiants"][$i]["nom"]);
    array_push($row, $grp["etudiants"][$i]["prenom"]);
    if($grp["etudiants"][$i]["groupe"][0]["typeGroupe"] == "1"){
      array_push($row, $grp["etudiants"][$i]["groupe"][0]["libGroupe"]);
      array_push($row, $grp["etudiants"][$i]["groupe"][1]["libGroupe"]);
    } else {
      array_push($row, $grp["etudiants"][$i]["groupe"][1]["libGroupe"]);
      array_push($row, $grp["etudiants"][$i]["groupe"][0]["libGroupe"]);
    }
    array_push($data,$row);
  }
}

$os = new \OneSheet\Writer();
$headerStyle = new \OneSheet\Style\Style();
$headerStyle->setFontSize(13)->setFontBold()->setFontColor('FFFFFF')->setFillColor('777777');

$headers=array("Groupe", "Nom","Prénom","TD","TP","Note");
$os->addRow($headers,$headerStyle);
$os->addRows($data);
$path = "../groupes_mod_" . $_GET["id"] . ".xlsx";
$os->writeToFile($path);
