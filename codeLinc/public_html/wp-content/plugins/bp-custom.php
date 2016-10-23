<?php

/* set default BP to achievements page instead of activity page and removes unnecessaries */
define( 'BP_DEFAULT_COMPONENT', 'achievements' );

function my_remove_em_nav() {
	global $bp;
      bp_core_remove_nav_item( 'groups', 'forums', 'settings');
      bp_core_remove_subnav_item( 'settings', 'groups' );

}
add_action( 'bp_init', 'my_remove_em_nav' );

?>