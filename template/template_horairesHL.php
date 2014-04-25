<?php 

function TableauHorsLigne($horaires,$force = 1)
{
	ob_start();
	$contenu = null;
	$bus = null;
	?>
		 <tr id="trtitres" />
			<th> </th>
			<th> Départ </th>
			<th> Arrivée </th>
		</tr>
	<?php

	foreach($horaires as $horaire)
	{	 
		$horairesBus = null;
		$horairesBus = Bus::getListeHorairesByBus($horaire['Bus']);
		foreach($horairesBus as $horaireBus)
		{
			if(($horaireBus['Gare']>$horaire['Gare']) && (((strtotime($horaire['Heure'])) - strtotime($horaireBus['Heure'])) < 0))
			{ //- date("G H s",mktime(0, 0, 0));
			?>
				<tr style="display:none" data-heure="<?php echo strtotime($horaire['Heure']) ?>" data-depart="<?php echo $horaire['Gare'] ?>" data-arrivee="<?php echo $horaireBus['Gare'] ?>" data-bus="<?php echo $horaire['Bus'] ?>" />
					<th> </th>
					<td> <?php echo $horaire['Heure'] ?> </td>
					<td> <?php echo $horaireBus['Heure'] ?> </td>
				</tr>
			<?php 	
			}
			if(($horaireBus['Gare']>$horaire['Gare']) && (((strtotime($horaire['Heure'])) - strtotime($horaireBus['Heure'])) > 0))
			{
			?>
				<tr style="display:none" data-heure="<?php echo strtotime($horaireBus['Heure']); ?>" data-depart="<?php echo $horaireBus['Gare'] ?>" data-arrivee="<?php echo $horaire['Gare'] ?>" data-bus="<?php echo $horaire['Bus'] ?>"/>
					<th> </th>
					<td> <?php echo $horaireBus['Heure'] ?> </td>
					<td> <?php echo $horaire['Heure'] ?> </td>
				</tr>
			<?php 	
			}
		}
	}
	$contenu .= ob_get_contents();
	ob_end_clean();
	return utf8_encode($contenu);
}
?>