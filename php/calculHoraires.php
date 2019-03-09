<?php
include_once("autoload.include.php");
require_once "mypdo.include.php";
require_once "utils.php";


function cmp($a, $b){
  return count($a->getEtudiants()) > count($b->getEtudiants());
}


if(Admin::isConnected()){
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $mod = Module::getModuleById($_GET['id']);
      //On supprime les anciens horaires si ils existent
      $stmt = myPDO::getInstance()->prepare(<<<SQL
        UPDATE groupeprojet SET horaire = null
        WHERE idModule = :idM
SQL
);
      $stmt->execute(array(':idM' => $mod->getId()));
    }
  }
}

$grps = GroupeProjet::getGroupePrjByMod($mod->getId());
$dureeProjet = strtotime($mod->getDuree());
//var_dump($dureeProjet);

//echo date('i', $dureeProjet);

usort($grps,"cmp");

$temp = array();
foreach ($grps as $g) {
  $grptp = Groupe::getGroupeById($g->getIdGroupe());
  $temp[$g->getIdGroupe()] = $grptp->getHoraire();
}

$grpPrjHoraires = array();

foreach ($grps as $g) {
  $h = array();
  $h["idGroupePrj"] = $g->getIdGroupePrj();
  $h["horaire"] = $temp[$g->getIdGroupe()];
  //$grpPrjHoraires[$g->getIdGroupePrj()] = $temp[$g->getIdGroupe()];
  $d = strtotime($temp[$g->getIdGroupe()]);
  $d = date('H:i:s',strtotime('+'.date('i',$dureeProjet)." minutes",$d));
  $temp[$g->getIdGroupe()] = $d;
  array_push($grpPrjHoraires, $h);
}

foreach ($grpPrjHoraires as $h) {
  $stmt = myPDO::getInstance()->prepare(<<<SQL
    UPDATE groupeprojet SET horaire = :hor
    WHERE idGroupePrj = :id
SQL
);
  $stmt->execute(array(':hor' => $h['horaire'],
                       ':id' => $h['idGroupePrj']));
}




//header('Location: ../groupeProjet.php?id='.$_GET['id']);
