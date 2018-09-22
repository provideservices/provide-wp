<?php
/*
Plugin Name: Provide-WP
Description: Integrate your WordPress site with provide.services
Version: 0.1.0
Author: web3devs
Author URI: http://web3devs.com
License: GPLv2 or later
*/

// Create admin settings page

require('lib/provide-php/src/goldmine.php');
require('lib/vendor/autoload.php');

add_action( 'admin_menu', 'providewp_menu' );

/** Step 1. */
function providewp_menu() {
	add_options_page( 'Provide Settings', 'Provide Settings', 'manage_options', 'providewp-plugin-identifier', 'providewp_options' );
}

/** Step 3. */
function providewp_options() {
			
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if( isset( $_GET[ 'tab' ] ) ) {
    	$active_tab = $_GET[ 'tab' ];
	} else {
		$active_tab = "api_key";
	}
	
	echo '<div class="wrap">';
	// variables for the field and option names 
    $opt_name = 'mt_provide_api_key';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_provide_api_key';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put a "settings saved" message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Provide Settings', 'menu-test' ) . "</h2>";

    // settings form
    
    ?>
<h2 class="nav-tab-wrapper">
    <a href="?page=providewp-plugin-identifier&tab=api_key" class="nav-tab <?php echo $active_tab == 'api_key' ? 'nav-tab-active' : ''; ?>">API key</a>
    <a href="?page=providewp-plugin-identifier&tab=documents" class="nav-tab <?php echo $active_tab == 'documents' ? 'nav-tab-active' : ''; ?>">Documents</a>
    <a href="?page=providewp-plugin-identifier&tab=warranties#" class="nav-tab <?php echo $active_tab == 'warranties' ? 'nav-tab-active' : ''; ?>">Warranties</a>
</h2>
<?php if ($active_tab == "api_key") { ?>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p>
	<?php _e("API Key:", 'menu-test' ); ?>
<input type="text" id="provide-api-key" name="<?php echo $data_field_name; ?>" value="<?php echo get_option($data_field_name); ?>">
</p>

<?php
$api_key = get_option( 'mt_provide_api_key' );
if ($api_key) {
	$test = new Goldmine("https","goldmine.provide.services",$api_key);

	$res = $test->fetch_contracts();
	print_r($res);
	echo "<select>";
	foreach ($res as $contract) {
		echo "<option>".$contract->name."</option>";
	}
	echo "</select>";
}
?>

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
<?php } ?>

</form>


<?php
	if ($active_tab == "documents") {
		echo "<h2>Which page has your terms of use?</h2>";
		$pages = get_pages();

		echo "<select>";
		foreach ($pages as $page) {
			echo "<option>".$page->post_title."</option>";
		}
		echo "</select><br />";
		
		echo "<button class='button-primary'>Write to blockchain</button>";
	}


}


?>