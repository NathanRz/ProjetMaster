<?php
include_once("autoload.include.php");
include_once("mypdo.include.php");
class Layout{

	public static function nav(){
		$nav = <<<HTML
		<header>
			<nav class="navbar navbar-expand-lg bgDark">
				<div class="navbar-collapse collapse" id="navbarColors01">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item"><a class="nav-link" href="enseignements.php">Enseignements</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Bouton 2</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Bouton 3</a></li>
						<li class="nav-item"><a class="nav-link" href="#">Bouton 3</a></li>

HTML;
		if(Admin::isConnected()){
			$a = Admin::createFromSession();
			$nav .= "<li class='nav-item'>". Admin::logoutForm("Déconnexion", "logout.php") ."</li>";
		}else{
			$nav .= "<li class='nav-item'><a class='nav-link' href='formLogin.php'>Administration</a></li>";
		}

		$nav .= <<<HTML
					</ul>
				</div>
			</nav>
		</header>
HTML;

		return $nav;
	}

	public static function footer(){
		$footer = <<<HTML
			<footer class="bgDark text-center">
				<div >
					<p>Ceci est un test de footer</p>
				</div>
			</footer>
HTML;
		return $footer;
	}
}