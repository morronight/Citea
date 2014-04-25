<?php 
require_once 'include/Configuration.php';
require_once 'include/Facebook/facebook.php';
require_once 'include/Utilisateurs.php';


if ($_SESSION['id_user_appli'] === null)
{
header('Location:'.Configuration::$page_authentification); //On retourne à la page d'authentification
exit();
}

$action=null;
	if (isset($_REQUEST['action']))
		$action=htmlentities($_REQUEST['action']);
	
	
	switch(strtolower($action))
	{	
		case 'get_token':
				$token = uniqid();
				$_SESSION['Token']=$token;
				$_SESSION['Token_time'] = time();
				echo $token;
				exit();
			break;
		default:
			break;
	}

if (isset($_REQUEST['logout'])) //Déconnexion
{
	$Url = $_SESSION['logout_url'];
	$_SESSION = array();
	if (ini_get("session.use_cookies")) 
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	session_destroy();
	if ($Url !== null)
	{
	header('Location:'.$Url); //On redirige vers l'url de déconnexion de Facebook
	exit();
	}
	else
	{
	header('Location:'.Configuration::$page_authentification); //On retourne à la page d'authentification
	exit();
	}
}


else // On a un utilisateur connecté et autorisé pour notre application
{

 ?>
<html>
<head><meta charset="utf-8"> 
<link rel="stylesheet" href="css/interCitea_1.4.css" type="text/css">
<script language="Javascript" src="javascript/administration_1.4.js"> </script>
</head>
<body>

<body>
<img style="margin-left:40%" src="MonRovaltain.png" /></br>
<h1 class="titre1"> Page d'Administration </h1>


<h1> Authentifié </h1>
<br><a href=<?php echo Configuration::$page_modif ?> > Administration du contenu </a>
<br><a href="?logout"> Déconnexion </a>

</body>	
</html>
<?php 
}
?>