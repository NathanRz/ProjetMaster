<?php


include_once("php/layout.class.php");
include_once("php/autoload.include.php");


$p = new BootstrapPage("Index");
$nav = Layout::nav();
$p->setLanguage("fr");
$p->appendContent($nav);

$pass = password_hash("test",PASSWORD_BCRYPT);
$p->appendContent(<<<HTML
	
		<div class="container text-center no-reveal">
			<div class="alert">
				<a href="#">Ceci est un test</a> d'alerte !
			</div>
			<h1>Test de titre h1</h1>
			<h2>Titre h2</h2>
			<a href="#" class="fancy-button">Cliquez</a>
		</div>
		<div class="container container-mod">
			<div class="row pt-2">
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 2</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
				<div class="col-lg mt-3">
					<div class="modules">
						<div class="modules-body">
							<h4 class="modules-title">Titre 1</h4>
							<p>test dsq iudsq hgdsqid  qsd guqydgqsdu gqsudg quygdq</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

HTML
);

$p->appendContent(Layout::footer());


echo $p->toHTML();