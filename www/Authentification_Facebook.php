<?php      
require_once 'include/Configuration.php';
require_once 'include/Utilisateurs.php';
require_once 'include/Facebook/facebook.php';

// ---------------------- Facebook --------------------------
$facebook = new Facebook(array(
		'appId' => Configuration::$Facebook['ClientID'],
		'secret' => Configuration::$Facebook['APISecret'],
		'cookie' => true
));
$params = array( 'next' => Configuration::$page_authentification);
if (!(isset($_SESSION['id_user_appli'])))
{
	$user_fb = $facebook->getUser(); //On récupère l'utilisateur
	if ($user_fb) // On a un utilisateur connecté
	{
		$id_fb = $user_fb;
		$id_verif = Utilisateurs::Get(null,null,$id_fb);
			if ($id_verif !== null)
			{
			$_SESSION['logout_url'] = $facebook->getLogoutUrl($params); //URL de déconnexion
			$_SESSION['id_user_appli']=$id_verif;
			header('Location:'.Configuration::$page_administration);
			exit();
			}
			else
			{
			header('Location:'.Configuration::$page_authentification);
			exit();
			}
	}
	else //On a pas d'utilisateur connecté
	{
		$Url = $facebook->getLoginUrl();
		header('Location:'.$Url); //On propose à l'utilisateur de se connecter
		exit();
	}
}
else
{
header('Location:'.Configuration::$page_administration);
exit();
}
?>