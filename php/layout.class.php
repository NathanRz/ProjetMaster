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
				$index = "<a class='active' href='index.php'>Accueil</a></li>";
				$enseignement = "<a href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a href='cv.php'>CV</a></li>";
				break;
			case 1:
				$index = "<a href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='active' href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a href='cv.php'>CV</a></li>";
				break;
			case 2:
				$index = "<a href='index.php'>Accueil</a></li>";
				$enseignement = "<a href='enseignements.php'>Enseignements</a></li>";
				$cv= "<a  class='active' href='cv.php'>CV</a></li>";
				break;
			default :
				$index = "<a href='index.php'>Accueil</a></li>";
				$enseignement = "<a href='enseignements.php'>Enseignements</a></li>";
				$cv = "<a href='cv.php'>CV</a></li>";
				break;
		}
		$nav = <<<HTML
		<header>
			<nav class="topnav" id="navbarColors01">
				<div class="links">
					{$index}
					{$enseignement}
					{$cv}
HTML;
		if(Admin::isConnected()){
			$a = Admin::createFromSession();
			$nav .= Admin::logoutForm("Déconnexion", "logout.php");
		}else{
			if($id == 3)
				$nav .= "<a class='active' href='formLogin.php'>Administration</a>";
			else
				$nav .= "<a href='formLogin.php'>Administration</a>";
		}

		$nav .= <<<HTML
				</div>
			</nav>
		</header>
HTML;

		return $nav;
	}

	public static function footer(){
		$footer = <<<HTML
		<footer class="font-small teal pt-4">
			<div class="container-fluid text-center text-md-left">
				<div class="row">
					<div class="col-md-6 mt-md-0 mt-3">
						<h5 class="text-uppercase font-weight-bold">Coordonnées</h5>
						<p>Laboratoire Electronique Informatique et Image (LE2I), UMR CNRS 5158</p>
						<p>Aile Sciences de l'Ingénieur, Bureau G208</p>
						<p>BP 47870, 21078 Dijon Cedex, France</p>
					</div>
					<div class="col-md-6 mb-md-0 mb-3">
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
