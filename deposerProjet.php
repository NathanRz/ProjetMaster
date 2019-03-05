<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");
session_start();
$mod = Module::getModuleById($_GET['id']);
if(isset($_SESSION['password']) && !empty($_SESSION['password']) && ($_SESSION['password']) == $mod->getPassModule() || Admin::isConnected()){
$p = new BootstrapPage("Déposer un projet");

$p->appendContent(Layout::nav(1));

$grps = GroupeProjet::getGroupePrjByMod($_GET['id']);
$options = "";
foreach ($grps as $g) {
  $options .= "<option value='" . $g->getIdGroupePrj() . "'>";
  foreach ($g->getEtudiants() as $e) {
    $options .= $e->getNom() . " " . $e->getPrenom() . " - ";
  }

  $options .="</option>";
}

$p->appendContent(<<<HTML
  <div class='container container-edit'>
      <h1>Dépôt de projet</h1>
      <form clas="md-form" name="depoFich" method="POST" action="php/addProject.php" enctype="multipart/form-data">
        <div class="row">

            <div class="col-lg mt-4">
              <select name="binom" class="browser-default custom-select" required="required">
                <option value='null' selected="true" disabled="disabled">Choisissez un groupe</option>
                {$options}
              </select>
            </div>
            <div class="col-lg mt-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Upload</span>
                </div>
                <div class="custom-file">
                  <input type="file" name="sources" accept=".zip" class="custom-file-input" id="sources" aria-describedby="inputGroupFileAddon01" required="required">
                  <label class="custom-file-label" for="inputGroupFile01">Archive</label>
                </div>
              </div>
            </div>
            <div class="col-lg mt-4">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                </div>
                <div class="custom-file">
                  <input type="file" name="rapport" accept=".pdf" class="custom-file-input" id="rapport" aria-describedby="inputGroupFileAddon01" required="required">
                  <label class="custom-file-label" for="inputGroupFile01">Rapport</label>
                </div>
              </div>
            </div>
            <input type="hidden" name="idMod" value="{$_GET['id']}">
        </div>
        <div id="rowImg" class="row rowImg mb-4">
            <div class="col-md-3 mb-1">
              <div id="addImgPrj">
                <span class="plus">+</span>
                <input type="file">
              </div>
            </div>
        </div>

        <input type="submit" id="postData" value="Envoyer">
      </form>
  </div>

HTML
);

$p->appendContent(<<<JAVASCRIPT
  <script>

  var formImage = new FormData();
  $(document).ready(function(){

    $("#addImgPrj").on('dragenter', function(e){
      e.preventDefault();
      $(this).css('background', '#BBD5B8');
    });

    $("#addImgPrj").on('dragover', function(e){
      e.preventDefault();
    })

    $("#addImgPrj").on('drop', function(e){
      $(this).css('background', "#D8F9D3");
      e.preventDefault();
      var image = e.originalEvent.dataTransfer.files;
      createFormData(image);
      var img = document.createElement("img");
      img.className ="img-fluid";
      var reader = new FileReader();
      reader.onload = function(event){
          //console.log(event);
          img.src = event.target.result;
      }
      reader.readAsDataURL(image[0]);
      var imgHold = document.createElement('div');
      imgHold.className = "col-md-3 mb-1";
      imgHold.append(img);
      $("#rowImg").prepend(imgHold);
    });
  });

  function createFormData(image){
    formImage.append('images[]', image[0]);
  }

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
    console.log(formImage);
    $.ajax({
      url: "php/addProject.php",
      type: "POST",
      data: formImage,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data){
        $("#addImgPrj").html(data);
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
  header("Location: index.php");
}
