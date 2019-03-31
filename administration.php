<?php
include_once("php/autoload.include.php");

if(Admin::isConnected()){

  $p = new BootstrapPage("Administration");
  $p->appendContent(Layout::nav(1));

  $etus = Etudiant::getEtudiants();
  $tabData  ="";
  foreach ($etus as $e) {
    $tabData .= "<tr><td>".$e->getId()."</td><td>".$e->getNom()."</td><td>".$e->getPrenom()."</td><td><a href='php/delEtudiant.php?id=".$e->getId()."'>Supprimer</a></td></tr>";
  }
  $p->appendContent(<<<HTML
  <div class='container container-edit'>
    <h1>Administration</h1>
    <div class="row">
      <div class="col-md-12">
        <table class="table .table-dark responsive">
          <thead>
            <tr>
              <th>ID</th><th>Nom</th><th>Prénom</th><th> - </th>
            </tr>
          </thead>
          <tbody>
            {$tabData}
          </tbody>
        </table>
      </div>
    </div>
  </div>
HTML
  );

echo $p->toHTML();
}else{

}
