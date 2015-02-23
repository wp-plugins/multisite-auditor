<?php

	
	echo '<h1>Themes</h1> ';
	
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		switch ($action)
		{		
			case "themeSwapComplete":
				echo '<div class="updated">Blog Themes Updates</div><br/><br/>';
			break;
		}
	}	
	

	// Firstly get a blank of array of the themes
	$currentThemes = wp_get_themes();
	
	$networkEnabledThemes = wp_get_themes( array( 'allowed' => 'network' ));
	$networkEnabledThemesArray = array();
	
	// Create an arry of those themes which are network enabled
	foreach($networkEnabledThemes as $thisTheme => $themeObject)
	{
		$thisThemeName = $themeObject->get( 'Name' );
		$networkEnabledThemesArray[]= $thisTheme;
	}
	
	// Create a simple array of themeName to check against ALL themes
	$currentThemeNameArray = array();
	
	
	foreach($currentThemes as $thisTheme => $themeObject)
	{
		$currentThemeNameArray[]=$thisTheme;
	}
	
	$allThemesArray = MSA_functions::getAllThemesArray();
	
	// Enabled / disabled string creator
	$enabledThemeStr = '<span style="color:green">Network Enabled</span>';
	$disabledThemeStr = '<span style="color:#CC0000">Network Disabled</span>';
	
	// Go through all the themes and check if they are in the actual vaid themes
	$missingBlogTableStr="";
	foreach($allThemesArray as $themeName)
	{
		if(!in_array($themeName, $currentThemeNameArray))
		{
			// Check to see if its network enabled or not
			if(in_array($themeName, $networkEnabledThemesArray))
			{
				$enabledStr = $enabledThemeStr;
			}
			else
			{
				$enabledStr = $disabledThemeStr;			
			}
			
			
			$blogList = MSA_functions::getBlogsOnTheme($themeName);

			$themeUseCount = 0;
			
			if($blogList)
			{
				$themeUseCount = count($blogList);
			}	
			
			$themeLink = "admin.php?page=multisite-auditor-theme-info&themeName=".$themeName;
			
			$missingBlogTableStr.='<tr class="tableQuery">';
			$missingBlogTableStr.='<td valign="top"><span style="font-size:16px"><a href="'.$themeLink.'">'.$themeName.'</a></span>';
			$missingBlogTableStr.='<br/>'.$enabledStr.'</td>';
			$missingBlogTableStr.='<td style="color:#CC0000" valign="top">This theme no longer exists!</td>';
			$missingBlogTableStr.= '<td width="100px" valign="top">';
			$missingBlogTableStr.= $themeUseCount;
			$missingBlogTableStr.= '</td>';
			$$missingBlogTableStr.='</tr>';
		}
	}
	
	

	
	
	echo '<table id="themesTable">';
	echo '<thead><tr><th>Theme</th><th>Description</th><th>Number</th></thead>';		
	
	foreach($currentThemes as $thisTheme => $themeObject)
	{
		
		$thisThemeName = $themeObject->Name;
		// Check to see if its network enabled or not
		if(in_array($thisTheme, $networkEnabledThemesArray))
		{
			$enabledStr = $enabledThemeStr;
			$rowClass = 'tableOK';
		}
		else
		{
			$enabledStr = $disabledThemeStr;	
			$rowClass = 'tableError';
		}
		
		$version="";
		$isChild="";
		$themeDescription = $themeObject->get( 'Description' );
		$version = $themeObject->get( 'Version' );
		$isChild = $themeObject->get( 'Template' );
		// Get the count of sites using this theme
		
		$themeLink = "admin.php?page=multisite-auditor-theme-info&themeName=".$thisTheme;
		
		
		$blogList = MSA_functions::getBlogsOnTheme($thisTheme);

		$themeUseCount = 0;
		
		if($blogList)
		{
			$themeUseCount = count($blogList);
		}	
		
		echo '<tr class="'.$rowClass.'">';
		echo '<td valign="top" width="200px">';
		echo '<span style="font-size:14px"><a href="'.$themeLink.'">'.$thisThemeName.'</a></span>';
		echo '<br/>'.$enabledStr;			
		if($isChild)
		{
			echo '<br/><span style="font-size:9px">This is a child theme of <b>'.$isChild.'</b></span>';
		}			
		echo '</td>';
		echo '<td valign="top" style="color:#808080;">';
		echo $themeDescription;
		if($version){echo '<br/><span style="font-size:9px">Version '.$version.'</span>';}
		echo '</td>';
		echo '<td width="100px" valign="top">';
		echo $themeUseCount;
		echo '</td>';
		echo '</tr>';			
	}
	
	// Add the missing blogs as well
	echo $missingBlogTableStr;
	
	echo '</table>';
	?>
	<script>
		jQuery(document).ready(function(){	
			if (jQuery('#themesTable').length>0)
			{
				jQuery('#themesTable').dataTable({
					"bAutoWidth": true,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength": 50 // How many numbers by default per page
				});
			}
			
		});
	</script>	
