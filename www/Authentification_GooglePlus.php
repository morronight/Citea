<?php      
require_once 'include/Configuration.php';
require_once 'include/Utilisateurs.php';
require_once 'include/Google+/Google_Client.php';
require_once 'include/Google+/contrib/Google_PlusService.php';


// ---------------------- Google ----------------------------
// Initialisation et configuration API Google+ Sign In
$client = new Google_Client(); //Création d'un objet Google_Client
$client->setApplicationName("Test");
$client->setClientId(Configuration::$Google['GooglePlus']['ClientID']); // ID de l'application Web
$client->setClientSecret(Configuration::$Google['GooglePlus']['ClientSecret']); // Clé secrète de l'application Web
$client->setRedirectUri(Configuration::$Google['GooglePlus']['url']); // url autorisée pour la redirection après la validation de l’authentification, de la forme :
 // http://monsite.fr/page-à- atteindre-après-la-redirection
$plus = new Google_PlusService($client);

if (!(isset($_SESSION['id_user_appli']))) //Si on a pas d'utilisateur autorisé déjà authentifié pour notre application
{
	if (isset($_REQUEST['error']))  //Si l'utilisateur refuse que l'application accède à ses données Google+
	{ 
		 error_log(" L’utilisateur a refusé que l’appli accède à ses données G+ ");
		 header('Location:'.Configuration::$page_authentification); //On retourne à la page d’authentification
		 exit();
	}
	
	if (isset($_GET['code'])) //Authentification de l'utilisateur grâce au code unique reçu par Google+
    { 
		$client->authenticate($_GET['code']); //On authentifie l'utilisateur chez Google+
	}
 
	if ($client->getAccessToken()) //Si l'utilisateur a un jeton d'accès
	{
		$me = $plus->people->get('me'); //On récupère les informations de l'utilisateur
		$id_google = $me['id']; //On récupère l'id de l'utilisateur
	}  
	else // l'utilisateur n'a pas de jeton d'accès
	 {
		 $authUrl = $client->createAuthUrl(); //On génère l'url qui permet d'atteindre l'interface de connexion Google+
		 header('Location:'.$authUrl); //On redirige vers l'interface de connexion Google+
		 exit();
	 }	

	if ($id_google !== null) //Google nous retourne un id Google+
    {
        $id_verif = Utilisateurs::Get(null,$id_google,null); //On vérifie que l’id Google+ de l’utilisateur est présent dans notre base
        if ($id_verif !== null) //Si il est présent, c’est un utilisateur autorisé pour notre application
        {
			$_SESSION['id_user_appli']=$id_verif; //On stocke l’id trouvé dans notre base pour cet utilisateur en session
			header('Location:'.Configuration::$page_administration); //On le redirige vers la page d'administration
			exit();
        }
        else //l’id Google+ de l’utilisateur n’est pas présent dans notre base, celui-ci n’est pas autorisé
        { 
			error_log(" L’utilisateur Google+ n’est pas présent dans notre table ") ;
			header('Location:'.Configuration::$page_authentification); //On le redirige vers la page d'authentification
			exit();
        }
    }
    else // l'id google n'est pas correct
    { 
		error_log(" L’id Google+ retourné n’est pas correct ") ;
		header('Location:'.Configuration::$page_authentification); //On le redirige vers la page d'authentification
		exit();
    }
}
 
else // On a un utilisateur connecté et autorisé pour notre application 
{
	 header('Location:'.Configuration::$page_administration); //On le redirige vers la page d'administration
	 exit();
}

?>
