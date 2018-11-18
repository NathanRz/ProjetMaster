<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());
$mods = Module::getModules();
$modsHtml = "<div class='container container-mod'>";
//foreach ($mods as $m) {
	$modsHtml .= <<<HTML
	<div class="row pt-2">
				<div class="col-lg mt-3">
					<form class="hidden-form" name="formMod" action="module.php" method="POST"> 
						<input type="hidden" name="modId" value="{$mods->getId()}">
						
						<div class="modules">
							<div class="modules-body">
								<h4 class="modules-title">{$mods->getLibModule()}</h4>
								<p>Description du module</p>
							</div>
						</div>
					
					</form> 
				</div>
	</div>

HTML;
//}
if(Admin::isConnected()){
	$modsHtml .= <<<HTML
	<div class="row pt-2">
				<div class="col-lg mt-3">
					<form class="hidden-form" name="formMod" action="module.php" method="POST"> 
						<input type="hidden" name="modId" value="{$mods->getId()}">
						
						<div class="modules">
							<div class="modules-body">
								<h4 class="modules-title">Ajouter un module</h4>
							</div>
						</div>
					
					</form> 
				</div>
	</div>

HTML;
}
$modsHtml .= "</div>";

$p->appendContent($modsHtml);
$p->appendContent(Layout::footer());

echo $p->toHTML();