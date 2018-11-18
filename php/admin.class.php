<?php

require_once "autoload.include.php";
require_once "mypdo.include.php";

/**
 * Classe d'Exception concernant les connexions de la Classe Utilisateur
 */
class AuthenticationException extends Exception { }

/**
 * Classe d'Exception concernant les récupération de la Classe Utilisateur dans les données de session
 */
class NotInSessionException extends Exception { }

/**
 * Classe d'Exception concernant le démarrage d'une session
 */
class SessionException extends Exception { }

/**
 * Utilisateur permettant d'effectuer des connexions client/serveur
 * Utilisateur issu de la base de données (table admin)
 */
class Admin {
    /**
     * Identifiant base de données
     * @var string $id
     */
    protected $id = null ;

    /**
     * Login
     * @var string $login
     */
    protected $username = null;


    /**
     * Clé de session à partir de laquelle les données sont stockées
     */
    const session_key = "__admin__" ;


    /**
     * Constructeur privé
     */
    private function __construct() {
    }

    //Getters
    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }
   

    /**
     * Production d'un formulaire de connexion contenant un challenge et une méthode JavaScript de hachage
     * @param string $action URL cible du formulaire
     * @param string $submitText texte du bouton d'envoi
     *
     * @return string code HTML du formulaire
     */
    static public function loginForm($action, $submitText = 'OK') {
        $texte_par_defaut = 'e-mail' ;
        // Mise en place de la session
        self::startSession() ;

        return <<<HTML
<form name='auth' action='$action' method='POST' autocomplete='off'>
    <div class="form-group">
        <label for="Login">Login:</label>
        <input class="form-control" type='text' name='login' onClick="if (this.value == '$texte_par_defaut') this.value = ''" onFocus="if (this.value == '$texte_par_defaut') this.value = ''" placeholder="Login">
    <div>
    <div class="form-group">
        <label for="Password">Mot de passe:</label>
        <input type='password' class="form-control" name='pass'  placeholder="Mot de passe">
    </div>
    <input type='submit'  class="form-control" value='{$submitText}'>
</form>
HTML;
    }

    /**
     * Validation de la connexion de l'Utilisateur
     * @param array $data tableau contenant la clé 'code' associée au condensat du login et au mot de passe
     * @throws AuthenticationException si l'authentification échoue
     *
     * @return Admin utilisateur authentifié
     */
    public static function createFromAuth(array $data) {

        // Mise en place de la session
        self::startSession() ;
        // Préparation de la requête
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT idAdmin,username,password
    FROM admin
    WHERE username = :user
SQL
    ) ;

        $stmt->execute(array(':user' => $data['login']));
        // Test de réussite de la sélection
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row !== false){
        	$validPassword = password_verify($data['pass'], $row['password']);
        	if($validPassword){
        		$stmt = myPDO::getInstance()->prepare(<<<SQL
				    SELECT idAdmin,username
				    FROM admin
				    WHERE username = :user
SQL
    ) ;
				$stmt->execute(array(':user' => $data['login']));
				$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		        if (($admin = $stmt->fetch()) !== false) {
		        	
		            return $admin ;
		        }
		        
        	}else{
        		throw new AuthenticationException("Login/pass incorrect") ;
        	}
			
        }else {

		    throw new AuthenticationException("Login/pass incorrect") ;
		}
        
    }

    /**
     * Formulaire de déconnexion de l'utilisateur
     * @param string $text texte du bouton de déconnexion
     * @param string $action URL cible du formulaire
     *
     * @return void
     */
    public static function logoutForm($text, $action) {
        $text = htmlspecialchars($text, ENT_COMPAT, 'utf-8') ;
        return <<<HTML
    <form action='$action' method='POST'>
    <button type='submit' class="btn btn-primary" name='logout'>{$text}</button>
    </form>
HTML;
    }

    /**
     * Déconnecter l'utilisateur
     *
     * @return void
     */
    public static function logoutIfRequested() {
        if (isset($_REQUEST['logout'])) {
            self::startSession() ;
            unset($_SESSION[self::session_key]) ;
        }
    }

    /**
     * Un utilisateur est-il connecté ?
     *
     * @return bool connecté ou non
     */
    static public function isConnected() {
        self::startSession() ;
        return (isset($_SESSION[self::session_key]['connected']) && $_SESSION[self::session_key]['connected']) || (isset($_SESSION[self::session_key]['admin']) && $_SESSION[self::session_key]['admin'] instanceof Admin) ;
    }

    /**
     * Sauvegarde de l'objet Utilisateur dans la session
     *
     * @return void
     */
    public function saveIntoSession() {
        // Mise en place de la session
        self::startSession() ;
        // Mémorisation de l'Utilisateur
        $_SESSION[self::session_key]['admin'] = $this ;

    }

    /**
     * Lecture de l'objet Admin dans la session
     * @throws NotInSessionException si l'objet n'est pas dans la session
     *
     * @return Admin
     */
    static public function createFromSession() {
        // Mise en place de la session
        self::startSession() ;
        // La variable de session existe ?
        if (isset($_SESSION[self::session_key]['admin'])) {
            // Lecture de la variable de session
            $u = $_SESSION[self::session_key]['admin'] ;
            // Est-ce un objet et un objet du bon type ?
            if (is_object($u) && get_class($u) == get_class()) {
                // OUI ! on le retourne
                return $u ;
            }
        }
        // NON ! exception NotInSessionException
        throw new NotInSessionException() ;
   }

   /**
    * Démarrer une session
    * @throws SessionException si la session ne peut être démarrée
    *
    * @return void
    */
    static protected function startSession($minutes = 0) {
        // Vision la plus contraignante et donc la plus fiable
        // Si les en-têtes ont deja été envoyés, c'est trop tard...
        if (headers_sent())
            throw new SessionException("Impossible de démarrer une session si les en-têtes HTTP ont été envoyés") ;
        // Si la session n'est pas demarrée, le faire
        if (!session_id()) session_start() ;
   }

}