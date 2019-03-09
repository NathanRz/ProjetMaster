var contentDiv ="";
var cpt=0;
var fileobj;

$(".drop").on('dragenter', function(e) {
  e.preventDefault();
  e.stopPropagation();
  var id = "#" + $(this).attr("id");
  if(cpt == 0)
    contentDiv = $(".drop" + id).html();

  var width = $(".drop" + id).width();
  var height = $(".drop" + id).height();

  $(".drop" + id).empty()
  $(".drop" + id).width(width);
  $(".drop" + id).height(height);
  $(".drop" + id).css('border', '3px dashed red');
  $(".drop" + id).append("<p style='text-align:center '>Déposez un fichier</p>");
  cpt++;
});

$(".drop").on('dragover', function(e) {
  e.preventDefault();
  e.stopPropagation();
});

$(".drop").on('dragleave', function(e) {
  e.preventDefault();
  e.stopPropagation();
  var id = "#" + $(this).attr("id");
  $('.drop' + id).empty();
  $('.drop' + id).append(contentDiv);
  $('.drop' + id).css('border', '');
  cpt--;
});

function ajax_file_upload(file_obj, id) {
    if(file_obj != undefined) {
        var form_data = new FormData();
        var file_img = $("#imgDescDrop").prop('files');
        var file_src = $("#srcFileDrop").prop('files');
        form_data.append('idModule', $('input[name=idModuleDrop]').val());
        form_data.append('descFile', $('input[name=descFileDrop]').val());
        form_data.append('typeFile', $('input[name=typeFileDrop]').val());
        form_data.append('file', file_obj, file_obj["name"]);
        if(file_img["length"] != 0)
          form_data.append('imgDesc', file_img[0], file_img[0]["name"]);
        if(file_src["length"] != 0)
          form_data.append('srcFile', file_src[0], file_src [0]["name"]);
        $.ajax({
            type: 'POST',
            url: 'php/addFileDrop.php',
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
              $(".drop#" + id).css('border', '');
              $('.drop#' + id).empty();
              var res = JSON.parse(response);
              var title ="";
              switch(id){
                case "CM" :
                  title="<h2>Cours magistraux</h2>\n<hr class='hrPart'/>";
                  break;
                case "TD" :
                  title = "<h2>Travaux dirigés</h2>\n<hr class='hrPart'/>";
                  break;
                case "TP" :
                  title = "<h2>Travaux pratiques</h2>\n<hr class='hrPart'/>";
                  break;
              }
              title += "<a href='#' data-toggle='modal' data-target='#myModal" +id+"'>" +
                          "<img class='addPng' src='img/document_add.png' width='32' height='32' alt='Ajouter un" + id + "'>"+
                        "</a>";

              $(".drop#" + id).append(title);
              console.log(res[0]);
              for(i =0; i < res.length;i++){
                if(res[i]["typeFichier"] == id){
                  var img="";
                  var src="";
                  if(res[i]["cheminImg"] != null){
                    img = "<img class='imgFile' src='"+ res[i]["cheminImg"] + "' width='64' height='64' >";
                  }
                  if(res[i]["cheminSource"] != null){
                    var nomSrc = res[i]['cheminSource'].split('/').reverse()[0];
                    nomSrc = nomSrc.split('.').slice(0, -1).join('.');
                    src = "<p class='fileDesc rowCours'>" +
                            "<img class='pr-1' src='img/logo-pdf.png' height='32' width='32' />" +
                            nomSrc +
                          "</p>";
                  }
                  var cmPart =  "<div class='cours'>"+
                                  "<div class='row mb-2'>" +
                                    "<div class='col-md-1'>" +
                                      img +
                                    "</div>" +
                                    "<div class='col-md-5'>" +
                                      "<a href='"+ res[i]["cheminFichier"] + "'>" +
                                        "<p class='fileDesc rowCours'>"+
                                          "<img class='pr-1' src='img/logo-pdf.png' height='32' width='32' />" +
                                          res[i]["descFichier"] +
                                        "</p>" +
                                      "</a>"+
                                    "</div>" +
                                    "<div class='col-md-5'>"+
                                      "<a href='"+ res[i]['cheminSource']+"'>"+
                                        src +
                                      "</a>"+
                                    "</div>"+
                                    "<div class='col-1 text-center'>"+
                                      "<a data-id = '{$file->getId()}' href='#' data-toggle='modal' data-target='#modalRemove'>"+
                                        "<img class='removePng' src='img/remove.png' width='32' height='32' alt ='Supprimer ce CM'>"+
                                      "</a>"+
                                    "</div>"+
                                  "</div>"+
                                "</div>";
                  $(".drop#" + id).append(cmPart);
                }
              }
            },
        });
    }
}

$(document).on("click", 'img.validateNewFile', function(e){
  var id = $(this).attr("id");
  ajax_file_upload(fileobj, id);
});

$(document).on("click", 'img.cancelNewFile', function(e){
  var id = $(this).attr("id");
  $('.drop#' + id + ' .cours#added').remove();

  $(".drop").css('border','');
});
