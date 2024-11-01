<?php
/*
Plugin Name: WP-Door
Plugin URI: http://rjeevan.com
Description: Redirect To a Page When user visit First Time. No Cookie / Session Found Than This Plug In Will Redirect to a Specific Page...
Version: 0.2
Author: Rajeevan
Author URI: http://rjeevan.com
*/

### Load WP-Config File If This File Is Called Directly
if (!function_exists('add_action')) {
	$wp_root = '../../..';
	if (file_exists($wp_root.'/wp-load.php')) {
		require_once($wp_root.'/wp-load.php');
	} else {
		require_once($wp_root.'/wp-config.php');
	}
}

### Use WordPress 2.6 Constants
if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}

### Function: WP Door Administration Menu
add_action('admin_menu', 'door_admin_menu');
function door_admin_menu()
{
	// Add as option For Settings
	add_options_page("WP Door Options","Door Options",8,"wp-door/door-options.php");
}

### Function: Check for Visit and Redirect
add_action('wp', 'door_rdirect');
function door_rdirect()
{
	// Exception to Admin Pages
	if(strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false || strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !==false || strpos($_SERVER['REQUEST_URI'], '/feed/') !==false)
		return;	// Admin Don't need to Cnfirm
		
	@session_start();
	
	// Check for BOT
	if(is_bot())
		return;	// No need to take actions
		
	$validated=false;	// Hold Vaidation
	
	// Check Previous Visit / Session
	if(!empty($_COOKIE["door_validated"]))
		$validated=true;
	
	// Check Session IF No Cookie Validation Found!
	if(!$validated && isset($_SESSION['door_validated']))
		$validated=true;
	
	// Check Option
	$opt_value=get_option("door_timeout");
	if(is_numeric($opt_value) && $opt_value==-1 && !isset($_SESSION['door_validated']))
		 $validated=false;
	
	if($validated)
		return;	// All Set!
	
	// Redirect To Specified Page
	$door_page=get_option("door_page");
	if(!ereg("http://",$door_page))
		$door_page=get_option("siteurl").$door_page;
		
	$door_url=$door_page."?return=".$_SERVER['REQUEST_URI']."&e=".get_option("door_timeout");
	//echo "<!--".$door_url."-->";
	wp_redirect($door_url);
	exit(0);
}


### Function: Detect is is BOT Crawling From Users List
function is_bot()
{

	// Getting bots LIST from OPTIONs & Split them & save as Array
	$botsList=get_option("door_bots");
	$botsList=strtolower(str_replace(" ","",str_replace("\r","",$botsList)));
	$bots_array=split(";",$botsList);
	
	// If None Found, Use Default as Bots
	if(count($bots_array)<=0)
		$bots_array=split(";","Teoma;alexa;froogle;inktomi;looksmart;URL_Spider_SQL;Firefly;NationalDirectory;Ask Jeeves;TECNOSEEK;InfoSeek;WebFindBot;girafabot;crawler;www.galaxy.com;Googlebot;Scooter;Slurp;appie;FAST;WebBug;Spade;ZyBorg;rabaz;Google;Lycos;Spider;SideWinder;Bot;Jeeves;yahoo;ia_archiver");
			
	// Check for BOTS and return IF BOT found!
	$found_bot=false;	
	foreach($bots_array as $bot)
	{
						
		if(stristr($_SERVER["HTTP_USER_AGENT"],$bot))
		{
			$found_bot=true;
			break;
		}
	}
	
	return $found_bot;	// if no bot found!
	
}




/* INSTALL & UNINSTALL */

### Function: Install
function door_install()
{
	// Set Options
	add_option("door_version","0.2","","no");
	add_option("door_timeout","3600",'',"no");	// Default 1 Hr (time()+3600)
	add_option("door_page",get_option("siteurl")."/door.php");
	add_option("door_bots","Teoma;alexa;froogle;inktomi;looksmart;URL_Spider_SQL;Firefly;NationalDirectory;Ask Jeeves;TECNOSEEK;InfoSeek;WebFindBot;girafabot;crawler;www.galaxy.com;Googlebot;Scooter;Slurp;appie;FAST;WebBug;Spade;ZyBorg;rabaz;Google;Lycos;Spider;SideWinder;Bot;Jeeves;ia_archiver;yahoo",'',"no");
}

function door_uninstall()
{
	// Delete All Options
	delete_option("door_version");
	delete_option("door_timeout");
	delete_option("door_bots");
	delete_option("door_page");
}

// Install / Uninstall Functions
register_activation_hook(__FILE__,"door_install");
register_deactivation_hook(__FILE__,"door_uninstall");

?>