<?php
	$analytics = null;
	if (isset($isAdmin) && ($isAdmin === true))
	{
		if (isset(Configuration::$Google['AnalyticsAdmin']))
			$analytics = Configuration::$Google['AnalyticsAdmin'];
	}
	else
	{
		if (isset(Configuration::$Google['Analytics']))
			$analytics = Configuration::$Google['Analytics'];
	}
	if ($analytics !== null)
	{
		?>
			<script type="text/javascript">
				var _gaq = _gaq || [];
				<?php
					if (is_array($analytics))
					{
						$i = 0;
						foreach($analytics as $tracker)
						{
							echo '_gaq.push(["_tracker'.$i.'._setAccount", "'.$tracker.'"]);'.PHP_EOL;
							if (isset(Configuration::$Google['Domain']))
								echo '_gaq.push(["_tracker'.$i.'._setDomainName", "'.Configuration::$Google['Domain'].'"]);'.PHP_EOL;
							echo '_gaq.push(["_tracker'.$i.'._trackPageview"]);'.PHP_EOL;
							$i++;
						}
					}
					else
					{
						echo '_gaq.push(["_setAccount", "'.$analytics.'"]);'.PHP_EOL;
						if (isset(Configuration::$Google['Domain']))
							echo '_gaq.push(["_setDomainName", "'.Configuration::$Google['Domain'].'"]);'.PHP_EOL;
						echo '_gaq.push(["_trackPageview"]);'.PHP_EOL;
					}
				?>				
				(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
		<?php
	}
?>