<?php

	global $wpdb;
	
	echo '<h1>Plugins</h1> ';
	
	// Firstly get a blank of array of the themes
	$currentPlugins = get_plugins();

	echo '<table id="pluginsTable">';
	echo '<thead><tr><th>Plugins</th><th>Description</th><th>Number</th></thead>';		
	
	foreach($currentPlugins as $pluginRef => $pluginObject)
	{
		
		$pluginIsNetworkActivated = false;
		$rowClass="";
		$tdClass = "";
		if ( is_plugin_active_for_network( $pluginRef ) ) {
			// Plugin is activated
			$pluginIsNetworkActivated = true;
			$rowClass="active";
			$tdClass = "tdActive";
		}		
		
		
		$thisPluginName = $pluginObject['Name'];
		$pluginDescription = $pluginObject['Description'];
		$version = $pluginObject['Version'];
		
		$thisPlugin = MSA_functions::getPluginNameFromRef($pluginRef);
		
		// Get the number of sites using this plugin
		$blogList = MSA_functions::getBlogsUsingPlugin($thisPlugin);
		$pluginUseCount=0;
		$pluginUseCount=count($blogList);
		
		$pluginLink = "admin.php?page=multisite-auditor-plugin-info&pluginName=".$thisPlugin;

		echo '<tr class="'.$rowClass.'">';
		echo '<td valign="top" width="200px" class="'.$tdClass.'">';
		echo '<span style="font-size:14px"><a href="'.$pluginLink.'">'.$thisPluginName.'</a></span>';
		if($pluginIsNetworkActivated==true)
		{
			echo '<br/>Network activated';
		}
		echo '</td>';
		echo '<td valign="top" style="color:#808080;">';
		echo $pluginDescription;
		if($version){echo '<br/><span style="font-size:9px">Version '.$version.'</span>';}
		echo '</td>';
		echo '<td width="100px" valign="top">';
		echo $pluginUseCount;
		echo '</td>';
		echo '</tr>';			
	}
	
	// Add the missing blogs as well
	echo '</table>';
	?>
	<script>
		jQuery(document).ready(function(){	
			if (jQuery('#pluginsTable').length>0)
			{
				jQuery('#pluginsTable').dataTable({
					"bAutoWidth": true,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength": 50 // How many numbers by default per page
				});
			}
			
		});
	</script>	
