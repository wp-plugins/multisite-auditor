<?php
/*
Plugin Name: Multisite Auditor
Plugin URI: http://www.iliad.soton.ac.uk
Description: Cut down multisite theme and plugin auditor
Version: 1.0
Author: Alex Furr, Simon Ward
*/


$MSA_path = dirname(__FILE__);
include_once( $MSA_path . '/functions.php');
include_once( $MSA_path . '/database.php');
include_once( $MSA_path . '/pages.php');
include_once( $MSA_path . '/google-charts/class-googlecharts.php');
$GCharts = new gCHARTS();


?>