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
        form_data.append('idModule', $('input[name=idModuleDrop]').val());
        form_data.append('descFile', $('input[name=descFileDrop]').val());
        form_data.append('typeFile', $('input[name=typeFileDrop]').val());
        form_data.append('file', file_obj, file_obj["name"]);
        if(file_img["length"] != 0)
          form_data.append('fileImg', file_img[0], file_img[0]["name"]);
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
              for(i =0; i < res.length;i++){
                if(res[i]["typeFichier"] == id){
                  var img="";
                  if(res[i]["cheminImg"] != null){
                    img = "<img class='imgFile' src='"+ res[i]["cheminImg"] + "' width='64' height='64' >";
                  }
                  var cmPart = "<div class='cours'>"+
                                 "<a data-id='" + res[i]["idFichier"] + "' href='#' data-toggle='modal' data-target='#modalRemove'>" +
                                   "<img class='removePng' src='img/remove.png' width='32' height ='32'>"+
                                 "</a>"+
                                 "<a href='"+ res[i]["cheminFichier"] + "'>" +
                                    img +
                                   "<h4>" + res[i]["nomFichier"] + " : </h4>" +
                                   "<p class='fileDesc'>" + res[i]["descFichier"] + "</p>" +
                                 "</a>"+
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
