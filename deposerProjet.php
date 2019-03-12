<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");
session_start();
if(isset($_GET['id']) && !empty($_GET['id'])){

  $mod = Module::getModuleById($_GET['id']);
  $p = new BootstrapPage("Déposer un projet");
  $p->appendContent(Layout::nav(1));
  $now = time();
  $date = strtotime($mod->getStartDate());

  if($now > $date){
    if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){

      $grps = GroupeProjet::getGroupePrjByModProj($_GET['id']);
      $options = "";

      foreach ($grps as $g) {
        $options .= "<option value='" . $g->getIdGroupePrj() . "'>";
        $etuArr = $g->getEtudiants();
        for($i = 0; $i < sizeof($etuArr); $i++) {
          if($i != sizeof($etuArr)-1)
            $options .= $etuArr[$i]->getNom() . " " . $etuArr[$i]->getPrenom() . " - ";
          else
            $options .= $etuArr[$i]->getNom() . " " . $etuArr[$i]->getPrenom();
        }

        $options .="</option>";
      }

      $p->appendContent(<<<HTML
        <div class='container container-edit'>
            <div id="alertPrj">
            </div>
            <h1>Dépôt de projet</h1>
            <form id="formDepot" clas="md-form" name="depoFich" method="POST" action="php/addProject.php" enctype="multipart/form-data">
              <div class="row">

                  <div class="col-lg mt-4">
                    <select name="binom" class="browser-default custom-select" required="required">
                      <option value='null' selected="true" disabled="disabled">Choisissez un groupe</option>
                      {$options}
                    </select>
                  </div>
                  <div class="col-lg mt-4">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="sources" accept=".zip" class="custom-file-input" id="sources" required="required">
                        <label class="custom-file-label" for="sources">Sources</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg mt-4">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="rapport" accept=".pdf" class="custom-file-input" id="rapport" required="required">
                        <label class="custom-file-label" for="rapport">Rapport</label>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="idMod" value="{$_GET['id']}">
              </div>

              <div id="dropBox" class="col-12 mt-4 text-center">
                <label for="img"><a href="" id="upload_link"><strong>Choisissez un fichier</strong></a><span> ou deplacez le ici</span>.</label>
                <input type="file" id="upload" name="img">
              </div>

              <div id="rowImg" class="row rowImg mb-4 ">
              </div>

              <input class="fancy-button" type="submit" id="postData" value="Envoyer">
            </form>
        </div>

HTML
    );

      $p->appendContent(<<<JAVASCRIPT
        <script>

        var formImage = new FormData();
        $(document).ready(function(){

          $('#sources').on('change',function(e){
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);
          });

          $('#rapport').on('change',function(e){
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);
          });

          $("#dropBox").on('dragenter', function(e){
            e.preventDefault();
            $(this).css('background', '#BBD5B8');
          });

          $("#dropBox").on('dragover', function(e){
            e.preventDefault();
          });

          $("#dropBox").on('dragleave', function(e){
            e.preventDefault();
            $(this).css('background', '');
          });

          $("#upload_link").on("click", function(e){
              e.preventDefault();
              $("#upload").trigger('click');
          });

          $("#upload").on('change', function(e){
            e.preventDefault();
            var image = document.forms['depoFich'].img.files;
            console.log(image);
            createFormData(image);
            var img = document.createElement("img");
            img.className ="img-fluid imgPrj";
            var reader = new FileReader();
            reader.onload = function(event){
                //console.log(event);
                img.src = event.target.result;
            }
            reader.readAsDataURL(image[0]);
            var imgHold = document.createElement('div');
            imgHold.className = "col-md-3 mb-2";
            imgHold.append(img);
            $("#rowImg").prepend(imgHold);
          });

          $("#dropBox").on('drop', function(e){
            $(this).css('background', "");
            e.preventDefault();
            var image = e.originalEvent.dataTransfer.files;
            console.log(image);
            createFormData(image);
            var img = document.createElement("img");
            img.className ="img-fluid imgPrj";
            var reader = new FileReader();
            reader.onload = function(event){
                //console.log(event);
                img.src = event.target.result;
            }
            reader.readAsDataURL(image[0]);
            var imgHold = document.createElement('div');
            imgHold.className = "col-md-3 mb-2";
            imgHold.append(img);
            $("#rowImg").prepend(imgHold);
          });
        });

        function createFormData(image){
          formImage.append('images[]', image[0]);
        }

        $(function(){
          $("#upload_link").on("click", function(e){
            e.preventDefault();
            $("#upload:hidden").trigger('click');
          })
        });

        $("#sources").on('input', function(e){
          formImage.append('sources', e.currentTarget.files[0]);
        })

        $("#rapport").on('input', function(e){
          formImage.append('rapport', e.currentTarget.files[0]);
        })

        function uploadFormData(formData){
          var formU = document.forms['depoFich'];
            formImage.append('binom', formU.binom.value);
            formImage.append('idMod', formU.idMod.value);
          //console.log(formImage);
          $.ajax({
            url: "php/addProject.php",
            type: "POST",
            data: formImage,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
              $("#alertPrj").append("<div class='alert alert-success'>Projet déposé avec <strong>succès</strong>, vous allez être redirigé vers la liste des groupes de projets</div>");
              setTimeout(function(){ window.location = "./groupeProjet.php?id={$mod->getId()}"; }, 3000);
            }
          });
        }

        $("#postData").on('click', function(e){
          e.preventDefault();
          uploadFormData();
        });
        </script>
JAVASCRIPT
      );

      $p->appendContent(Layout::footer());

      echo $p->toHTML();
    }else{
      header('Location: index.php');
    }
  }else{
    $d = date('d-m-Y', $date);
    $p->appendContent(<<<HTML
      <div class='container container-edit'>
        <div class="alert alert-warning">
          <span>Vous ne pouvez pas encore déposer de prôjet pour le moment. La date d'ouverture est fixé au <strong>{$d}</strong></span>
        </div>
      </div>
HTML
    );
    echo $p->toHTML();
  }
}
