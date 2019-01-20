<?php
include_once("autoload.include.php");
include_once("mypdo.include.php");
class Layout{

	public static function nav(){
		$nav = <<<HTML
		<header>
			<nav class="topnav" id="navbarColors01">
				<div class="links">
					<a class="active" href="index.php">Accueil</a></li>
					<a href="enseignements.php">Enseignements</a></li>

HTML;
		if(Admin::isConnected()){
			$a = Admin::createFromSession();
			$nav .= Admin::logoutForm("Déconnexion", "logout.php");
		}else{
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
			<footer class="text-center">
				<div >
					<p>Footer</p>
				</div>
			</footer>
HTML;
		return $footer;
	}
}
