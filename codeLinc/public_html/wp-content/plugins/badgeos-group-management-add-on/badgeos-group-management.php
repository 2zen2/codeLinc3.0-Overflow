<?php
/**
 * Plugin Name: BadgeOS Group Management Add-On
 * Plugin URI: http://www.badgeos.org/
 * Description: This BadgeOS add-on integrates BadgeOS features with Schools.
 * Text Domain: badgeos-group-management
 * Author: LearningTimes
 * Version: 1.0.1.1
 */

class BadgeOS_Group_Management {

    public $basename;
    public $directory_path;
    public $directory_url;


    function __construct() {

		// Define plugin constants
		$this->basename       = plugin_basename( __FILE__ );
		$this->directory_path = plugin_dir_path( __FILE__ );
        $this->directory_url = plugin_dir_url( __FILE__ );

		// Load translations
		load_plugin_textdomain( 'badgeos-group-management', false, 'badgeos-group-management/languages' );

		// Run our activation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// If BadgeOS is unavailable, deactivate our plugin
		add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );
		add_action( 'plugins_loaded', array( $this, 'badgeos_includes' ) );

        // Add Schools menu for Super admin
        add_action( 'admin_menu', array( $this, 'badgeos_group_management_menu' ) );

        // Register and load admin scripts
	    add_action('admin_enqueue_scripts', array($this,'badgeos_group_management_admin_enqueue_scripts'), 999);
	}

	/**
	 * Files to include for BadgeOS integration.
	 *
	 * @since  1.0.0
	 */
	public function badgeos_includes() {

        ob_start();

		if ( $this->meets_requirements() ) {

			require_once( $this->directory_path . '/includes/badgeos-group-management-roles.php' );

			if($this->check_group_management_group_roles_current_env() != false){

				require_once( $this->directory_path . '/includes/badgeos-users.php' );
				require_once( $this->directory_path . '/includes/submission-filters.php' );
				require_once( $this->directory_path . '/includes/badgeos-user-list.php' );
				require_once( $this->directory_path . '/includes/badgeos-group-management-functions.php' );

			}
		}
	}

    /**
    * Enqueue custom scripts and styles
    *
    * @since 1.0.0
    */
	public function badgeos_group_management_admin_enqueue_scripts(){

		// If need to load css for admin pages
		if ( isset( $_GET[ 'page' ]) && ($_GET['page']=='badgeos-group-management' || $_GET['page']=='bp-groups') ) {
			wp_enqueue_script( 'badgeos-group-management', $this->directory_url . 'js/badgeos-group-management.js', array( 'jquery' ) );
            wp_enqueue_script('user-profile');
			wp_localize_script(
				'badgeos-group-management',
				'badgeos_group_management',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
					'prompt_school' => __( 'Please enter a school name', 'badgeos-group-management' )
				)
			);
		}

	}

    /**
     * Create BadgeOS School menu
     *
     * @since 1.0.0
     */
    public function badgeos_group_management_menu(){
        //Add Badgeos add new school menu in admin dashboard
        add_menu_page('BadgeOS Group Management Plugin','BadgeOS Group Management','administrator', 'badgeos-group-management-settings', 'badgeos_group_roles_setup_page',$GLOBALS['badgeos']->directory_url . 'images/badgeos_icon.png');

		// Create submenu for Roles setup
		add_submenu_page( 'badgeos-group-management-settings', 'Roles' , 'Roles', 'administrator', 'badgeos-group-management-settings', 'badgeos_group_roles_setup_page' );

		if($this->check_group_management_group_roles_current_env() != false) {
			//Show create school page
			add_submenu_page('badgeos-group-management-settings', 'Create Schools', 'Create Schools', 'administrator', 'badgeos-group-management', 'badgeos_schools_creation');
		}

    }


	/**
	 * Check group management roles from admin screen
	 *
	 * @since 1.0.1
	 *
	 */
	public function check_group_management_group_roles_current_env(){

		//Get all roles from present environment
		$all_roles = wp_roles()->roles;

		$editable_roles = apply_filters( 'editable_roles', $all_roles );

		$roles = array();

		foreach ($editable_roles as $role_name => $role_info){
			array_push($roles, $role_name);
		}

		$teacher_option_name = 'badgeos_group_management_teacher_role';
		$student_option_name = 'badgeos_group_management_student_role';

		$teacher_role = get_option($teacher_option_name);
		$student_role = get_option($student_option_name);

		//If group management roles doesnot exists in default setup options
		if( !$teacher_role && !$student_role){

			if( in_array('subscriber', $roles) && in_array('author' , $roles)){

				//Setup group management roles if subscriber & author role exists in current wordpress environment
				add_option( $teacher_option_name , 'author' , null , 'yes' );
				add_option( $student_option_name , 'subscriber' , null , 'yes' );

				return true;

			}else{

				//If the roles does not exists in current wordpress roles
				return false;
			}

		}else{

			/*
             * If the role present in user defined roles values in wp_option table
             * But not in current wordpress environment all roles values
             * May be, These roles are removed by some other plugins used
             */

			if( current_user_can('administrator') ){
				if(!in_array($student_role , $roles) || !in_array($teacher_role , $roles)){
					return false;
				}
			}

			return true;

		}
	}


	/**
	 * Activation hook for the plugin.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
        // Do some activation things.
	}

    /**
     * Deactivation hook for the plugin.
     *
     * @since 1.0.0
     */
    public function deactivate() {

        // Do some deactivation things. Note: this plugin may
        // auto-deactivate due to $this->maybe_disable_plugin()

    }

	/**
	 * Check if BadgeOS is available
	 *
	 * @since  1.0.0
	 * @return bool True if BadgeOS is available, false otherwise
	 */
	public static function meets_requirements() {

		if ( class_exists( 'BadgeOS' ) && class_exists( 'BuddyPress' ) && class_exists( 'BuddyPress_Invite_Codes' ))
			return true;
		else
			return false;
	}

	/**
	 * Generate a custom error message and deactivates the plugin if we don't meet requirements
	 *
	 * @since 1.0.0
	 */
	public function maybe_disable_plugin() {
        if ( !$this->meets_requirements() ) {
            // Display our error
            echo '<div id="message" class="error">';

            if ( !class_exists( 'BadgeOS' ) || !function_exists( 'badgeos_get_user_earned_achievement_types' ) ) {
                echo '<p>' . sprintf( __( 'BadgeOS Group Management requires BadgeOS and has been <a href="%s">deactivated</a>. Please install and activate BadgeOS and then reactivate this plugin.', 'badgeos-group-management' ), admin_url( 'plugins.php' ) ) . '</p>';
            }
            elseif ( !class_exists( 'BuddyPress' ) ) {
                echo '<p>' . sprintf( __( 'BadgeOS Group Management requires BuddyPress and has been <a href="%s">deactivated</a>. Please install and activate BuddyPress and then reactivate this plugin.', 'badgeos-group-management' ), admin_url( 'plugins.php' ) ) . '</p>';
            }
            elseif ( !class_exists( 'BuddyPress_Invite_Codes' ) ) {
                echo '<p>' . sprintf( __( 'BadgeOS Group Management requires BuddyPress Invite Codes be enabled and has been <a href="%s">deactivated</a>. Please activate <a href="%s">BuddyPress Invite Codes</a> and then reactivate this plugin.', 'badgeos-group-management' ), admin_url( 'plugins.php' ),admin_url( 'plugins.php' ) ) . '</p>';
            }

            echo '</div>';

            // Deactivate our plugin
            deactivate_plugins( $this->basename );

		} else {

			if($this->check_group_management_group_roles_current_env() == false) {
				//display the admin notice
				printf(__('<div class="notice notice-error"><p>Note: BadgeOS Group Management is almost ready to use. You must <a href="%s">define your role structure</a> for the plugin to work.</p></div>', 'badgeos'), admin_url('admin.php?page=badgeos-group-management-settings'));
			}
		}
	}

}
$GLOBALS['badgeos_group_management'] = new BadgeOS_Group_Management();
