<?php

include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Enseignements");
$pdo = myPDO::getInstance();

$p->appendContent(Layout::nav());

$p->appendContent(<<<HTML
	
		<div class="container text-center no-reveal">
			<p>Test</p>
		</div>
HTML
);

echo $p->toHTML();