<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());
$mods = Module::getModules();
$modsHtml = "<div class='container container-mod'>\n<div class='row pt-2'>";
$c = 0;
foreach ($mods as $m) {

	if($c < 3){
		$modsHtml .= <<<HTML
		
					<div class="col-lg mt-3">
						<form class="hidden-form" name="formMod" action="module.php" method="POST"> 
							<input type="hidden" name="modId" value="{$m->getId()}">
							
							<div class="modules">
								<div class="modules-body">
									<h4 class="modules-title">{$m->getLibModule()}</h4>
									<p>{$m->getDesc()}</p>
								</div>
							</div>
						
						</form> 
					</div>

HTML;
		$c++;
	}else{
		$modsHtml .= <<<HTML
			</div>
			<div class="row pt-2">
				<div class="col-lg mt-3">
					<form class="hidden-form" name="formMod" action="module.php" method="POST"> 
						<input type="hidden" name="modId" value="{$m->getId()}">
						
						<div class="modules">
							<div class="modules-body">
								<h4 class="modules-title">{$m->getLibModule()}</h4>
								<p>{$m->getDesc()}</p>
							</div>
						</div>
					
					</form> 
				</div>
			

HTML;
		$c = 0;
		$c++;
	}
}

$modsHtml .= "</div>";
if(Admin::isConnected()){
	if($c < 3){
		$modsHtml .= <<<HTML
			<div class="col-lg mt-3">
				<div class="modules">
					<div class="modules-body">
						<h4 class="modules-title">Ajouter un module</h4>
					</div>
				</div>
			</div>
HTML;

	}else{
		$modsHtml .= <<<HTML
			<div class="row">
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Ajouter un module</h4>
						</div>
					</div>
				</div>
			</div>
HTML;
	}
	
}


$p->appendContent($modsHtml . "</div>");
$p->appendContent(Layout::footer());

echo $p->toHTML();