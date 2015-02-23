<?php		
		
	$themeName = $_GET['themeName'];
	$originalThemeInfo = wp_get_theme( $themeName);
	$originalThemeName = $originalThemeInfo->Name;
	
	echo '<h1>Theme : '.$originalThemeName.'</h1>';
	echo '<a href="admin.php?page=multisite-auditor-themes">Back to theme list</a><hr/>';
	
		
	if(!isset($_GET['themeName']))
	{
		echo 'No theme found';
		die();	
	}
	
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		
	
		switch ($action)
		{		
			case "swapThemeCheck":
				
				$targetTheme = $_POST['targetTheme'];
				
				if($targetTheme)
				{
					$targetThemeInfo = wp_get_theme( $targetTheme);
					$targetThemeName = $targetThemeInfo->Name;
					
					echo '<div class="error">';
					echo 'This will swap all blogs currently using <b>"'.$originalThemeName.'"</b> to the theme <b>"'.$targetThemeName.'"</b><hr/>';
					echo '<span class="failText">Are you sure you want to do this?</span><br/><br/>';
					echo '<a href="admin.php?page=multisite-auditor-theme-swap&oldTheme='.$themeName.'&newTheme='.$targetTheme.'" class="button-primary">Yes, swap theme</a> ';
					echo '<a href="?page=multisite-auditor-theme-info&themeName='.$themeName.'" class="button">Cancel</a>';
					echo '</div><br/>';
				}
				
			
			
			break;
		}
		
	}

	// Swap themes option
	echo '<form action="admin.php?page=multisite-auditor-theme-info&themeName='.$themeName.'&action=swapThemeCheck" method="post">';
	$currentThemes = wp_get_themes();
	echo 'Swap these sites to ';
	echo '<select name="targetTheme">';
	echo '<option value="">-- Select --</option>';
	foreach($currentThemes as $targetThemeName => $themeObject)
	{
		$thisThemeName = $themeObject->Name;		
		echo '<option value="'.$targetThemeName.'">';
		echo $thisThemeName;
		echo '</option>';
	}
	
	echo '</select>';
	echo '<input type="submit" value="Swap" class="button-primary">';

	echo '</form><hr/>';
	
	$blogList = MSA_functions::getBlogsOnTheme($themeName);
	
	
	$blogCount = count($blogList);
	
	echo $blogCount.' blogs currently using this theme.<br/><br/>';
	
	if($blogCount>=1)
	{
		$tableStr="";
		foreach($blogList as $blogID => $blogInfo)
		{
			$blogID = $blogInfo['blogID'];
			$activateDate = $blogInfo['activateDate'];
			$blogName = $blogInfo['blogName'];
			$blogURL = $blogInfo['blogURL'];
			$dateCreated = $blogInfo['dateCreated'];
			

			
			$tableStr.='<tr>';
			$tableStr.='<td>'.$blogID.'</td>';
			$tableStr.='<td>'.$blogName.'</td>';
			$tableStr.='<td><a href="'.$blogURL.'" target="blank">'.$blogURL.'</a></td>';
			$tableStr.='<td>'.$dateCreated.'</td>';
			
			$tableStr.='</tr>';
		}
		
		
		echo '<table id="themeBlogsTable">';
		echo '<thead><tr><th width="10">ID</th><th>Blog Name</th><th>URL</th><th>Date Created</th></thead>';		
		echo $tableStr;
		echo '</table>';
		
		
		?>
		<script>
			jQuery(document).ready(function(){	
				if (jQuery('#themeBlogsTable').length>0)
				{
					jQuery('#themeBlogsTable').dataTable({
						"bAutoWidth": true,
						"bJQueryUI": true,
						"sPaginationType": "full_numbers",
						"iDisplayLength": 50 // How many numbers by default per page
					});
				}
				
			});
		 </script>	        
		<?php
	} // end if blog count exists
	?>
	