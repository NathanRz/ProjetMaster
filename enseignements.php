<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav(1));
$mods = Module::getModules();
$modsHtml = "<div class='container container-mod'>\n<h1 style='text-align:center'>Liste des modules</h1>\n<div class='row pt-2'>";
$c = 0;
$gestion ="";
$imgMod = "";

foreach ($mods as $m) {
	if(Admin::isConnected()){
		$gestion = <<<HTML

			<ul class="modGest">
				<li>
					<a data-id="{$m->getId()}" href="#" data-toggle="modal" data-target="#removeModal">
						<img src="img/remove.png" width="16" height="16" alt="Supprimer un module">
					</a>
				</li>
				<li>
					<a href="editModule.php?id={$m->getId()}">
						<img src="img/edit.svg" width="20" height="20" alt="Editer un module">
					</a>
				</li>
			</ul>
HTML;
	}
	if($m->getImgMod() !== null)
		$imgMod = "<img class='imgMod' src='".$m->getImgMod()."' width='50' height='50' />";
	else
		$imgMod = "";

	if($c < 3){
		$modsHtml .= <<<HTML

			<div class="col-lg mt-3">
					<div class="modules">
						{$gestion}
						<a href="acces.php?id={$m->getId()}">
							{$imgMod}
							<div class="modules-body">
								<h4 class="modules-title">{$m->getLibModule()}</h4>
								<p>{$m->getDesc()}</p>
							</div>
						</a>
					</div>
			</div>

HTML;
		$c++;
	}else{
		$modsHtml .= <<<HTML
			</div>
			<div class="row pt-2">
				<div class="col-lg mt-3">
						<div class="modules">
							{$gestion}
							<a href="module.php?id={$m->getId()}">
								{$imgMod}
								<div class="modules-body">

									<h4 class="modules-title">{$m->getLibModule()}</h4>
									<p>{$m->getDesc()}</p>
								</div>
							</a>
						</div>
				</div>


HTML;
		$c = 0;
		$c++;
	}
}

if(Admin::isConnected()){
	if($c < 3){
		$modsHtml .= <<<HTML
			<div class="col-lg mt-3">
				<div class="modules">
					<a href="#" data-toggle="modal" data-target="#myModal">
						<div class="modimg-top">
							<img src="img/add.svg" width="50" height="50" alt="Ajouter un module">
						</div>
						<div class="modules-body">
							<h4 class="modules-title">Ajouter un module</h4>
						</div>
					</a>
				</div>
			</div>
		</div>
HTML;

	}else{

		$modsHtml .= <<<HTML
			</div>
			<div class="row">
				<div class="col-lg mt-3">
					<div class="modules">
					<a href="#" data-toggle="modal" data-target="#myModal">
						<div class="modimg-top">
							<img src="img/add.svg" width="50" height="50" alt="Ajouter un module">
						</div>
						<div class="modules-body">
							<h4 class="modules-title">Ajouter un module</h4>
						</div>
					</a>
					</div>
				</div>
			</div>
HTML;
	}

}


$p->appendContent($modsHtml . "</div>");
$p->appendContent(<<<HTML

	<div class="modal" id="myModal">
	  <div class="modal-dialog">
	    <div class="modal-content">

	      <!-- Modal Header -->
	      <div class="modal-header">
	        <h4 class="modal-title">Ajout d'un module</h4>
	      </div>

	      <form name="addMod" method="POST" action="php/addModule.php" enctype="multipart/form-data">
		      <!-- Modal body -->
		      <div class="modal-body">
						<div class="row">
							<div class="col-5">
				       	<label for="lib">Libellé:</label>
							</div>
							<div class="col-7">
				       	<input class="fancy-input" type="text" name="lib">
			       	</div>
						</div>
						<div class="row">
							<div class="col-5">
				       	<label for="lib">Description:</label>
							</div>
							<div class="col-7">
				       	<input class="fancy-input" type="text" name="desc">
			       	</div>
						</div>
						<div class="row">
							<div class="col-5">
				       	<label for="img">Image:</label>
							</div>
							<div class="col-7">
								<input class="fancy-input" type="file" name="img" accept=".jpg, .jpeg, .png, .gif, .svg">
			       	</div>
						</div>
						<div class="row">
							<div class="col-5">
				       	<label for="lib">Mot de passe:</label>
							</div>
							<div class="col-7">
								<input class="fancy-input" type="text" name="mdp">
			       	</div>
						</div>
						<div class="row">
							<div class="col-5">
								<label for="startDate">Date d'ouverture : </label>
							</div>
							<div class="col-7">
								<input id="dateP1" class="fancy-input" type="text" name="startDate">
							</div>
						</div>
						<div class="row">
							<div class="col-5">
								<label for="endDate">Date de fermeture : </label>
							</div>
							<div class="col-7">
								<input id="dateP2" class="fancy-input" type="text" name="endDate">
							</div>
						</div>
						<div class="row">
							<div class="col-5">
								<label for="duree">Duree du projet : </label>
							</div>
							<div class="col-7">
								<input id="dateP3" class="fancy-input" type="time" name="duree">
							</div>
						</div>
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

	<div class="modal" id="removeModal">
	  <div class="modal-dialog">
	    <div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Etes-vous sûr ?</h4>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<form name="delMod" method="POST" action="php/removeModule.php">
						<input type="hidden" name="idMod" value="">
						<button type="submit" class="btn btn-success">Valider</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
					</form>
				</div>
	  	</div>
	  </div>
	</div>
HTML
);

$p->appendJs(<<<JAVASCRIPT
	$('#removeModal').on('show.bs.modal',function(e){
		var idMod = $(e.relatedTarget).data('id');
		$(e.currentTarget).find('input[name="idMod"]').val(idMod);
	});

	$( function() {
		$( "#dateP1" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});

	$( function() {
		$( "#dateP2" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});

JAVASCRIPT
);

$p->appendContent("</div>");
$p->appendContent(Layout::footer());

echo $p->toHTML();
