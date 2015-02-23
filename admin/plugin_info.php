<?php		
		
	$pluginName = $_GET['pluginName'];
	if(!isset($_GET['pluginName']))
	{
		echo 'No plugin found';
		die();	
	}
	
	// Get the plugin dir and then the plugin info
	$pluginRef = get_home_path();
	$pluginRef.='/wp-content/plugins/'.$pluginName.'/'.$pluginName.'.php';
	$pluginInfo = get_plugin_data( $pluginRef , true );	
	$pluginFullName = $pluginInfo['Name'];
	$pluginVersion = $pluginInfo['Version'];
	
	echo '<h1>Plugin overview : '.$pluginFullName.'</h1>';
	echo '<a href="admin.php?page=multisite-auditor-plugins">Back to plugin list</a><hr/>';
	
	

	$blogList = MSA_functions::getBlogsUsingPlugin($pluginName);
	
	$blogCount = count($blogList);
	
	echo $blogCount.' blogs currently using this plugin.<br/><br/>';
	
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
		
		
		echo '<table id="pluginBlogsTable">';
		echo '<thead><tr><th width="10">ID</th><th>Blog Name</th><th>URL</th><th>Date Created</th></thead>';		
		echo $tableStr;
		echo '</table>';
		
		
		?>
		<script>
			jQuery(document).ready(function(){	
				if (jQuery('#pluginBlogsTable').length>0)
				{
					jQuery('#pluginBlogsTable').dataTable({
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
	