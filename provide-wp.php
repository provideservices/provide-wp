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
	echo '<div class="wrap">';
	// variables for the field and option names 
    $opt_name = 'mt_favorite_color';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_favorite_color';

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

<?php
$test = new Goldmine("https","goldmine.provide.services","eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7fSwiZXhwIjpudWxsLCJpYXQiOjE1Mzc1NTk1MzQsImp0aSI6ImY5ZGI1Mzc0LTM1OTktNDQwMi1hNTVjLWFlYTM0N2Y3ZWU0NyIsInN1YiI6ImFwcGxpY2F0aW9uOmFlOThhZWVjLWEzOGItNDhlOS04NDBiLWQ3MWM0NjE3Mjc0MCJ9.wIvhWyc0TwHC2oe4942FDFINsqrTDUqXn-3bnj75oSU");

$res = $test->fetch_contracts();
print_r($res);

}


?>