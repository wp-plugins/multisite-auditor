<?php

		global $GCharts;
		
		
		$action="";
		if(isset($_GET['action']))
		{
			
			$action=$_GET['action'];
			
			switch ($action)
			{
				
				
				case "auditComplete":
					if( !get_option( 'MSA_run_date' ) )
					{
						add_option( 'MSA_run_date', date('Y-m-d h:i:s'));	
					}
					else
					{
						update_option( 'MSA_run_date', date('Y-m-d h:i:s'));	
					}
					echo '<h1>Audit Complete</h1>';					
					echo '<div class="updated">Audit Run succesfully!</div><hr/>';
					/// Swap back to main blog
					switch_to_blog(1);
					
					// reset the action
					$action="";
				
				break;
				
				
				case "MSA_audit":
					MSA_functions::runFirstTimeAudit();
				break;
			
			}

		}
		
		
		if($action=="")
		{
			if(!get_option( 'MSA_run_date' ))
			{
				
				echo '<h1>Welcome to the Wordpress Multisite Auditor</h1>';


				echo '<h2>How to use this tool</h2>';
				
				echo 'Click the button below to audit your WP Multisite Network.<br/><br/>';
				echo '<b>You\'ll only have to do this once,</b> but if you have thousands of sites, it may take a few minutes to complete.';
				echo '<br/>Don\'t worry, it won\'t time out and you\'ll be able to see how far through the audit progress you are.';
				
				echo '<hr/>';
				echo '<b>Once your network has been audited this tool will automatically update data regarding plugins and themes when they are activated and deactivated.</b>';
				
				echo '<hr/><a href="admin.php?page=multisite-auditor-overview&action=MSA_audit&blogPage=0" class="button-primary">';
				
				echo 'Click here to run the Multisite Auditor</a>';
			}
			else
			{
				
				echo '<h1>Overview</h1>';
				
				$MSA_run_date = get_option( 'MSA_run_date' );
				$MSA_run_date = strtotime($MSA_run_date);			
				//Formats the Date
				$MSA_run_date = date('jS M Y, g:i a', $MSA_run_date);	
				
				
				
				$mySites = MSA_functions::getNetworkBlogList();
				$siteCount = count($mySites);
				
				$myThemes = wp_get_themes();
				$themeCount = count($myThemes);
				
				$myPlugins = get_plugins();
				$pluginCount = count($myPlugins);
				
				// Get all the themes into an array then do a count for each one
				$currentThemesArray = array();
				
				foreach($myThemes as $thisTheme => $themeObject)
				{
					$$thisThemeCount=0;
					$currentThemesCountArray[$thisTheme]="";
				}
				
				$allThemeSites = MSA_functions::getAllSitesFromThemesTable();
				
				foreach($allThemeSites as $siteInfo)
				{
					$themeName = $siteInfo['themeName'];
					${$themeName.'Count'}++;
				}
				
				
				//$themePieStr.= '[\'Theme Name\', \'Number of sites\'],<br/>';
				$themeChartData = array();
				$j = 1;
				foreach($myThemes as $thisTheme => $themeObject)
				{
					$thisCount = ${$thisTheme.'Count'};
					if($thisCount==""){$thisCount=0;}
					$currentThemesCountArray[$thisTheme] = $thisCount;
					
					if($thisCount>=1)
					{
						// Add data to chart array
						$thisThemeData = wp_get_theme( $thisTheme);
						$thisThemeName = $thisThemeData->Name;
						$themeChartData[] = array( $thisThemeName, $thisCount );
					}
					$j++;
					
					//$themePieStr.= '[\''.$thisThemeName.'\', '.$thisCount.']' . ( $j < $themeCount ? ', ' : '' );
				}
				
				arsort($currentThemesCountArray);				
				$mostPopularTheme = key($currentThemesCountArray);
				$mostPopularThemeName = wp_get_theme( $mostPopularTheme);
				$mostPopularThemeName = $mostPopularThemeName->Name;
				// End of the themes data	
				
				// Start of plugin data
				// Get the list of plugins	
				// get count of ALL plugins used across network for average
				$allPluginsUsed = MSA_functions::getAllPluginsUsed();
				$totalPluginCount = count($allPluginsUsed);
				$averagePluginsPerSite = round($siteCount/$totalPluginCount);

				$currentPlugins = get_plugins();			
				$pluginChartData = array();
				$masterPluginCountArray = array();
				
				foreach($currentPlugins as $pluginRef => $pluginObject)
				{				
				

					$thisPluginRef = MSA_functions::getPluginNameFromRef($pluginRef);	

					$thisPluginName = $pluginObject['Name'];					
					$thisPluginName = preg_replace("/[^A-Za-z0-9 ]/", '', $thisPluginName);
		
					
					// Get the number of sites using this plugin
					$blogList = MSA_functions::getBlogsUsingPlugin($thisPluginRef);
					$pluginUseCount=0;
					$pluginUseCount=count($blogList);	
					$masterPluginCountArray[$thisPluginName] = 	$pluginUseCount;				
					
					$pluginChartData[] = array( $thisPluginName, $pluginUseCount );
				}
				
				// Get the most popular plugins
				
				$popularPlugins = "";
				arsort($masterPluginCountArray);
				// Get the most popular count
				$pluginMaxCount = reset($masterPluginCountArray);
				
				$popularPluginsArray="";

				foreach($masterPluginCountArray as $pluginName => $useCount)
				{
					if($pluginMaxCount==$useCount)
					{
						$popularPluginsArray[]= $pluginName;
					}
				}

				$popPluginCount = count($popularPluginsArray);
				$popPluginStr="<b>";
				$i=1;
				foreach($popularPluginsArray as $popPluginName)
				{

					$popPluginStr.= $popPluginName;
					if($popPluginCount>=1 && $i<$popPluginCount)
					{
						$popPluginStr.=' | ';
					}
					$i++;					
				}	
				
				$popPluginStr.= '</b> (<i>'.$pluginMaxCount.' activations)</i>';
				
				// End of plugin data		
				
				echo '<div style="float:left; width:500px">';
				echo '<h2>'.$siteCount.' sites | '.$themeCount.' themes | '.$pluginCount.' plugins</h2>';
				echo '<span class="smallText greyText">Your network audit is up to date.<br/>However, if you manually remove themes or plugins you may wish to re-audit.</span>';
				echo '</div>';
				
				echo '<div id="rerunAuditBox">';
				echo 'Last Audit Date : '.$MSA_run_date.'<br/>';
				echo '<a href="admin.php?page=multisite-auditor-overview&action=MSA_audit&blogPage=0" class="button-primary">';
				echo 'Re-run Audit.</a>';

				echo '</div>';
				
				echo '<div style="clear:both"></div>';
				
				echo '<div class="infoDiv">';
				echo '<h3>Themes</h3>';
				echo '<div class="content">';			
				echo 'Most popular theme : <b>'.$mostPopularThemeName.'</b><hr/>';
				echo '<a href="?page=multisite-auditor-themes">View detailed theme breakdown</a>';
				
				$GCharts->draw( 
					'pie', 				//chart type
					$themeChartData, 		//chart data
					'themesPieWrap', 	//html element ID
					'Theme Name', 		//label for keys
					'Number of sites', 	//label for values
					'Active Themes'		//chart title
				);					
				
				echo '</div>';
				echo '</div>';				
				
				
				echo '<div class="infoDiv">';
				echo '<h3>Plugins</h3>';
				
				echo '<div class="content">';
				$puralStr="";
				if($popPluginCount>1){$puralStr='s';}
				echo 'Most popular plugin'.$puralStr.' : '.$popPluginStr;

				echo '<br/>';
				echo 'Average plugins activated per site : <b>'.$averagePluginsPerSite.'</b><hr/>';
				echo '<a href="?page=multisite-auditor-plugins">View detailed plugin breakdown</a>';				
				$GCharts->draw( 
					'bar', 				//chart type
					$pluginChartData, 		//chart data
					'pluginsPieWrap', 	//html element ID
					'Plugin Name', 		//label for keys
					'Number of sites', 	//label for values
					'Plugins'		//chart title
				);					
				
				echo '</div>';				
				echo '</div>';
				
			}
		}
?>