<?php
include_once("php/layout.class.php");
include_once("php/autoload.include.php");

if($_SERVER["REQUEST_METHOD"] == "GET"){
  $mod = Module::getModuleById($_GET['id']);
  $p = new BootstrapPage("Accès au module");

  $p->appendContent(Layout::nav(1));
  $p->appendContent(<<<HTML
    <div class="container container-mod">
      <div id="alert-verif-success" class="alert alert-success" role="alert">Mot de passe correct. Vous allez être redirigé vers la page du module.</div>
      <div class="row">
        <div class="col-lg mt-4">
        </div>
        <div class="col-lg mt-4">
          <div class="modules">
            <div class="modules-body">
              <h4 class="modules-title">Mot de passe du module</h4>
              <form name="access" onsubmit="verif(event);" method="POST" action="php/verifPass.php">
                <input class="fancy-input" type="password" name="pass">
                <input type="hidden" name="idMod" value="{$mod->getId()}">
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg mt-4">
        </div>
      </div>
    </div>
HTML
);

  $p->appendContent(Layout::footer());
  $p->appendContent(<<<HTML
    <script type="text/javascript">
    function verif(e){
      e.preventDefault();
      var formAcc = document.forms['access'];
      var id = formAcc.idMod.value;
      var pass = formAcc.pass.value;

      console.log('Valeurs: ' + id + ', ' + pass);

      $.ajax({
        method: "POST",
        url: "php/verifPass.php",
        data: {idMod:id, password: pass},
        datatype: 'json',
        success: function(res, statut){
          var r = JSON.parse(res);
          if(r == 1){
            setTimeout(function(){ window.location = "./module.php?id="+id; }, 5000);
            $("#alert-verif-success").fadeIn("slow");
          }
          //console.log("Accès = " + res);
        }
      });
    }

    </script>
HTML
);

  echo $p->toHTML();


}
