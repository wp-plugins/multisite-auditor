<?php


$createMSA_networkPages = new MSA_pages(); 

class MSA_pages {
	
	
	var $menuSlug = 'multisite-auditor-overview';	
	
	
	function __construct()
	{		
		add_action( 'network_admin_menu', array( $this, 'MSA_addNetworkAdminPage' ));
	}		


	/**
	*	Registers the Network Admin pages
	*/
	function MSA_addNetworkAdminPage()
	{
		//--- Root page
		$page_title = "Overview";
		$menu_title = "Multisite Auditor";
		$capability = "manage_network_options"; //'manage_options' for administrators.
		$function = array( $this, 'drawMSA_overview' );
		
		$handle = add_menu_page( $page_title, $menu_title, $capability, $this->menuSlug, $function );
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_Overview') ); 
		

		//--- Sites  page
		$page_title="Sites";
		$menu_title="Sites";
		$capability="administrator";
		$menu_slug="multisite-auditor-sites";
		$function= array( $this, 'drawMSA_sites');		
		
		$handle = add_submenu_page($this->menuSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_Sites') );


		
		//--- Themes page
		$page_title="Themes";
		$menu_title="Themes";
		$capability="administrator";
		$menu_slug="multisite-auditor-themes";
		$function= array( $this, 'drawMSA_themes');		
		
		$handle = add_submenu_page($this->menuSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_Themes') );
		
		//sub Themes - info (hidden)
		$parentSlug = "multisite-auditor-themes";
		$page_title="Themes Blog List";
		$menu_title="Themes Blog List";
		$capability="administrator";
		$menu_slug="multisite-auditor-theme-info";
		$function= array( $this, 'drawMSA_theme_info');		
		
		$handle = add_submenu_page($parentSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_ThemesInfo') );
		
		//sub Themes info - swap (hidden)
		$parentSlug = "multisite-auditor-theme-info";
		$page_title="Theme Swap";
		$menu_title="Theme Swap";
		$capability="administrator";
		$menu_slug="multisite-auditor-theme-swap";
		$function= array( $this, 'drawMSA_theme_swap');		
		
		$handle = add_submenu_page($parentSlug, $page_title, $menu_title, $capability, $menu_slug, $function);		
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_ThemesSwap') );
		
		
		//--- Plugins page
		$page_title="Plugins";
		$menu_title="Plugins";
		$capability="administrator";
		$menu_slug="multisite-auditor-plugins";
		$function= array( $this, 'drawMSA_plugins');
		
		$handle = add_submenu_page($this->menuSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_Plugins') );
		
		//sub Plugins - info (hidden)
		$parentSlug = "multisite-auditor-plugins";
		$page_title="Plugin Blog List";
		$menu_title="Plugin Blog List";
		$capability="administrator";
		$menu_slug="multisite-auditor-plugin-info";
		$function= array( $this, 'drawMSA_plugin_info');		
		
		$handle = add_submenu_page($parentSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		add_action( 'admin_head-'. $handle, array($this, 'addScripts_PluginsInfo') );
	}		
	
	
	
	//~~~~~ Scripts per admin screen
	function addScripts_Overview() {
		global $GCharts;
		$GCharts->enqueueScripts();
		$this->enqueueCommonScripts();
	}
	
	function addScripts_Sites () {
		$this->enqueueCommonScripts();
	}	
	
	function addScripts_Themes () {
		$this->enqueueCommonScripts();
	}
	
	function addScripts_ThemesInfo () {
		$this->enqueueCommonScripts();
	}
	
	function addScripts_ThemesSwap () {
		$this->enqueueCommonScripts();
	}
	
	function addScripts_Plugins () {
		$this->enqueueCommonScripts();
	}
	
	function addScripts_PluginsInfo () {
		$this->enqueueCommonScripts();
	}
	
	
	
	//~~~~~ common scripts and style
	function enqueueCommonScripts ()
	{
		global $wp_scripts;	

		// Allow the poopup thickbox to appear all pages
		add_thickbox();
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		
		// get the jquery ui object
		$queryui = $wp_scripts->query('jquery-ui-core');
		// load the jquery ui theme
		$url = "https://ajax.googleapis.com/ajax/libs/jqueryui/".$queryui->ver."/themes/smoothness/jquery-ui.css";	
		wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
	
		//dataTables js
		wp_register_script( 'datatables', ( '//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js' ), false, null, true );
		wp_enqueue_script( 'datatables' );
		
		//dataTables css
		wp_enqueue_style('datatables-style','//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css');	
		
		//plugin scripts and styles
		wp_register_style( 'MSA_styles',  plugins_url('/scripts/styles.css',__FILE__) );
		wp_enqueue_style( 'MSA_styles' );
		
		wp_enqueue_script('MSA_custom', plugins_url('/scripts/js-functions.js',__FILE__) ); #Custom JS functions
		
	}
	
	
	
	//~~~~~ Drawing
	function drawMSA_overview()
	{
		include_once( dirname(__FILE__) . '/admin/index.php');
	}
	
	
	//~~~~~ Drawing
	function drawMSA_sites()
	{
		if( get_option( 'MSA_run_date' ) )
		{
			include_once( dirname(__FILE__) . '/admin/sites.php');
		}
		else
		{
			include_once( dirname(__FILE__) . '/admin/empty.php');
		}
	}	
	
	
	
	  
  
	function drawMSA_themes()
	{
		if( get_option( 'MSA_run_date' ) )
		{
			include_once( dirname(__FILE__) . '/admin/themes.php');
		}
		else
		{
			include_once( dirname(__FILE__) . '/admin/empty.php');
		}
	} 
	
	
	function drawMSA_theme_info()
	{
		include_once( dirname(__FILE__) . '/admin/theme_info.php');
	}
	
	
	function drawMSA_theme_swap()
	{
		include_once( dirname(__FILE__) . '/admin/theme_swap.php');
	}
	
	  
	function drawMSA_plugins()
	{
		if( get_option( 'MSA_run_date' ) )
		{
			include_once( dirname(__FILE__) . '/admin/plugins.php');
		}
		else
		{
			include_once( dirname(__FILE__) . '/admin/empty.php');
		}
	}
	
	
	function drawMSA_plugin_info()
	{
		include_once( dirname(__FILE__) . '/admin/plugin_info.php');
	}	
	

}

?>