<?php
	include 'include/Configuration.php';
	header('HTTP/1.0 200');
	header('Status: 200 OK');
	header('Content-Type: text/cache-manifest');
	header('Cache-Control: no-cache');
	header('Expires: '.date('r', mktime() - 3600 * 24 * 7)).PHP_EOL;
	echo 'CACHE MANIFEST'.PHP_EOL;
	echo '# Version '.Configuration::$Version.PHP_EOL;
	echo '# Date '.getlastmod().PHP_EOL;
	echo 'CACHE:'.PHP_EOL;
	$fichiers = array(
	'css/interCitea_'.Configuration::$Version.'.css',
	'index.php',
	'javascript/geoloc_'.Configuration::$Version.'.js',
	'dataHorsLigne.php',
	'MonRovaltain.png',
	'qr_monrovaltain.png',
	'qr_cansii.png',
	'plus.png',
	'moins.png',
	'Fonts/EurostileBold.afm',
'Fonts/EurostileBold.eot',
'Fonts/EurostileBold.woff',
'Fonts/EurostileRegular.afm',
'Fonts/EurostileRegular.eot',
'Fonts/EurostileRegular.woff',
'Fonts/Garamond-Bold.afm',
'Fonts/Garamond-Bold.eot',
'Fonts/Garamond-Bold.woff',
'Fonts/Garamond.afm',
'Fonts/Garamond.eot',
'Fonts/Garamond.woff',
'Fonts/GillSansMT-Bold.afm',
'Fonts/GillSansMT-Bold.eot',
'Fonts/GillSansMT-Bold.woff',
'Fonts/GillSansMT.afm',
'Fonts/GillSansMT.eot',
'Fonts/GillSansMT.woff'
	);
	$datemaj = getlastmod();
	foreach($fichiers as $fichier)
	{
		echo $fichier.PHP_EOL;
		$datemaj = max($datemaj, filemtime($fichier));
	}
	echo '# Date de code '.$datemaj.PHP_EOL;
	$datemajdonnees = max($datemaj, filemtime(Configuration::$Documents['location'].'dataHorsLigne.html'));
	echo '# Date des donnees '.$datemajdonnees.PHP_EOL;
	
	echo 'NETWORK:'.PHP_EOL;
	echo '*'.PHP_EOL;
?>