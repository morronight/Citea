<?php
	if (!class_exists('Configuration'))
	{
		define('DIR_SEPARATOR', '/');
		
		class Configuration
		{
			public static $Version = '1.4';
			public static $Url = 'tests.monrovaltain.com';
			public static $Offline = false;
			
			public static $MySql = array
			(	'HOST' => 'localhost'
			,	'USER' => 'root'
			,	'PASS' => 'JV3dFsvU1SU3'
			,	'BD' => 'mobilite_tests'
			);

			public static $Google = array
			(	'GoogleAnalytics' => Array(
				'Analytics' => null 
				, 'AnalyticsAdmin' => null
				, 'Domain' => 'www.monrovaltain.com'
				, 'Translate' => null)
				,'GooglePlus' => Array(
				'ClientID' => '502188651119.apps.googleusercontent.com'
				,'ClientSecret' => 'XWArQzy35Jk9j6cGFvy95RIx'
				,'APIKey' => 'AIzaSyBi_qS9cNJnGeXb8fKOvY-1cloQVV-ESKs'
				,'url' => 'http://tests.monrovaltain.com/Authentification_GooglePlus.php' 
				)
			);
			
			public static $Facebook = array
			(	
				'ClientID' => '470580953013306'
				,'APISecret' => 'e2d2c14ec73b802d6219399171ad2526'
				,'redirect-url' => 'http://tests.monrovaltain.com/Authentification_Facebook.php'
		
			);
			
			
			public static $Static = array
			(
				'url' => ''
			);			
			
			public static $Images = array
			(
				'location' => '/srv/data-ecoparc/SiteWebTests/images/'
				, 'cache' => '/srv/data-ecoparc/SiteWebTests/cache/'
			);

			public static $Documents = array
			(
				'location' => '/srv/data-ecoparc/SiteWebTests/documents/'
			);

			public static $Background = array
			(
				'bg' => '/images/couverts.jpg'
			);
			
			public static $Fonts = array
			(
				'location' => '/srv/data-ecoparc/SiteWebTests/www/fonts/'
			,	'path' => '/fonts/'
			);

			public static $Css = array
			(
				'location' => '/srv/data-ecoparc/SiteWebTests/www/css/'
				, 'cache' => '/srv/data-ecoparc/SiteWebTests/cache/'
				, 'compact' => false
				, 'force' => true
			);

			public static $Javascript = array
			(
				'location' => '/srv/data-ecoparc/SiteWebTests/www/'
				, 'cache' => '/srv/data-ecoparc/SiteWebTests/cache/'
				, 'compact' => false
				, 'force' => true
			);
		}
	}
?>
