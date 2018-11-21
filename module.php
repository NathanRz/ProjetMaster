<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());

$mod = Module::getModuleById($_GET['id']);
$title = "<div class='container container-mod'> \n <h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";
$p->appendContent($title);


/************* ADMIN *************/
if(Admin::isConnected()){
  $cmPart = <<<HTML
    <div class = "part">
      <h2> Cours magistraux </h2>
      <a href="#" data-toggle="modal" data-target="#myModalCM">
        <img src="img/document_add.png" width="32" height="32" alt="Ajouter un CM">
      </a>
      <hr class="hrPart"/>
HTML;

  $tdPart = <<<HTML
    <div class = "part">
      <h2> Travaux dirigés </h2>
      <a href="#" data-toggle="modal" data-target="#myModalTD">
        <img src="img/document_add.png" width="32" height="32" alt="Ajouter un TD">
      </a>
      <hr class="hrPart"/>
HTML;

$tpPart = <<<HTML
  <div class = "part">
    <h2> Travaux pratiques </h2>
    <a href="#" data-toggle="modal" data-target="#myModalTP">
      <img src="img/document_add.png" width="32" height="32" alt="Ajouter un TP">
    </a>
    <hr class="hrPart"/>
HTML;

  $res = Fichier::getFichiersByModule($_GET['id']);

  foreach ($res as $f) {
    if($f->getTypeFichier() == "CM"){
      $cmPart .= <<<HTML
      <div class="cmPart">
        <a  data-id="{$f->getId()}" href="#" data-toggle="modal" data-target ="#modalRemove">
          <img src="img/remove.png" width="32" height="32">
        </a>
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
    else if($f->getTypeFichier() == "TD"){
      $tdPart .= <<<HTML
      <div class="cmPart">
        <a  data-id="{$f->getId()}" href="#" data-toggle="modal" data-target ="#modalRemove">
          <img src="img/remove.png" width="32" height="32">
        </a>
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
    else if($f->getTypeFichier() == "TP"){
      $tpPart .= <<<HTML
      <div class="cmPart">
        <a  data-id="{$f->getId()}" href="#" data-toggle="modal" data-target ="#modalRemove">
          <img src="img/remove.png" width="32" height="32">
        </a>
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
    <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>");
  $p->appendContent($tpPart . "</div>\n</div>");


  $p->appendContent(<<<HTML
    <div class="modal" id="myModalCM">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Ajout d'un CM</h4>
          </div>

          <form name="addCM" method="POST" action="php/addFile.php" enctype="multipart/form-data">
          <!-- Modal body -->
          <div class="modal-body">
            <input name="addedFile" type="file" accept="application/pdf">
            <input name="descFile" type="text" placeholder="Description du fichier">
            <input name="typeFile" type="hidden" value="CM">
            <input name="idModule" type="hidden" value="{$_GET["id"]}">
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Valider</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
          </div>
          </form>

        </div>
      </div>
    </div>
HTML
  );

  $p->appendContent(<<<HTML
    <div class="modal" id="myModalTD">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Ajout d'un TD</h4>
          </div>

          <form name="addTD" method="POST" action="php/addFile.php" enctype="multipart/form-data">
          <!-- Modal body -->
          <div class="modal-body">
            <input name="addedFile" type="file" accept="application/pdf">
            <input name="descFile" type="text" placeholder="Description du fichier">
            <input name="typeFile" type="hidden" value="TD">
            <input name="idModule" type="hidden" value="{$_GET["id"]}">
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Valider</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
          </div>
          </form>

        </div>
      </div>
    </div>
HTML
  );

  $p->appendContent(<<<HTML
    <div class="modal" id="myModalTP">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Ajout d'un TP</h4>
          </div>

          <form name="addTP" method="POST" action="php/addFile.php" enctype="multipart/form-data">
          <!-- Modal body -->
          <div class="modal-body">
            <input name="addedFile" type="file" accept="application/pdf">
            <input name="descFile" type="text" placeholder="Description du fichier">
            <input name="typeFile" type="hidden" value="TP">
            <input name="idModule" type="hidden" value="{$_GET["id"]}">
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Valider</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
          </div>
          </form>

        </div>
      </div>
    </div>
HTML
  );

  $p->appendContent(<<<HTML
    <div class="modal" id="modalRemove">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Voulez-vous vraiment supprimer le fichier ?</h4>
          </div>

          <form name="removeCM" method="POST" action="php/removeFile.php" enctype="multipart/form-data">
            <!-- Modal body -->
            <div class="modal-body">
              <input type="hidden" name="idFichier" value="">
              <input type="hidden" name="idModule" value="{$_GET["id"]}">
              <button type="submit" class="btn btn-success">Valider</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
            </div>
          </form>

        </div>
      </div>
    </div>
HTML
  );

  $p->appendJs(<<<JAVASCRIPT
	$('#modalRemove').on('show.bs.modal',function(e){
		var idFichier = $(e.relatedTarget).data('id');
		$(e.currentTarget).find('input[name="idFichier"]').val(idFichier);
	});
JAVASCRIPT
);

}

/************* USER *************/

else {
  $cmPart = <<<HTML
    <div class = "part">
      <h1 style ="text-align : left"> Cours magistraux </h2>
      <hr class="hrPart"/>
HTML;

  $tdPart = <<<HTML
    <div class = "part">
      <h2>Travaux dirigés</h2>
      <hr class="hrPart"/>
HTML;

$tpPart = <<<HTML
  <div class = "part">
    <h2>Travaux pratiques</h2>
    <hr class="hrPart"/>
HTML;

  $res = Fichier::getFichiersByModule($_GET['id']);

  foreach ($res as $f) {
    if($f->getTypeFichier() == "CM"){
      $cmPart .= <<<HTML
      <div class="cmPart">
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
    else if($f->getTypeFichier() == "TD"){
      $tdPart .= <<<HTML
      <div class="cmPart">
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
    else if($f->getTypeFichier() == "TP"){
      $tpPart .= <<<HTML
      <div class="cmPart">
        <a href="{$f->getCheminFichier()}">
          <h4>{$f->getNomFichier()}</h4>
          <p>{$f->getDescFichier()}</p>
        </a>
      </div>
      <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>");
  $p->appendContent($tpPart . "</div>\n</div>");
}

echo $p->toHTML();
