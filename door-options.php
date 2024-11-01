<?php
/*
+----------------------------------------------------------------+
|																 |
|	WordPress 2.6.1 Plugin: WP-Door 0.2							 |
|	Copyright (c) 2008 Rajeevan									 |
|																 |
|	File Written By:											 |
|	- Rajeevan													 |
|	- http://rjeevan.com										 |
|																 |
|	File Information:											 |
|	- Configure WP Door										 |
|	- wp-content/plugins/wp-door/door-options.php				 |
|																 |
+----------------------------------------------------------------+
*/


if($_POST['Submit'])
{
	// SAVING ACTION
	$door_timeout=isset($_POST['door_timeout']) && is_numeric($_POST['door_timeout'])?$_POST['door_timeout']:'3600';
	$door_bots=isset($_POST['door_bots'])?$_POST['door_bots']:'';
	$door_page=isset($_POST['door_page'])?$_POST['door_page']:'/';
	
	// SET BOT
	$door_bots=str_replace("\n",";",$door_bots);
	$door_bots=str_replace("\t","",$door_bots);
	$door_bots=str_replace("\r","",$door_bots);
	
	// Update
	$update_query=array();
	$update_text=array();
	
	$update_query[]=update_option("door_timeout",$door_timeout);
	$update_query[]=update_option("door_bots",$door_bots);
	$update_query[]=update_option("door_page",$door_page);
	
	$update_text[]=__("Expire Time ","wp-door");
	$update_text[]=__("Bots List ","wp-door");
	$update_text[]=__("Redirect Page ","wp-door");
	
	$i = 0;
	$text = '';
	foreach($update_query as $u_query) {
		if($u_query) {
			$text .= '<font color="green">'.$update_text[$i].' '.__('Updated', 'wp-reportpost').'</font><br />';
		}
		$i++;
	}
	if(empty($text)) {
		$text = '<font color="red">'.__('No Option Updated', 'wp-reportpost').'</font>';
	}
	
}

// Display Value Needed
$door_version=get_option("door_version");
$door_timeout=get_option("door_timeout");
$door_bots=get_option("door_bots");
$door_page=get_option("door_page");

$door_bots=str_replace(";","\n",$door_bots);
?>

<div class="wrap"> 
	<h2><?php _e('WP Door Options', 'wp-reportpost'); ?></h2> 
	
	<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
	
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
	
	<?php wp_nonce_field('update-options'); ?>

	<table class="form-table">
		<tr>
			<th scope="row" valign="top">Version:</th>
			<td><?php echo get_option("door_version");?></td>
		</tr>
		<tr>
			<th scope="row" valign="top">Redirect URL:</th>
			<td><input type="text" name="door_page" value="<?php echo $door_page; ?>" /></td>
		</tr>
		<tr>
			<th scope="row" valign="top">Cookie Expire in:</th>
			<td><input type="text" name="door_timeout" value="<?php echo $door_timeout; ?>" /> Seconds<br />0=Never Expire / -1 = Allways Ask(Once Per Session)</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Bot Lists:<br />One Per line</th>
			<td><textarea name="door_bots" rows="15" cols="40"><?php echo $door_bots; ?></textarea></td>
		</tr>
	</table>
	
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
</div>