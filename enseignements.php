<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());
$mods = Module::getModules();
$modsHtml = "<div class='container container-mod'>\n<h1 style='text-align:center'>Liste des modules</h1>\n<div class='row pt-2'>";
$c = 0;
$gestion ="";


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
					<a href="#" data-toggle="modal" data-target="#myModal">
						<img src="img/edit.svg" width="20" height="20" alt="Supprimer un module">
					</a>
				</li>
			</ul>		
HTML;
	}

	if($c < 3){
		$modsHtml .= <<<HTML
		
					<div class="col-lg mt-3">
							<div class="modules">
								{$gestion}
								<a href="module.php?id={$m->getId()}">
									<img src="{$m->getImgMod()}">
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
								<img src="{$m->getImgMod()}">
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
	      	<div class="form-group">
		       	<label for="lib">Libellé:</label>
		       	<input class="fancy-input" type="text" name="lib">
	       	</div>
	       	<div class="form-group">
		       	<label for="lib">Description:</label>
		       	<input class="fancy-input" type="text" name="desc">
	       	</div>
	       	<div class="form-group">
		       	<label for="img">Image:</label>
		       	<input class="fancy-input" type="file" name="img">
	       	</div>
	       	<div class="form-group">
		       	<label for="lib">Mot de passe:</label>
		       	<input class="fancy-input" type="text" name="mdp">
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
JAVASCRIPT
);
$p->appendContent(Layout::footer());

echo $p->toHTML();