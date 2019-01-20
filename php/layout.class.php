<?php
include_once("autoload.include.php");
include_once("mypdo.include.php");
class Layout{

	public static function nav($id){
		$index = "";
		$enseignement = "";
		switch ($id) {
			case 0:
				$index = "<a class='active' href='index.php'>Accueil</a></li>";
				$enseignement = "<a href='enseignements.php'>Enseignements</a></li>";
				break;
			case 1:
				$index = "<a href='index.php'>Accueil</a></li>";
				$enseignement = "<a class='active' href='enseignements.php'>Enseignements</a></li>";
				break;
			default :
				$index = "<a href='index.php'>Accueil</a></li>";
				$enseignement = "<a href='enseignements.php'>Enseignements</a></li>";
				break;
		}
		$nav = <<<HTML
		<header>
			<nav class="topnav" id="navbarColors01">
				<div class="links">
					{$index}
					{$enseignement}
HTML;
		if(Admin::isConnected()){
			$a = Admin::createFromSession();
			$nav .= Admin::logoutForm("Déconnexion", "logout.php");
		}else{
			if($id == 2)
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
			<footer class="text-center">
				<div >
					<p>Footer</p>
				</div>
			</footer>
HTML;
		return $footer;
	}
}
