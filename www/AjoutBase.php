<?php
	require_once 'include/Bus.php';
	require_once 'include/Gares.php';
	$template['titre'] = 'Mon Rovaltain';
	$template['content'] = '';
	$template['css'][] = 'interCitea.css';
	//$gares = Gares::GetGares();
	//$bus = Bus::GetBus();
	//$nbBus = Bus::GetNbBus($bus);
	$rBus = null;
	if($nbBus < 8)
		$rBus = $bus;
	else
	{
		$rBus = $bus+1;
		$nbBus = Bus::GetNbBus($bus+1);
	}
	$rGare = null;
	if(isset($_REQUEST['gare']))
		$rGare = $_REQUEST['gare'];
	$rHeuresD = null;
	if(isset($_REQUEST['heuresD']))
		$rHeuresD = $_REQUEST['heuresD'];
	$rMinutesD = null;
	if(isset($_REQUEST['minutesD']))
		$rMinutesD = $_REQUEST['minutesD'];
		
	if(isset($rGare) && isset($rBus) && isset($rHeuresD) && isset($rMinutesD))
	{	
		$newBus = new Bus;
		$newBus->Id = $rBus;
		$newBus->IdGare = $rGare;
		$newBus->Heure = $rHeuresD.':'.sprintf('%02d',$rMinutesD).':00';
		$newBus->Save();
	}
?>
<h1>Il vous reste <?php echo 8-($nbBus); ?> arrets a rentrer pour cette ligne (<?php echo $rBus; ?>)</h1>
<form action="AjoutBase.php" method="post">

	<div class="row">
		<span>Heure de  passage :</span>
	</div>
	<div class="row">
		<span><input name ="heuresD" id="heureDep"></span>
		<span><input name ="minutesD" id="minuteDep"></span>
	</div>
	<div class="row">
		<input type="submit" value="Ajouter a la base">
	</div>
		<br \>
		<span>Nom de gare :</span>
		<select id="gareId" name="gare">
			<?php 	if($rGare != null)
				{ ?>
			<option selected="selected" value="<?php echo $gares[$rGare+1]->IdGare; ?>"><?php echo $gares[$rGare+1]->Nom; ?></option>
		<?php } ?>
			<?php foreach($gares as $code => $gare)
			{?>
				<option value="<?php echo $gare->IdGare; ?>"><?php echo $gare->Nom; ?></option>
			<?php } ?>
		</select>
</form>