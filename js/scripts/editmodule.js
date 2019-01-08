$("#addgrp").on('click', function(){
  var form = document.forms['addGrp'];
  var lib = form.title.value;
  var type = form.type.value;
  var idMod = form.idMod.value;
  var horaire = form.horaire.value;

  console.log(lib + ", " +type+ ", " +idMod+ ", " +horaire);

  $.ajax({
    method: "POST",
    url: "php/addGrp.php",
    data: {lib: lib, type:type, idMod: idMod, horaire: horaire},
    datatype: 'json',
    success: function(res, statut){
      var html = "";
      var res2 = JSON.parse(res);
      var tGrp;
      console.log(res2);
      for(var i = 0;i < res2.length; i++){
        html +="<tr><td>"+res2[i].libGroupe+"</td><td>";
        if(res2[i].typeGroupe == 1){
          tGrp = "TD";
        }else{
          tGrp = "TP";
        }

        html += tGrp+ "</td><td>"+res2[i].horaireDeb+"</td><td><a href='php/gestionGrp?id="+res2[i].idGroupe + "&idMod="+res2[i].idModule+"'>Supprimer</a></td></tr>";

      }
      $("#grps").html(html);
    }
  });
});


$( function() {
  $( "#dateP1" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$( function() {
  $( "#dateP2" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$(function(){
  $("#upload_link").on("click", function(e){
    e.preventDefault();
    $("#upload:hidden").trigger('click');
  })
});
/*var form = $('.edit');
var box = $('.box');
var droppedFile = false;
box.on('drag dragstart dragend dragover dragenter dragleave drop', function(e){
  e.preventDefault();
  e.stopPropagation();
})
.on('dragover dragenter',function(){
  box.addClass('is-dragover');
})
.on('dragleave dragend drop', function(){
  box.removeClass('is-dragover');
})
.on('drop', function(e){
  droppedFile = e.originalEvent.dataTransfert.files;
});

var valid = $('.fancy-button');
valid.on('click',function(){
  var formData = new FormData(form);
  formData.append(form.get(0));
  console.log(formData);
})
form.on('submit', function(e){
  if(form.hasClass('is-uploading')) return false;

  form.addClass('is-uploading').removeClass('is-error');

  //ajax

  e.preventDefault();
  var formData = new FormData(form);
  console.log(formData);
  if(droppedFile){
    formData.append($.input.attr(''))
  }

})*/
