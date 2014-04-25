<?php
	if (!class_exists('Configuration'))
	{
		define('DIR_SEPARATOR', '/');
		
		class Configuration
		{
			public static $Version = '1.4';
			public static $Url = 'monrovaltain.com';
			public static $Offline = false;
			public static $page_administration = 'http://monrovaltain.com/Administration';
			public static $page_authentification = 'http://monrovaltain.com/Authentification';
			public static $page_modif = 'http://monrovaltain.com/ModifHoraires';
			
			public static $MySql = array
			(	'HOST' => 'localhost'
			,	'USER' => 'root'
			,	'PASS' => ''
			,	'BD' => 'mobilite'
			);

			public static $Google = array
			(	'GoogleAnalytics' => Array(
				'Analytics' => null 
				, 'AnalyticsAdmin' => null
				, 'Domain' => 'www.monrovaltain.com'
				, 'Translate' => null)
				,'GooglePlus' => Array(
				'ClientID' => ''
				,'ClientSecret' => ''
				,'url' => 'http://monrovaltain.com/Authentification_GooglePlus' 
				)
			);
			
			public static $Facebook = array
			(	
				'ClientID' => ''
				,'APISecret' => ''
				,'redirect-url' => 'http://monrovaltain.com/Authentification_Facebook'
		
			);
			
			
			public static $Static = array
			(
				'url' => ''
			);			
			
			public static $Images = array
			(
				'location' => '/srv/data-ecoparc/SiteWeb/images/'
				, 'cache' => '/srv/data-ecoparc/SiteWeb/cache/'
			);

			public static $Documents = array
			(
				'location' => '/srv/data-ecoparc/SiteWeb/documents/'
			);

			public static $Background = array
			(
				'bg' => '/images/couverts.jpg'
			);
			
			public static $Fonts = array
			(
				'location' => '/srv/data-ecoparc/SiteWeb/www/fonts/'
			,	'path' => '/fonts/'
			);

			public static $Css = array
			(
				'location' => '/srv/data-ecoparc/SiteWeb/www/css/'
				, 'cache' => '/srv/data-ecoparc/SiteWeb/cache/'
				, 'compact' => false
				, 'force' => true
			);

			public static $Javascript = array
			(
				'location' => '/srv/data-ecoparc/SiteWeb/www/'
				, 'cache' => '/srv/data-ecoparc/SiteWeb/cache/'
				, 'compact' => false
				, 'force' => true
			);
		}
	}
?>
