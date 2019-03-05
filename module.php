<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");

session_start();
$mod = Module::getModuleById($_GET['id']);
if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule()){
  $p = new BootstrapPage("Enseignements");

  $p->appendContent(Layout::nav(1));



  $topDiv = "<div class ='container container-mod'>";
  $title = "<h1 style='text-align : center'>" . $mod->getLibModule() . "</h1>";

  //Must close topDiv at the end of the page
  $p->appendContent($topDiv . $title);
  $p->appendContent(<<<HTML
    <div class="buttonsModule">
      <a href="inscription.php?id={$mod->getId()}" class="fancy-button">S'inscrire</a>
      <a href="groupeProjet.php?id={$mod->getId()}" class="fancy-button">Groupes de projet</a>
      <a href="deposerProjet.php?id={$mod->getId()}" class="fancy-button">Déposer un projet</a>
    </div>
HTML
  );
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
      $img="";
      if($file->getCheminImg() != NULL){
        $img.=<<<HTML
        <img class="imgFile" src="{$file->getCheminImg()}" width="64" height="64">
HTML;
      }

      if($file->getTypeFichier() == "CM"){
        $cmPart .= <<<HTML
          <div class="cours">
            <a href = "{$file->getCheminFichier()}">
              {$img}
              <h4>{$file->getNomFichier()} : </h4>
              <p class="fileDesc">{$file->getDescFichier()}</p>
            </a>
          </div>
HTML;
      } else if($file->getTypeFichier() =="TD"){
        $tdPart .= <<<HTML
          <div class="cours">
            <a href = "{$file->getCheminFichier()}">
              {$img}
              <h4>{$file->getNomFichier()} : </h4>
              <p class="fileDesc">{$file->getDescFichier()}</p>
            </a>
          </div>

HTML;
        } else if($file->getTypeFichier() == "TP"){
            $tdPart .= <<<HTML
              <div class="cours">
                <a href = "{$file->getCheminFichier()}">
                  {$img}
                  <h4>{$file->getNomFichier()} : </h4>
                  <p>{$file->getDescFichier()}</p>
                </a>
              </div>
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
        <img class="addPng" src="img/document_add.png" width="32" height="32" alt="Ajouter un CM">
      </a>
      <hr class="hrPart"/>
HTML;

  $tdPart = <<<HTML
    <div class="drop" id="TD">
      <h2>Travaux dirigés</h2>
      <a href ="#" data-toggle="modal" data-target="#myModalTD">
        <img class="addPng" src="img/document_add.png" width="32" height="32" alt="Ajouter un TD">
      </a>
      <hr class="hrPart"/>
HTML;

  $tpPart = <<<HTML
    <div class="drop" id ="TP">
      <h2>Travaux pratiques</h2>
      <a href ="#" data-toggle="modal" data-target="#myModalTP">
        <img class="addPng" src="img/document_add.png" width="32" height="32" alt="Ajouter un TP">
      </a>
      <hr class="hrPart" />
HTML;

  $mods = Fichier::getFichiersByModule($mod->getId());

  foreach ($mods as $file){
    $img="";
    if($file->getCheminImg() != NULL){
      $img .=<<<HTML
      <img class="imgFile" src="{$file->getCheminImg()}" width="64" height="64" >
HTML;
    }
    if($file->getTypeFichier() == "CM"){
      $cmPart .= <<<HTML
        <div class="cours">
          <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
            <img class="removePng" src="img/remove.png" width="32" height="32" alt ="Supprimer ce TD">
          </a>
          <a href = "{$file->getCheminFichier()}">
            {$img}
            <h4>{$file->getNomFichier()} : </h4>
            <p class="fileDesc">{$file->getDescFichier()}</p>
          </a>
        </div>
HTML;
    } else if($file->getTypeFichier() =="TD"){
      $tdPart .= <<<HTML
        <div class="cours">
          <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
            <img class="removePng" src="img/remove.png" width="32" height="32" alt="Supprimer ce TD">
          </a>
          <a href = "{$file->getCheminFichier()}">
            {$img}
            <h4>{$file->getNomFichier()}</h4>
            <p>{$file->getDescFichier()}</p>
          </a>
        </div>

HTML;
      } else if($file->getTypeFichier() == "TP"){
        $tpPart .= <<<HTML
          <div class="cours">
            <a data-id = "{$file->getId()}" href="#" data-toggle="modal" data-target="#modalRemove">
              <img class="removePng" src="img/remove.png" width="32" height="32" alt ="Supprimer ce TP">
            </a>
            <a href = "{$file->getCheminFichier()}">
              {$img}
              <h4>{$file->getNomFichier()}</h4>
              <p class="fileDesc">{$file->getDescFichier()}</p>
            </a>
          </div>
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
              <div class="form-group">
                <label for="addedFile">Fichier:</label>
                <input class= "fancy-input" id="addedFile" name="addedFile" type="file" accept="application/pdf" />
              </div>
              <div class="form-group">
                <label for="descFile">Description:</label>
                <input class= "fancy-input" id="descFile" name="descFile" type="text" placeholder="Description du fichier" />
              </div>
              <div class="form-group">
                <label for="imgDesc">Image:</label>
                <input class= "fancy-input" id="imgDesc" name="imgDesc" type="file" accept="image/*" />
              </div>
                <input name="typeFile" type="hidden" value="CM" />
                <input name="idModule" type="hidden" value="{$mod->getId()}" />
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
              <div class="form-group">
                <label for="addedFile">Fichier:</label>
                <input class= "fancy-input" id="addedFile" name="addedFile" type="file" accept="application/pdf" />
              </div>
              <div class="form-group">
                <label for="descFile">Description:</label>
                <input class= "fancy-input" id="descFile" name="descFile" type="text" placeholder="Description du fichier" />
              </div>
              <div class="form-group">
                <label for="imgDesc">Image:</label>
                <input class= "fancy-input" id="imgDesc" name="imgDesc" type="file" accept="image/*" />
              </div>
                <input name="typeFile" type="hidden" value="TD" />
                <input name="idModule" type="hidden" value="{$mod->getId()}" />
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
              <div class="form-group">
                <label for="addedFile">Fichier:</label>
                <input class= "fancy-input" id="addedFile" name="addedFile" type="file" accept="application/pdf" />
              </div>
              <div class="form-group">
                <label for="descFile">Description:</label>
                <input class= "fancy-input" id="descFile" name="descFile" type="text" placeholder="Description du fichier" />
              </div>
              <div class="form-group">
                <label for="imgDesc">Image:</label>
                <input class= "fancy-input" id="imgDesc" name="imgDesc" type="file" accept="image/*" />
              </div>
                <input name="typeFile" type="hidden" value="TP" />
                <input name="idModule" type="hidden" value="{$mod->getId()}" />
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
          var newDiv = "<hr><div class='cours' id='added'>"+
            "<h4>"+ fileobj["name"] +"</h4>"+
            "<form id ='validateFileDrop' name ='validateFileDrop' action='php/addFile.php'>"+
              //"<div class='form-control'><img class='cancelNewFile' id='" + type + "'src='img/remove.png' width='32' height='32' alt='Confirmer l'ajout'></div>"+
              //"<img class='validateNewFile' id='"+ type +"'src='img/validate.png' width='32' height='32' alt ='Annuler l'ajout'>"+
              "<input type='hidden' name='idModuleDrop' value='{$_GET['id']}'>"+
              "<input type='hidden' name='typeFileDrop' value='"+type+"'>"+
              "<div class='row'>"+
                "<div class='col-10 offset-1'>"+
                  "<div class='form-control' style='background-color:#15162C;border:0;height:100%;'>"+
                    "<label for='imgDescDrop'>Image:</label>" +
                      "<input id='imgDescDrop' class='fancy-input' name='imgDesc' type='file' accept='image/*'>"+
                  "</div>" +
                "</div>" +
              "</div>"+
              "<div class='row'>"+
                "<div class='col-10 offset-1'>"+
                  "<div class='form-control' style='background-color:#15162C;border:0;height:100%;'>"+
                    "<label for='descFileDrop'>Descirption du fichier:</label>" +
                    "<input id='descFileDrop'class='fancy-input' type='text' name='descFileDrop' placeholder='Description du fichier'>"+
                  "</div>" +
                "</div>" +
              "</div>"+
              "<div class='row'>"+
                "<div class='col-2 offset-10'>"+
                  "<div class='form-control' style='background-color:#15162C;border:0;height:100%;'>"+
                    "<img class='cancelNewFile' id='" + type + "'src='img/remove.png' width='32' height='32' alt='Confirmer l'ajout'>"+
                    "<img class='validateNewFile' id='"+ type +"'src='img/validate.png' width='32' height='32' alt ='Annuler l'ajout'>"+
                  "</div>" +
                "</div>" +
              "</div>"+
            "</form>"+
          "</div>";
          $(".drop" + id).empty();
          $(".drop" + id).append(contentDiv);
          $(".drop" + id).append(newDiv);
          $(".drop" + id).css('width','100%');
          $(".drop" + id).css('height','100%');
        }
        cpt = 0;
      });

JAVASCRIPT
  );

  }

  //Closing the topDiv
  $p->appendContent("</div>");

  $mod = Module::getModuleById($_GET['id']);
  echo $p->toHTML();
}
