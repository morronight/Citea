<?php 
	require_once 'include/Bus.php';
	require_once 'include/Horaires.php';
	require_once 'include/Configuration.php';
	require_once 'template/template_admin.php';

if ($_SESSION['id_user_appli'] === null)
{
	header('Location:'.Configuration::$page_authentification);
	exit();
}	
	
	
	$template['titre'] = 'Mon Rovaltain';
	$template['content'] = '';


	$action=null;
	if (isset($_REQUEST['action']))
		$action=htmlentities($_REQUEST['action']);
	$methode=null;
	if (isset($_REQUEST['methode']))
		$methode=htmlentities($_REQUEST['methode']);
	$IdLigne=null;
	if (isset($_REQUEST['IdLigne']))
		$IdLigne=intval($_REQUEST['IdLigne']);
	$heureMin=null;
	if (isset($_REQUEST['heureMin']))
		$heureMin=intval($_REQUEST['heureMin']);	
	$heureMax=null;
	if (isset($_REQUEST['heureMax']))
		$heureMax=intval($_REQUEST['heureMax']);
	$BusId=null;
	if (isset($_REQUEST['BusId']))
		$BusId=intval($_REQUEST['BusId']);
	$Sens=null;
	if (isset($_REQUEST['Sens']))
		$Sens=intval($_REQUEST['Sens']);
	$Token=null;
	if (isset($_REQUEST['Token']))
		$Token=htmlentities($_REQUEST['Token']);


		
	switch(strtolower($action))
	{	
		case 'ajoutselect':
				$horaires = Bus::GetBus($IdLigne,$Sens,null,$heureMin,$heureMax);
			if (!(isset($heureMax)) && (isset($heureMin)))
			{
			$template['content'] = affHorairesDepart($horaires,$heureMin);
			}
			else
			{
			$template['content'] = affHorairesDepart($horaires,null);
			}
			echo $template['content'];
			break;
			
		case 'recup_bus':
			if($BusId != null)
			{
				$HorairesBus = Horaires::getHoraireByBus($BusId);		
			$template['content'] = affHorBus($HorairesBus,$BusId);
			echo $template['content'];
			}
			break;
		
		case 'modif':	
			if ($BusId !== null && isset($_REQUEST['Id_Horaires']) && isset($_REQUEST['Id_Gares']) && isset($_POST['Token']) && isset($_SESSION['Token']) && isset($_SESSION['Token_time']) && $_POST['Token'] == $_SESSION['Token'] && time() - $_SESSION['Token_time'] <= 30)
			{

		$TabHorairesExistants = array();
		$TabHorairesInexistants = array();
		
				$TabHorairesExistants=($_REQUEST['Id_Horaires']);
				$TabHorairesInexistants=($_REQUEST['Id_Gares']);
					  $base = Mysql::Get();
					  $base->StartTransaction();
					  $etat = "reussi";
					  $modif_rem = ($_REQUEST['modif_rem']);
					  $busActuel = Bus::Get($BusId);
						if ($busActuel->Remarque != $modif_rem)
						{
						  $busActuel->Remarque = $modif_rem;
						  $busActuel->Save();
						}
					  
						foreach($TabHorairesExistants as $IdHoraire => $New_Horaire)
					{if ($IdHoraire !== null)
						{
						  $Horaire = Horaires::Get($IdHoraire);
						  if ($New_Horaire === null || $New_Horaire == '' || $New_Horaire =='00:00' )
							{$Horaire->Remove();}
						  else
							{
								if (($Horaire->Heure) != $New_Horaire)
								{
									$Horaire->Heure = $New_Horaire;
								
									if ($Horaire->Save()=== NULL) 
									{	$etat = "echec";
										break;
									}
								}
							}
						}	
					}
						foreach($TabHorairesInexistants as $IdGare => $New_Horaire)
					{if ($IdGare !== null)
						{$Horaire = new Horaires;
						    if (($New_Horaire !== null) && ($New_Horaire != '') && ($New_Horaire != '00:00'))
							{	$Horaire->Heure = $New_Horaire;
							  $Horaire->Bus = $BusId;
							  $Horaire->Gare = $IdGare;
							  
								  if ($Horaire->Save()=== NULL) 
								{	$etat = "echec";
									break;
								}
							  
							  
							}
				
							
							
						}	
					}
						
					if ($etat == "reussi")
					{
						$HorairesBus = Horaires::getHoraireByBus($BusId);		
					$template['content'] = affHorBus($HorairesBus,$BusId);
					echo $template['content'];
						
						$base->Commit();
						
					}
					else
					{
						$base->Rollback();
						echo 0;
						
					}
				}
				else
				{
				echo "Echec";
				unset($_POST['Token']);
				unset($_SESSION['Token']);
				unset($_SESSION['Token_time']);
				}
				
				
			break;
		case 'form_ajout':
				
				$template['content'] = affAjout($IdLigne,$Sens);
				echo $template['content'];
				break;
		
		case 'ajout':
			if ($IdLigne !== null && $Sens !== null && isset($_POST['Token']) && isset($_SESSION['Token']) && isset($_SESSION['Token_time']) && $_POST['Token'] == $_SESSION['Token'] && time() - $_SESSION['Token_time'] <= 30)
			{
				$base = Mysql::Get();
				$base->StartTransaction();
				$newBus = new Bus;
				if (isset($_REQUEST['Rem']))
				{	$rem=($_REQUEST['Rem']);}
				$newBus->Remarque = $rem;
				$newBus->Sens = $Sens;
				$newBus->Ligne = $IdLigne;
				$NumeroBus = $newBus->Save();
				$etat = "reussi";
				if ($NumeroBus !== NULL)
				{
				if (isset($_REQUEST['liste_Ajout']))
				{	$TabArrets=($_REQUEST['liste_Ajout']);}
				 foreach($TabArrets as $IdGare => $New_Horaire)
				 {
				 $NewHoraire = new Horaires;
				 $NewHoraire->Bus = $NumeroBus;
				 $NewHoraire->Gare = $IdGare;
				 $NewHoraire->Heure = $New_Horaire;
					 if ($NewHoraire !== null && $NewHoraire->Heure != '' && $NewHoraire->Heure !='00:00' )
					{
						if (($NewHoraire->Save()) === NULL)
							{
							$etat = "echec"; 
							break;
							}
					}
				 }
				
				}
				if ($etat == "reussi")
					{
					$base->Commit();
					echo "L'enregistrement du nouveau bus a été effectué";
					}
				else
					{$base->Rollback();
					echo "Echec de l'enregistrement pour le bus";
					echo '<br>'.'<A HREF="ModifHoraires">Retour</A>';
					}
			}
			else
			{
			echo "Délai de validation dépassé";
			unset($_POST['Token']);
			unset($_SESSION['Token']);
			unset($_SESSION['Token_time']);
			}
				break;
		default:
			break;
	
	}

	?>