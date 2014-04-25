<?php

	function EcranRecherche($lignes, $heure=null)
		{
		ob_start();
		
			?>
			<div id="monrov" style="display:none">
				<a href="http://www.monrovaltain.com/" style="font-size:23px;"> <?php //echo Configuration::$Url; ?> 
					www.monrovaltain.com<br>
					<img src="qr_monrovaltain.png" alt="www.monrovaltain.com" />
				</a><br>
				<span><input type="button"  style="font-size:25px;" value="Retour" onclick="DisplayMonRovaltain();"></span>
			</div>
			<div id="cansii" style="display:none">
				<a href="http://www.cansii.com/" style="font-size:23px;">
					www.cansii.com<br>
					<img src="qr_cansii.png" alt="www.cansii.fr" />
				</a><br>
				<span><input type="button" style="font-size:25px;" value="Retour" onclick="DisplayCansii();"></span><br>
			</div>
			<div class="titre" id="titre"><img src="/MonRovaltain.png" title="MonRovaltain par Cansii"><br>Inter Citea</div>
			<br>
			<div id="formRecherche" style="display:block;">
				<div class="row">
					<span>Lieu de depart :</span>
				</div>
				<div class="row">
						<span><select name ="lieuDep" id="lieuDep" onchange="go();">
						<option selected="selected"></option>
						<?php 
						foreach ($lignes as $ligne)
						{ 
						$depart = $ligne->GetGareDepart(TRUE);
						echo '<optgroup label="'.htmlentities($ligne->Nom,ENT_QUOTES,'UTF-8').'">';
						$gares = Bus::ListeArretsLigne($ligne->Id, '0');
						foreach($gares as $code => $gare)
							{ ?>
							<option data-ligne= "<?php echo $ligne->Id ?>" data-lat="<?php echo $gare->geoLat ?>"  data-lon="<?php echo $gare->geoLgt ;?>" data-IdGare="<?php echo $gare->IdGare;?>"><?php echo $gare->Nom ;?></option>
						<?php }
						echo "</optgroup>";
						} ?>
						</select></span>
						<br>
				</div>
				<div class="dist"></div>
				<div class="prec"></div>
				<br>
					<div class="row">
						<span>Lieu d'arrivée :</span>
					</div>
					<div class="row">
						<span><select name ="lieuArr" id="lieuArr" onchange="go();">
								<option selected="selected"></option>
								<?php 
								foreach ($lignes as $l)
								{ 
									$ligne = Lignes::Get($l->Id);
									$depart = $ligne->GetGareArrivee(TRUE);
									echo '<optgroup label="'.htmlentities($ligne->Nom,ENT_QUOTES,'UTF-8').'">';
									$gares = Bus::ListeArretsLigne($l->Id, '0');

									foreach($gares as $code => $gare)
									{	?>
										<option data-ligne= "<?php echo $ligne->Id ?>" data-lat="<?php echo $gare->geoLat ?>"  data-lon="<?php echo $gare->geoLgt ;?>" data-IdGare="<?php echo $gare->IdGare;?>"><?php echo $gare->Nom ;?></option>
								<?php }
								} ?>

						</select></span>
					</div>
				<br>
					<div class="row">
						<span>Heure de  départ :</span>
					</div>
				<div id="heureDepart">
					<div class="row">
						<span><select name ="heuresD" id="heureDep" onchange="go()">	
							<option selected="selected"></option>
							<?php
							for ( $heuresD = 6 ; $heuresD <= 23 ; $heuresD++ ) 
							{
		  						?>
								<option value="<?php echo $heuresD ;?>"><?php echo $heuresD ;?></option>
							<?php } ?>
						</select></span>
						<span><select name ="minutesD" id="minuteDep" onchange="go()">
						<option selected="selected"></option>	
						<?php  for ( $minutesD = 0 ; $minutesD <= 59 ; $minutesD += 1 ) 
							 { ?>
								<option value="<?php echo intval($minutesD) ;?>"><?php echo sprintf('%02d',$minutesD) ;?></option>
						 <?php   } ?>			
						</select></span>
						<span><input type="button" value="Heure courante" onclick="refreshSelect();"></span>

					</div>
				</div>
			</div>
			<?php
			$rslt = ob_get_contents();
			ob_end_clean();
			return $rslt;

		}

	function Social($heurebus,$grise = null)
	{
		?>

		<div id="social">
			<?php 
			if ($grise == null)
			{ 
				?><input type="button" class="social" id="signal" value="Il est là" onclick="DisplayRetard(<?php echo $heurebus['Bus']; ?>,'<?php echo $heurebus['heureD']; ?>');">
				<input type="button" class="social" id="alerte" value="Incident" onclick="DisplayAlerte(<?php echo $heurebus['Bus']; ?>);"><?php 
			}
			else
			{ 
				?>
				<input type="button" class="social" id="signal" value="Il est là" style="background-color:grey;">
				<input type="button" class="social" id="alerte" value="Incident" style="background-color:grey;"><?php 
			} 
			?>
		</div>

		<?php
	}

	function Signaler()
	{
		?>
		<div id="formSign" style="display: none;">
			<table border="1">
				<tr>
					<div id="retardIndic" style="border-bottom:15px;"></div>
					<br>
					<div class="row">
						<span>Email (facultatif) : </span>
					</div>
					<div class="row">
						<span><input type="email" id="mail" value=""></span>
					</div>
					<div class="row">
						<span>Téléphone (facultatif) : </span>
					</div>
					<div class="row">
						<input type="tel" id="telephone" value=""></span>
					</div> 
					<br>
					<input type="hidden" id="busid" value="">
					<input type="hidden" id="heureSign" value="">

					<span><input type="button" id="signalement" value="Signaler un passage" onclick="FormSignaler();DisplayAll();"></span>
					<span><input type="button" id="cancel" value="Annuler" onclick="document.getElementById('formSign').style.display='none';DisplayAll();"></span>
				<tr>
			</table>
		</div>
		<?php
	}

	function Alerte()
	{
		?>
		<div id="formAlert" style="display: none;">
			<table border="1">
				<tr>
					<div class="row">
						<span>Email (facultatif) : </span>
					</div>
					<div class="row">
						<span><input type="email" id="mail" value=""></span>
					</div>
					<div class="row">
						<span>Téléphone (facultatif) : </span>
					</div>
					<div class="row">
						<input type="tel" id="telephone" value=""></span>
					</div> 
					<div class="row">
						<span>Nature : </span>
					</div>
					<div class="row">
						<span><select name="nature" id="nature" value=""></span>
							<option id="nopassage">Pas de passage</option>
							<option id="accident">Accident</option>
							<option id="retard">Retard important</option>
							<option id="autre">Autre</option>
						</select>
					</div> 
					<br>
					<input type="hidden" id="busid" value="">
					<span><input type="button" id="alerter" value="Signaler une alerte" onclick="FormAlerter();DisplayAll();"></span>
					<span><input type="button" id="cancel" value="Annuler" onclick="document.getElementById('formAlert').style.display='none';DisplayAll();"></span>
				<tr>
			</table>
		</div>
		<?php
	}

	function Contact()
	{
		?>
		<div id="formContact" style="display: none;">
					
			<div class="row">
				<span>Email (facultatif) : </span>
			</div>
			<div class="row">
				<span><input type="email" id="mailContact" value=""></span>
			</div>
			<div class="row">
				<span>Téléphone (facultatif) : </span>
			</div>
			<div class="row">
				<input type="tel" id="telephoneContact" value=""></span>
			</div> 
			<br>
			<div id="txtalerte">
				<div class="row">
					<textarea name="comments" id="textarea" cols="30" rows="10" placeholder="Entrez ici votre commentaire:"></textarea> 
				</div>
				<input type="hidden" id="busid" value="">
				<span><input type="button" id="contact" value="Envoyer" onclick="FormContact();DisplayAll();"></span>
				<span><input type="button" id="cancel" value="Annuler" onclick="document.getElementById('formContact').style.display='none';DisplayAll();"></span>
			</div>
		</div>
		<?php
	}

	function TableauInit($nextH = null)
		{
		ob_start();
			?>

			<div id="resultat">
				<div class="row">
					<table border="1" id="initHoraires">
					 <tr id="trtitres">
						 <th>  </th>
						 <th> Départ </th>
						 <th> Arrivée </th>
						 <th> Remarques </th>
					</tr>
					 <tr id="trnext">
						 <th> Prochain bus </th>
						 <td> </td>
						 <td> </td>
						 <td> </td>
					 </tr>
					 <tr id="trsuiv">
						 <th>Bus suivant </th>
						 <td> </td>
						 <td> </td>
						 <td> </td>
					 </tr>
					</table> 
				</div>
			</div>
			<div id="message">
			</div>

			<?php
			$rslt = ob_get_contents();
			ob_end_clean();
			return $rslt;
		}
		
		
	function AjoutLigne($heureBus,$titre)
	{ 
		?>
		<tr>
			<th  rowspan="3"><?php echo $titre ;?> </th>
			<?php 
			$retard = Bus::getRetard($heureBus['Bus']); 
			if($retard != false)
			{
				$retard = number_format($retard,0);
				if($retard>3 && $retard<=5)
					$couleur="orange";
				if($retard>5)
					$couleur="red";
			}
			else $couleur="#00FF00"; 
			$alerte = Bus::getAlerte($heureBus['Bus']); 
			if($alerte != false)
				$couleur="red";
			?>
			<td><?php echo $heureBus['heureD'] ;?></td>
			<td><?php echo $heureBus['heureA'] ;?></td>
		</tr>	
		<tr><td colspan="2"><?php if($titre == "Bus suivant") Social($heureBus,1); else Social($heureBus); ?></td></tr>
		<tr><td  style="background-color:<?php echo $couleur ; ?>" colspan="2"><?php if($alerte !== false) echo $alerte; elseif($retard !==false && $retard!==0) echo $retard." minutes de retard";else null; ?></td></tr>

		<?php
	}

	function TableauResultat($heuresBus,$busPrecedent,$listebusprecedent)
	{	
		ob_start();
		?>
		<div id="res">
				<table id="initHoraires" class="enLigne">
				<tr>
					<th>
						<img src="moins.png" id="moins" style="display:none; margin-left: auto;margin-right: auto;" onclick='document.getElementById("listePrecedents").style.display = "none"; this.style.display = "none"; document.getElementById("plus").style.display = "block"' />
						<img src="plus.png" id="plus" style="display:block; margin-left: auto;margin-right: auto;" onclick='document.getElementById("listePrecedents").style.display = "table-row-group"; this.style.display = "none"; document.getElementById("moins").style.display = "block"' />
					</th>
					<th> Départ </th>
					<th> Arrivée </th>
				</tr>
				<tbody style="display:none" id="listePrecedents" style="display:none">
					<?php 
					for($i=0;$i < count($listebusprecedent) ;$i++)
						AjoutLigne($listebusprecedent[$i], "Bus précédent"); ?>
				</tbody>
				<?php 
				if($busPrecedent !== false)
					AjoutLigne($busPrecedent,"Bus récent");
				if(isset($heuresBus[0]))
					AjoutLigne($heuresBus[0],"Prochain bus");
				for($i=1;$i < count($heuresBus) ;$i++)
					AjoutLigne($heuresBus[$i], "Bus suivant") ;?>
				</table> 
		</div>
				<?php
				Signaler();
				Alerte();
				Contact();
				?>
			<?php
			$rslt = ob_get_contents();
			ob_end_clean();
			return $rslt;
	}

?>
