<?php
include_once("autoload.include.php");
include_once("mypdo.include.php");
class Layout{

	public static function nav($id){
		$index = "";
		$enseignement = "";
		$cv = "";
		switch ($id) {
			case 0:
				$index = "<a class='nav-link' href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='nav-link' href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a class='nav-link' href='cv.php'>CV</a></li>";
				break;
			case 1:
				$index = "<a class='nav-link' href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='nav-link' href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a class='nav-link' href='cv.php'>CV</a></li>";
				break;
			case 2:
				$index = "<a class='nav-link' href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='nav-link' href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a class='nav-link' href='cv.php'>CV</a></li>";
				break;
			default :
				$index = "<a class='nav-link' href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='nav-link' href='enseignements.php'>Enseignements</a></li>";
				$cv = "<a class='nav-link' href='cv.php'>CV</a></li>";
				break;
		}
		$nav = <<<HTML
		<header>
			<div class="topnav navbar navbar-expand-sm navbar-dark">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				    <span class="navbar-toggler-icon"></span>
				  </button>
					<div class="collapse navbar-collapse" id="collapsibleNavbar">
						<ul class="navbar-nav">
							<li class="nav-item">
								{$index}
							</li>
							<li class="nav-item">
								{$enseignement}
							</li>
							<li class="nav-item">
								{$cv}
							</li>
HTML;
		if(Admin::isConnected()){
			$a = Admin::createFromSession();
			$nav .= "<li class='nav-item'><a class='nav-link' href='administration.php'>Administration</a></li>";
			$nav .= "<li class='nav-item'>" . Admin::logoutForm("Déconnexion", "logout.php") . "</li>";
			$nav .= "<li class='nav-item'><a class='nav-link' href='#' data-toggle='modal' data-target='#passModal'><img src='img/gear.png' style='color:white' width='15'/></a></li>";
		}else{
			if($id == 3)
				$nav .= "<li class='nav-item'><a class='nav-link' href='formLogin.php'>Administration</a></li>";
			else
				$nav .= "<li class='nav-item'><a class='nav-link' href='formLogin.php'>Administration</a></li>";
		}

		$nav .= <<<HTML
						</ul>
					</div>
				</div>
			</div>
			<div class="modal" id="passModal">
				<div class="modal-dialog">
					<div class="modal-content">

						<!-- Modal Header -->
						<div class="modal-header">
							<h4 class="modal-title">Changer de mot de passe</h4>
						</div>

						<form name="addMod" method="POST" action="php/changePass.php" enctype="multipart/form-data">
							<!-- Modal body -->
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
						       		<label for="lib">Nouveau mot de passe:</label>
											<input class="fancy-input" type="password" name="pass">
										</div>
									</div>
									<div class="col-md-12">
										<input class="fancy-button" type="submit">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</header>
HTML;

		return $nav;
	}

	public static function footer(){
		$footer = <<<HTML
		<footer class="font-small teal pt-4">
			<div class="container-fluid text-center text-md-left">
				<div class="row">
					<div class="col-md-6 mt-3">
						<h5 class="text-uppercase font-weight-bold">Coordonnées</h5>
						<p>Laboratoire Electronique Informatique et Image (LE2I), UMR CNRS 5158</p>
						<p>Aile Sciences de l'Ingénieur, Bureau G208</p>
						<p>BP 47870, 21078 Dijon Cedex, France</p>
					</div>
					<div class="col-md-6 mb-3">
						<h5 class="text-uppercase font-weight-bold">Contact</h5>
						<p>Télephone : (+33) 3.80.39.69.87</p>
						<a href="mailto:sandrine.lanquetin@u-bourgogne.fr">sandrine.lanquetin@u-bourgogne.fr</a>
					</div>
				</div>
			</div>
		</footer>
HTML;
		return $footer;
	}
}
