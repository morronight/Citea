<?php
	/*
	**	String			$template['titre'];
	**	String Array	$template['css'];
	**	String Array	$template['javascript'];
	**	String			$template['content'];
	**  String			$template['onload'];
	*/
	require_once 'include/Configuration.php';
	
	if (isset($_SERVER['HTTP_USER_AGENT']) && (preg_match('/MSIE [6-8]/', $_SERVER['HTTP_USER_AGENT']) > 0))
	{
		$template['css'][] = 'interCitea.css';
		ob_start();
	}
?>
<!DOCTYPE html>
<html lang="fr" manifest= "manifest.php">
<?php //   ?> 
	<?php include 'template/template_head.php'; ?>
	<body <?php //echo 'style="background-image:url(\''.htmlentities(Configuration::$Background['bg'], ENT_QUOTES, 'UTF-8').'\');"'//?>>
		<article>
			<?php
				echo $template['content'];
			?>
		</article>
		<footer>
			<span><?php echo 'V '.Configuration::$Version; ?></span>
			<input type="button" id="boutoncontact" value="Contact" onclick="DisplayContact();">
			<a href ="www.cansii.com" class="about" target="main" onclick="DisplayCansii(); return false;">
				<img src="qr_cansii.png" width="40" height="40" style="border:0;margin: 0px 25px;" /><br>
				Cansii
			</a>
			<a href ="www.monrovaltain.com" class="about" target="main" onclick="DisplayMonRovaltain(); return false;">
				<img src="qr_monrovaltain.png" width="40" height="40" style="border:0;" /><br>
				MonRovaltain
			</a>
		</footer>
		<?php
			if (isset($template['onload']) && ($template['onload'] != ''))
				echo '<script type="text/javascript">'.$template['onload'].'</script>';
		?>
	</body>	
</html>
