$("#addgrp").on('click', function(){
  var form = document.forms['addGrp'];
  var lib = form.title.value;
  var type = form.type.value;
  var idMod = form.idMod.value;
  
  console.log(lib + ", " +type+ ", " +idMod);

  /*$.ajax(
    method: "POST",
    url: "",
    data: {'lib': lib, 'type':type, 'idMod': idMod}

  );*/
});


$( function() {
  $( "#dateP1" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$( function() {
  $( "#dateP2" ).datepicker({ dateFormat: 'yy-mm-dd' });
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
