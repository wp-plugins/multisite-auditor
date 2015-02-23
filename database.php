<?php

$checkMSA_database = new MSA_database(); 

class MSA_database
{
	function __construct()
	{
		
		/**
		 * Description: Creates database tables used by the auditor
		 */
		 
		
		//Database table versions
		global $this_MSA_version;
		$this_MSA_version = "1.0"; // INcrease this each time a DB change is made
		
		// CHeck the DB version of the plugin
		$current_MAS_db_version = get_option( 'MSA-db-version' );
		
		
		if($current_MAS_db_version==false) // add the option and create DB
		{
			$this->MSA_db_create();
			add_option( 'MSA-db-version', $this_MSA_version);
			
		}
		elseif($current_MAS_db_version<$this_MSA_version) // update the option and update DB
		{
			$this->MSA_db_create();
			update_option( 'MSA-db-version', $this_MSA_version );
		}
	}
	
	//Create tables - uses the dbDelta stuff that looks to see if the tables already exist and udpates if not
	function MSA_db_create()
	{
		global $wpdb;
		global $this_MSA_version;
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
			
		$table_name = $wpdb->base_prefix . "MSA_themes";		
		$sql = "CREATE TABLE ".$table_name." (
		blogID int NOT NULL,
		themeName varchar(255),
		blogURL varchar(255),
		blogName varchar (255),
		dateCreated datetime,
		activateDate datetime,
		PRIMARY KEY  (blogID)
		);";
		dbDelta($sql);
		
		$table_name = $wpdb->base_prefix . "MSA_plugins";		
		$sql = "CREATE TABLE ".$table_name." (
		ID int NOT NULL AUTO_INCREMENT,
		blogID int,
		pluginName varchar(255),
		blogURL varchar(255),
		blogName varchar (255),
		dateCreated datetime,		
		PRIMARY KEY  (ID)
		);";
		dbDelta($sql);		
		
	}

}

?>
