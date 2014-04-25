<?php 
	require_once 'include/Bus.php';
	require_once 'include/Gares.php'; 
	require_once 'include/Lignes.php'; 
	require_once 'template/template_citea.php';
	$template['titre'] = 'Mon Rovaltain';
	$template['content'] = '';
	$template['javascript'][] = 'geoloc.js';
	$template['css'][] = 'interCitea.css';
	$template['onload'] = 'getLocation();loadData();';

	$action=null;
	if (isset($_POST['action']))
		$action=$_POST['action'];
	$methode=null;
	if (isset($_POST['methode']))
		$methode=$_POST['methode'];
	$heureD=null;
	if (isset($_POST['heuresD']))
		$heureD=$_POST['heuresD'];
	$minuteD=null;
	if (isset($_POST['minutesD']))
		$minuteD=$_POST['minutesD'];
	$ligne=null;
	if (isset($_POST['ligne']))
		$ligne=$_POST['ligne'];
	$lieuD=null;
	if (isset($_POST['lieuxD']))
		$lieuD=$_POST['lieuxD'];
	$lieuA=null;
	if (isset($_POST['lieuxA']))
		$lieuA=$_POST['lieuxA'];
	$busId=null;
	if (isset($_POST['idBus']))
		$busId=$_POST['idBus'];
	$nbRes=2;
	if (isset($_POST['nbres']))
		$nbRes=$_POST['nbres'];
	$mail=null;
	if (isset($_POST['mail']))
		$mail=$_POST['mail'];
	$telephone=null;
	if (isset($_POST['telephone']))
		$telephone=$_POST['telephone'];
	$mailContact=null;
	$nature=null;
	if (isset($_POST['nature']))
		$nature=$_POST['nature'];
	if (isset($_POST['mailContact']))
		$mailContact=$_POST['mailContact'];
	$text=null;
	if (isset($_POST['text']))
		$text=$_POST['text'];

	if ($lieuD !== null)
	setcookie("LieuDepart", $lieuD, time()+60*60*24*30);

	if ($lieuA !== null)
	setcookie("LieuArrivee", $lieuA, time()+60*60*24*30);

	if(($methode !== null) && (strtolower($methode) == 'ajax'))
	{
		switch(strtolower($action))
		{
			case 'afficher':

				$prochainBus = Bus::getHoraireBus($lieuD,$lieuA,$heureD,$minuteD,$ligne,$nbRes);
				$busPrecedent = Bus::getHoraireBusPrecedent($lieuD,$lieuA,$heureD,$minuteD,$ligne);
				$listeBusPrecedents = Bus::getListeHoraireBusPrecedent($lieuD,$lieuA,$heureD,$minuteD,$ligne);
				$template['content'] = TableauResultat($prochainBus,$busPrecedent,$listeBusPrecedents);
				echo $template['content'];
				break;
	

			case 'alerter':
	
				$alerte = Bus::setAlerte($busId,$lieuD,$mail,$telephone,$nature);
				break;
	
			case 'contact':
	
				$sent = Bus::contact($text,$mailContact);
				if($mail !== null)
					Bus::remerciement($mail);
				break;
	
			case 'signaler':

				$retard = Bus::setPassage($busId,$lieuD,$mail,$telephone);
				break;
	
			default:
				break;
		}
	}
	else
	{
		$lignes = Lignes::Get();
		$template['content'] = EcranRecherche($lignes);
		$template['content'] .= TableauInit();
		include 'template/template_accueil.php';
	}
?>
