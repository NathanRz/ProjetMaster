<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");

$p = new BootstrapPage("Enseignements");

$p->appendContent(Layout::nav(1));

$mod = Module::getModuleById($_GET['id']);

$topDiv = "<div class ='container container-mod'>";
$title = "<h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";

//Must close topDiv at the end of the page
$p->appendContent($topDiv . $title);

if(!Admin::isConnected()){
  $cmPart = <<<HTML
    <div class = "drop" id ="CM">
      <h2>Cours magistraux</h2>
      <hr class="hrPart"/>
HTML;

  $tdPart = <<<HTML
    <div class="drop" id="TD">
      <h2>Travaux dirigés</h2>
      <hr class="hrPart"/>
HTML;

  $tpPart = <<<HTML
    <div class="drop" id ="TP">
      <h2>Travaux pratiques</h2>
      <hr class="hrPart" />
HTML;

  $mods = Fichier::getFichiersByModule($mod->getId());

  foreach ($mods as $file){
    if($file->getTypeFichier() == "CM"){
      $cmPart .= <<<HTML
        <div class="cours">
          <a href = "{$file->getCheminFichier()}">
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>
        <hr>
HTML;
} else if($file->getTypeFichier() =="TD"){
      $tdPart .= <<<HTML
        <div class="cours">
          <a href = "{$file->getCheminFichier()}">
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>
        <hr>

HTML;
} else if($file->getTypeFichier() == "TP"){
        $tdPart .= <<<HTML
          <div class="cours">
            <a href = "{$file->getCheminFichier()}">
              <h4>{$file->getNomFichier()}</h4>
              <p>{$file->getDescFichier()}</p>
            </a>
          </div>
          <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>");
  $p->appendContent($tpPart . "</div>");
} else {
  $p->appendJsUrl("js/file.js");
  $cmPart = <<<HTML
    <div class = "drop" id ="CM">
      <h2>Cours magistraux</h2>
      <a href ="#" data-toggle="modal" data-target="#myModalCM">
        <img src="img/document_add.png" width="32" height="32" alt="Ajouter un CM">
      </a>
      <hr class="hrPart"/>
HTML;

  $tdPart = <<<HTML
    <div class="drop" id="TD">
      <h2>Travaux dirigés</h2>
      <a href ="#" data-toggle="modal" data-target="#myModalTD">
        <img src="img/document_add.png" width="32" height="32" alt="Ajouter un TD">
      </a>
      <hr class="hrPart"/>
HTML;

  $tpPart = <<<HTML
    <div class="drop" id ="TP">
      <h2>Travaux pratiques</h2>
      <a href ="#" data-toggle="modal" data-target="#myModalTP">
        <img src="img/document_add.png" width="32" height="32" alt="Ajouter un TP">
      </a>
      <hr class="hrPart" />
HTML;

  $mods = Fichier::getFichiersByModule($mod->getId());

  foreach ($mods as $file){
    if($file->getTypeFichier() == "CM"){
      $cmPart .= <<<HTML
        <div class="cours">
          <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
            <img src = "img/remove.png" width="32" height="32" alt ="Supprimer ce TD">
          </a>
          <a href = "{$file->getCheminFichier()}">
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>
        <hr>
HTML;
    } else if($file->getTypeFichier() =="TD"){
      $tdPart .= <<<HTML
        <div class="cours">
          <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
            <img src = "img/remove.png" width="32" height="32" alt="Supprimer ce TD">
          </a>
          <a href = "{$file->getCheminFichier()}">
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>
        <hr>

HTML;
    } else if($file->getTypeFichier() == "TP"){
      $tpPart .= <<<HTML
        <div class="cours">
          <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
            <img src = "img/remove.png" width="32" height="32" alt ="Supprimer ce TP">
          </a>
          <a href = "{$file->getCheminFichier()}">
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>
        <hr>
HTML;
    }
  }
  $p->appendContent($cmPart . "</div>");
  $p->appendContent($tdPart . "</div>");
  $p->appendContent($tpPart . "</div>");

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
              <input name="idModule" type="hidden" value="{$mod->getId()}">
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
            <input name="idModule" type="hidden" value="{$mod->getId()}">
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
            <input name="idModule" type="hidden" value="{$mod->getId()}">
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

    $(".drop").on('drop', function(e) {
      var id = "#" + $(this).attr("id");
      if(e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
        e.preventDefault();
        e.stopPropagation();
        fileobj = e.originalEvent.dataTransfer.files[0];
        var type = $(this).attr("id");
        var newDiv = "<div class='cours' id='added'>"+
        "<h4>"+ fileobj["name"] +"</h4>"+
        "<form name ='validateFileDrop' action='php/addFile.php'>"+
          "<img class='cancelNewFile' id='" + type + "'src='img/remove.png' width='32' height='32' alt='Confirmer l'ajout'>"+
          "<img class='validateNewFile' id='"+ type +"'src='img/validate.png' width='32' height='32' alt ='Annuler l'ajout'>"+
          "<input type='hidden' name='idModuleDrop' value='{$_GET['id']}'>"+
          "<input type='hidden' name='typeFileDrop' value='"+type+"'>"+
          "<input type='text' name='descFileDrop' placeholder='Description du fichier'>";
          "</form>"+
        "</div>";
        $(".drop" + id).empty();
        $(".drop" + id).css('width','');
        $(".drop" + id).css('height','');
        $(".drop" + id).append(contentDiv);
        $(".drop" + id).append(newDiv);
      }
      cpt = 0;
    });

JAVASCRIPT
);

}

//Closing the topDiv
$p->appendContent("</div>");

$mod = Module::getModuleById($_GET['id']);
$p->appendContent(<<<HTML

    <ul>
      <li><a href="inscription.php?id={$mod->getId()}">S'inscrire</a></li>
      <li><a href="groupeProjet.php?id={$mod->getId()}">Groupes de projet</a></li>
      <li><a href="deposerProjet.php?id={$mod->getId()}">Déposer un projet</a></li>
    </ul>



HTML
);

echo $p->toHTML();
