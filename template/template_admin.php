<?php 
require_once 'include/Bus.php';
require_once 'include/Gares.php';

 ?>
<?php

	
	function affHorairesDepart($horaires)
	{?><option value =null></option><?php
		foreach($horaires as $hor)
			{ $horaireDepart = Bus::getHeureDepart($hor->Numero) ?>
		<option value = "<?php echo htmlentities($horaireDepart->Bus,ENT_COMPAT,'UTF-8'); ?>">
		<?php echo htmlentities($horaireDepart->Heure,ENT_COMPAT,'UTF-8'); ?>
		</option>
		<?php } 
	} 
	
	
	function affHorBus($HorairesBus,$BusId)
	{
	?>
	<h2>Modification du bus</h2>
	
	<h3>Les horaires</h3>
	
		<input type="hidden" name="action" value="modif">
		<table>
		<tbody>
			<?php
			$BusActuel = Bus::Get($BusId);	     
			$listeArrets = $BusActuel->ListeArrets();
			$rem = $BusActuel->Remarque;
			
			foreach($listeArrets as $arret)
			{ $i = null;
				?> 
				<tr>
				<td><?php echo htmlentities($arret->Nom,ENT_COMPAT,'UTF-8'); ?></td> 
				<?php 
					foreach($HorairesBus as $horaire)
					{ if ($horaire->Gare == $arret->IdGare) 
					 {$i = $horaire;
					 break;
					 }
					}
					  if ($i !== null) 
					  {?>
					  <td><input type="time" name ="Id_Horaires[<?php echo $i->IdHoraire; ?>]" value = "<?php echo $i->Heure; ?>" /> </td>
					  <?php }
					  else 
					  {?>
					  <td><input type="time" name ="Id_Gares[<?php echo $arret->IdGare; ?>]" value = "" /> </td>
					 <?php }
					
				?>	</tr> <?php
			}
			?>
		
		</tbody>
		</table>
		
		<h3>Remarque</h3>
		<input type="hidden" name="BusId" value= <?php echo $BusId ?>>
		<input type="hidden" id="form1_Token" name="Token" value="" >
		<input type="text" name = "modif_rem" size="50px" value = "<?php echo $rem; ?>" >
		<br>
		<input type="submit" value="Valider">
	
		
		<?php 
	}
	
	function affAjout($IdLigne,$Sens)
	{ 
	if ($IdLigne != null)
	{
	?>
		<input type="hidden" id="form2_action" name="action" value="ajout">
		<input type="hidden" id="form2_IdLigne" name="IdLigne" value= <?php echo $IdLigne ?> >
		<input type="hidden" id="form2_Sens" name="Sens" value= <?php echo $Sens ?> >
		<input type="hidden" id="form2_Token" name="Token" value="" >
		<h3>Les horaires </h3>
		<div>
		<table>
			<tbody>		
			<?php $listeArrets = Bus::ListeArretsLigne($IdLigne,$Sens);
			
			foreach($listeArrets as $arret)
				{?> <tr> <td><?php echo $arret->Nom; ?></td> 
				<td><input type="time" id="form2_liste_Ajout" name = "liste_Ajout[<?php echo $arret->IdGare;?>]"></td></tr>
			<?php } ?> 
			</tbody>
		</table>
		</div>  
		<h3>Remarque</h3>
		<input type="text" size="50px" id="form2_Rem" name = "Rem" value ="">
		<br>
		<input type="submit" value="Valider">
		<input type="reset" value="Vider formulaire">
		
		<?php
	}
	} 
	
	
	
?>
	
