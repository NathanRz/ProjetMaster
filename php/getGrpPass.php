<?php

include_once("layout.class.php");
include_once("autoload.include.php");
session_start();
$mod = Module::getModuleById($_GET['id']);
if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){

  $p = new BootstrapPage("Enseignements");
  $p->appendContent(Layout::nav(1));

  $grps = GroupeProjet::getGroupePrjByMod($_GET["id"]);
  if(Admin::isConnected()){
    $grpHor = array();
    foreach ($grps as $g) {
      $grpHor[$g->getIdGroupePrj()] = array();
      foreach($g->getEtudiants() as $etu){
        $h = array();
        $h[$etu->getGrpTP()->getId()] = $etu->getGrpTP()->getLib();
        $grpHor[$g->getIdGroupePrj()] += $h;
      }
    }
  }

  echo json_encode($grpHor[$_GET['idGrp']]);
}
