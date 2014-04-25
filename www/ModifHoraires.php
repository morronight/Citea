<?php
	require_once 'include/Bus.php';
	require_once 'include/Horaires.php';
	require_once 'include/Gares.php';
	require_once 'include/Lignes.php';
	require_once 'include/Utilisateurs.php';
	
	$template['titre'] = 'Mon Rovaltain';
	$template['content'] = '';
	$template['css'][] = 'interCitea.css';
	$lignes = Lignes::Get(NULL,TRUE);

if ($_SESSION['id_user_appli'] !== null)
{
?>
<html>
<head><meta charset="utf-8"> 
<script language="Javascript" src="javascript/administration_1.4.js"> </script>
</head>

<body>
<h2 style="text-align:center">Ajout/Modification des bus</h2>


	<h2>Choix du bus</h2>
	<select id = "ChoixType" onChange="afficher('choix2',this.value);afficher('choix3','cache');
	select_default('choix2','cache');select_default('choix3','0');aff_Ajout();">
		<option id = "ajout" value="cache">Nouveau</option>
		<option id = "modif" value ="affiche"> Modification </option>
		
		
	</select>

	<select style="display:none" name ="choix2" id ="choix2" onChange="afficher('choix3',this.value);
			selectHoraire(this);cacher_afficher('modif2','cacher');">
		<option value="cache"></option>
		<?php foreach($lignes as $l)
		{
		$ligne = Lignes::Get($l->Id);
		$depart = $ligne->GetGareDepart(TRUE);
		echo '
		<optgroup label="'.htmlentities($l->Nom,ENT_QUOTES,'UTF-8').' - '.$depart->Nom.'"';
		echo "> 
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '0' data-heureMin=''    data-heureMax= '10'> Avant 10h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '0' data-heureMin='10'  data-heureMax='14' > Entre 10 et 14h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '0' data-heureMin='14'  data-heureMax='18' > Entre 14 et 18h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '0' data-heureMin='18'  data-heureMax=''   > Après 18h</option>
		</optgroup>";
		$depart = $ligne->GetGareDepart(FALSE);
		echo '
		<optgroup label="'.htmlentities($l->Nom,ENT_QUOTES,'UTF-8').' - '.$depart->Nom.'"';
		echo "> 
		<option  data-IdLigne='".($l->Id)."'  data-Sens ='1' data-heureMin=''    data-heureMax= '10:00' > Avant 10h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens ='1' data-heureMin='10:00'  data-heureMax='14:00'  > Entre 10 et 14h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens ='1' data-heureMin='14:00'  data-heureMax='18:00'  > Entre 14 et 18h</option>
		<option  data-IdLigne='".($l->Id)."'  data-Sens ='1' data-heureMin='18:00'  data-heureMax=''    > Après 18h</option>

		</optgroup>";
		
		}
		?>
		
		
	</select>
	
	<select style="display:none" name ="choix3" id="choix3" onChange="affHorBus(this);cacher_afficher('modif2','afficher');">
	</select>
	
	<div id ="modif2" style="display:none">
	
	<form id ="form1" onsubmit="return submit_form1();">
	</form>
	</div>
	
	
	
	<div id="ajout2">
		<h2>Ajout d'un bus</h2>
	
	<h3>Direction</h3>
	<select  name ="choixLigne" id ="choixLigne" onChange ="selectLigneAjout(this);">
		<option value="cache" data-Idligne="null" data-sens="null"></option>
		<?php foreach($lignes as $l)
		{
		$ligne = Lignes::Get($l->Id);
		$depart = $ligne->GetGareDepart(TRUE);
		echo "
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '0' >".htmlentities($l->Nom,ENT_QUOTES,'UTF-8').' - '.$depart->Nom."</option>
		";
		$depart = $ligne->GetGareDepart(FALSE);
		
		echo "
		<option  data-IdLigne='".($l->Id)."'  data-Sens = '1' >".htmlentities($l->Nom,ENT_QUOTES,'UTF-8').' - '.$depart->Nom."</option>
		";
		}
		?>
		
		
	</select>
	<form id = "form2" onsubmit="return submit_form2();">
	</form>
	</div>
	</body></html>
	

<?php 

}
else
{echo "Utilisateur non connecté ou non autorisé";
echo "<br><a href=".Configuration::$page_authentification."> S'authentifier </a>";
}
?>
