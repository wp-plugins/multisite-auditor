<?php

global $wpdb;	
$oldTheme = $_GET['oldTheme'];
$newTheme = $_GET['newTheme'];	

echo '<h1>Theme Swap</h1> ';
$totalBlogsLeft = $blogsToSwap =  MSA_functions::getBlogsOnTheme($oldTheme);
echo '<h2>'.count($totalBlogsLeft).' blogs left to swap</h2>';

$blogsToSwap =  MSA_functions::getBlogsOnTheme($oldTheme, 10);
$blogCount = count($blogsToSwap);
if($blogCount==0)
{
?>
	<script>
    window.location.replace("?page=multisite-auditor-themes&action=themeSwapComplete");
    </script>
<?php
}
else
{
	
	// Get the theme info in case its a child theme
	$newThemeInfo= wp_get_theme( $newTheme );
	$newTemplate = $newThemeInfo->get( 'Template' );
	
	if($newTemplate==""){$newTemplate = $newTheme;}
	

	foreach($blogsToSwap as $blogID => $blogInfo)
	{		
		$blogID = $blogInfo['blogID'];
		$blogName = $blogInfo['blogName'];
		switch_to_blog($blogID);
		update_option('template', $newTemplate);
		update_option('stylesheet', $newTheme);
		update_option('current_theme', $newTheme);
		
		// Now update the theme in the master theme DB as well
		MSA_functions::MSA_updateThemeFromSwap($blogID, $newTheme);
		
		
		echo 'Swapping theme for Blog "'.$blogName.'"<hr/>';
		

	}
	
	
	
}
?>
<script>
window.location.replace("?page=multisite-auditor-theme-swap&oldTheme=<?php echo $oldTheme ?>&newTheme=<?php echo $newTheme ?> ");
</script>