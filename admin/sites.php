<?php


	// Sort out the themes
	
	// Firstly get a blank of array of the themes
	$masterBlogThemeArray = array();
	$pluginUseLookupArray = array();	
	$currentSites = MSA_functions::getAllSitesFromThemesTable();
	foreach($currentSites as $thisSite => $siteObject)
	{
		$blogID = $siteObject['blogID'];
		$themeName = $siteObject['themeName'];
		$masterBlogThemeArray[$blogID] = $themeName;
	}
	
	// Now get array of themes for quick lookup based on the themeRef as key
	$allThemes	= wp_get_themes();
	$themeLookupArray = array();
	foreach($allThemes as $themeRef => $themeObject)
	{
		$themeName =  $themeObject->Name;
		$themeLookupArray[$themeRef] = $themeName;
	}
	// End theme info
	
	// Plugin Info
	//get the entire info of plugin table as array
	$masterPluginArray = MSA_functions::getAllPluginsUsed();
	foreach($masterPluginArray as $blogPluginInfo)
	{	
		$blogID = $blogPluginInfo['blogID'];
		$pluginRef = $blogPluginInfo['pluginName'];
		$pluginUseLookupArray[$blogID][] = $pluginRef;
		
	}
	
	// Get a lookup of plugin names from ref
	$myPlugins = get_plugins();
	$pluginNameLookupArray = array();
	foreach($myPlugins as $pluginRef => $pluginObject)
	{
		$pluginName = $pluginObject['Name'];
		$pluginRef = MSA_functions::getPluginNameFromRef($pluginRef);
		$pluginNameLookupArray[$pluginRef] = $pluginName;	
	}


	
	

	// End Plugininfo
	
	echo '<h1>Sites</h1> ';
	echo '<table id="sitesTable">';
	echo '<thead><tr><th>Blog ID</th><th>Blog Name</th><th>URL</th><th>Theme</th><th>Plugins</th></thead>';		
	
	foreach($currentSites as $thisSite => $siteObject)
	{
		
		$blogName= $siteObject['blogName'];
		$blogID = $siteObject['blogID'];
		$blogURL = $siteObject['blogURL'];
		
		$pluginCount=0;
		$pluginArray = $pluginUseLookupArray[$blogID];
		$pluginCount = count($pluginArray);
		$blogThemeRef = $masterBlogThemeArray[$blogID];
		$blogThemeName = $themeLookupArray[$blogThemeRef];
		// Check to see if its network enabled or not
		echo '<tr>';
		echo '<td valign="top" width="20px" valign="top">';
		echo $blogID;
		echo '</td>';
		echo '<td valign="top">';
		echo $blogName;
		echo '</td>';
		echo '<td valign="top">';
		echo '<a href="'.$blogURL.'" target="blank">'.$blogURL.'</a>';
		echo '</td>';		
		echo '<td valign="top">'.$blogThemeName.'</td>';
		echo '<td>';
		
		
		$pluginLinkStart='';
		$pluginLinkEnd='';
		$pluginStr='';
		if($pluginCount>=1)
		{
			$pluginLinkStart = '<a href="javascript:toggleLayerVis(\'pluginsBlog'.$blogID.'\')">';	
			$pluginLinkEnd = '</a>';
			$pluginStr= '<div id="pluginsBlog'.$blogID.'" style="display:none">';
			foreach($pluginArray as $pluginName)
			{
				$thisPluginName = $pluginNameLookupArray[$pluginName];
				$pluginStr.=$thisPluginName.'<hr/>';;
			}
			$pluginStr.= '</div>';
		}
		echo $pluginLinkStart;
		echo $pluginCount.' Plugins<br/>';
		echo $pluginLinkEnd;		
		echo $pluginStr;
		
		echo '</td>';		
		echo '</tr>';			
	}
	
	
	echo '</table>';
	?>
	<script>
		jQuery(document).ready(function(){	
			if (jQuery('#sitesTable').length>0)
			{
				jQuery('#sitesTable').dataTable({
					"bAutoWidth": true,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength": 50 // How many numbers by default per page
				});
			}
			
		});
	</script>	
