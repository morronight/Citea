	<head>
		<meta charset="UTF-8">
		<?php
			$titre = '';
			if (isset($template['titre']))
				$titre = $template['titre'];
		?>
		<title><?php echo htmlentities($titre, ENT_COMPAT, 'UTF-8'); ?></title>
		<meta name="Viewport" content="width=320">
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<?php
			if (isset($template['css']) && is_array($template['css']))
			{
				foreach($template['css'] as $groupe => $css)
				{
					if (is_array($css))
					{
						$filename = Configuration::$Css['cache'].$groupe.'_'.Configuration::$Version.'.css';
						if (!is_file($filename) || (Configuration::$Css['force'] === true))
						{
							$cssFile = '';
							foreach($css as $cssLine)
							{
								if ((Configuration::$Css['compact'] === false) || is_int($groupe))
								{
									$fileinfos = pathinfo($cssLine);
									echo '<link href="'.Configuration::$Static['url'].'/css/'.htmlentities($fileinfos['filename'].'_'.Configuration::$Version.'.'.$fileinfos['extension'], ENT_COMPAT, 'UTF-8').'" rel="stylesheet" type="text/css" media="screen"/>';
								}
								else
								{
									$fileinfos = pathinfo($cssLine);
									if (is_file(Configuration::$Css['location'].$cssLine))
									{
										if (strtolower($fileinfos['extension']) == 'php')
										{
											ob_start();
											$NoHeader = true;
											include 'www/css/'.$cssLine;
											$cssFile .= ob_get_contents();
											ob_end_clean();
										}
										else
											$cssFile .= file_get_contents(Configuration::$Css['location'].$cssLine);
									}
								}
							}
							if ($cssFile != '')
							{
								file_put_contents($filename, $cssFile);
								echo '<link href="'.Configuration::$Static['url'].'/css/'.htmlentities($groupe.'_'.Configuration::$Version.'.css', ENT_COMPAT, 'UTF-8').'" rel="stylesheet" type="text/css" media="screen"/>';
							}
						}
						else
							echo '<link href="'.Configuration::$Static['url'].'/css/'.htmlentities($groupe.'_'.Configuration::$Version.'.css', ENT_COMPAT, 'UTF-8').'" rel="stylesheet" type="text/css" media="screen"/>';
					}
					else
					{
						$fileinfos = pathinfo($css);
						echo '<link href="'.Configuration::$Static['url'].'/css/'.htmlentities($fileinfos['filename'].'_'.Configuration::$Version.'.'.$fileinfos['extension'], ENT_COMPAT, 'UTF-8').'" rel="stylesheet" type="text/css" media="screen"/>';
					}
				}
			}
			if (isset($template['javascript']) && is_array($template['javascript']))
			{
				foreach($template['javascript'] as $groupe => $javascript)
				{
					if (is_array($javascript))
					{
						$filename = Configuration::$Javascript['cache'].$groupe.'_'.Configuration::$Version.'.js';
						if (!is_file($filename) || (Configuration::$Javascript['force'] === true))
						{
							$jsFile = '';
							foreach($javascript as $jsLine)
							{
								if ((Configuration::$Javascript['compact'] === false) || is_int($groupe))
								{
									$fileinfos = pathinfo($jsLine);
									echo '<script type="text/javascript" src="'.Configuration::$Static['url'].'/javascript/'.htmlentities($fileinfos['filename'].'_'.Configuration::$Version.'.'.$fileinfos['extension'], ENT_COMPAT, 'UTF-8').'"></script>';
								}
								else
								{
									$fileinfos = pathinfo($jsLine);
									if (is_file(Configuration::$Javascript['location'].$jsLine))
									{
										if (strtolower($fileinfos['extension']) == 'php')
										{
											ob_start();
											$NoHeader = true;
											include 'www/javascript/'.$jsLine;
											$jsFile .= ob_get_contents();
											ob_end_clean();
										}
										else
											$jsFile .= file_get_contents(Configuration::$Javascript['location'].$jsLine);
									}
								}
							}
							if ($jsFile != '')
							{
								file_put_contents($filename, $jsFile);
								echo '<script type="text/javascript" src="'.Configuration::$Static['url'].'/javascript/'.htmlentities($groupe.'_'.Configuration::$Version.'.js', ENT_COMPAT, 'UTF-8').'"></script>';
							}
						}
						else
							echo '<script type="text/javascript" src="'.Configuration::$Static['url'].'/javascript/'.htmlentities($groupe.'_'.Configuration::$Version.'.js', ENT_COMPAT, 'UTF-8').'"></script>';
					}
					else
					{
						$fileinfos = pathinfo($javascript);
						echo '<script type="text/javascript" src="'.Configuration::$Static['url'].'/javascript/'.htmlentities($fileinfos['filename'].'_'.Configuration::$Version.'.'.$fileinfos['extension'], ENT_COMPAT, 'UTF-8').'"></script>';
					}
				}
			}
			include 'template/analytics.php';
			if (isset($template['googleTranslate']) && ($template['googleTranslate'] === true))
				include 'template/google-translate.php';
		?>
	</head>
