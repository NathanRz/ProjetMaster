<?php


include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Index");
$nav = Layout::nav(0);
$p->setLanguage("fr");
$p->appendContent($nav);


$p->appendContent(<<<HTML
		<hr>
		<div class="container container-mod">
			<div class="row pt-2">
				<div class="col-lg mt-3">
					<a href="enseignements.php">
						<div class="modules">
							<div class="modules-body">
								<h4 class="modules-title">Enseignements</h4>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg mt-3">
					<a href="cv.php">
						<div class="modules">
							<div class="modules-body">
								<h4 class="modules-title">CV</h4>
							</div>
						</div>
					</a>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Recherche</h4>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Publication</h4>
						</div>
					</div>
				</div>
			</div>
		</div>


HTML
);

$p->appendContent(Layout::footer());

echo $p->toHTML();
