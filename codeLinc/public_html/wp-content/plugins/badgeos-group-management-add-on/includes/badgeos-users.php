<?php
/**
 * Create new School Admin role and associate with capabilities
 *
 * @since 1.0.0
 *
 * @param WP_Roles $roles Roles object to add default roles to
 */
function badgeos_init_roles(){

    // school admin role creation with certain permissions
    add_role('school_admin', __('School Admin','badgeos-group-management'),
        array(
            'read' => true,
            'list_users' => true,
            'create_users' => true,
            'delete_users' => true,
            'edit_users' => true,
            'manage_options' => true,
            'read_private_pages' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'delete_published_posts' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'edit_post' => true,
            'edit_others_posts' => true,
        )
    );
}
add_action('init', 'badgeos_init_roles');


/**
 * Access permission level updated for School Admin and Author role
 *
 * @since 1.0.0
 */
function badgeos_add_capabilities_to_user_roles( ) {

    // Add capabilities to School Admin
    $role_school_admin = get_role( 'school_admin' );
    $role_school_admin->add_cap( 'create_users' );
    $role_school_admin->add_cap( 'list_users' );
    $role_school_admin->add_cap( 'edit_users' );
    $role_school_admin->add_cap( 'delete_users' );
    $role_school_admin->add_cap( 'upload_files' );
    $role_school_admin->add_cap( 'edit_post' );
    $role_school_admin->add_cap( 'edit_posts' );
    $role_school_admin->add_cap( 'edit_others_posts' );

    // Add capabilities to Author role
    $role_author = get_role( get_option('badgeos_group_management_teacher_role') );
    $role_author->add_cap( 'create_users' );
    $role_author->add_cap( 'list_users' );
    $role_author->add_cap( 'edit_users' );
    $role_author->add_cap( 'delete_users' );
    $role_author->add_cap( 'manage_options' );
    $role_author->add_cap( 'edit_others_posts' );
    $role_author->add_cap( 'edit_posts' );
}
add_action( 'init', 'badgeos_add_capabilities_to_user_roles' );


/**
 * Unwanted menus are removed for School Admin and Author role
 *
 * @since 1.0.0
 *
 */
function badgeos_remove_menus() {

    global $submenu;

    // If the user does not have access to following menu page
    if(is_user_logged_in() && (current_user_can('school_admin') || current_user_can(get_option('badgeos_group_management_teacher_role')))) {

        remove_menu_page( 'shortcodes-ultimate' );
        remove_menu_page( 'edit.php?post_type=acf' );
        remove_menu_page( 'register-plus-redux' );
        remove_menu_page( 'bp-activity' );
        remove_menu_page( 'edit.php' );
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'options-general.php' );
        remove_menu_page( 'index.php' );
        remove_menu_page( 'upload.php' );

        //Remove BadgeOS  submenu only school admin
        remove_submenu_page( 'badgeos_badgeos', 'edit.php?post_type=achievement-type' );
        remove_submenu_page( 'badgeos_badgeos', 'edit.php?post_type=nomination');
        remove_submenu_page( 'badgeos_badgeos', 'edit.php?post_type=badgeos-log-entry');
        remove_submenu_page( 'badgeos_badgeos', 'badgeos_settings');
        remove_submenu_page( 'badgeos_badgeos', 'badgeos_sub_credly_integration');
        remove_submenu_page( 'badgeos_badgeos', 'badgeos_sub_add_ons');
        remove_submenu_page( 'badgeos_badgeos', 'badgeos_sub_help_support');

        //Remove "Manage Signup" submenu on school admin & authors dashboard
        unset($submenu['users.php'][17]);

        if(current_user_can(get_option('badgeos_group_management_teacher_role'))){
            remove_menu_page( 'bp_invite_codes_settings' );
            remove_menu_page( 'badgeos_badgeos' );
            remove_submenu_page( 'users.php', 'user-new.php' );
        }
    }
}
add_action( 'admin_menu', 'badgeos_remove_menus',999);


/**
 * Get students of a particular teacher
 *
 * @since 1.0.0
 *
 * @param integer $teacher_id Teacher ID
 * @return array List of student ID belongs to the teacher
 */
function badgeos_get_students($teacher_id){
    $args = array(
        'role'         => get_option('badgeos_group_management_student_role'),
        'meta_key'     => 'teacher_id',
        'meta_value'   => absint( $teacher_id ),
        'fields'       => 'ID'
    );

    // get list of users using arguments
    $result = get_users($args);
    return $result;
}
add_action('badgeos_get_students_of_teacher','badgeos_get_students');


/**
 * Get School ID of the logged in user
 *
 * @since 1.0.0
 *
 * @return int
 */
function badgeos_get_school_id(){

    // getting school id for user. If user not logged in result empty
    if( is_user_logged_in() && badgeos_get_user_role()=="school_admin" ){
        $school_id = get_current_user_id();
    }elseif( is_user_logged_in() ){
        $school_id = get_user_meta(get_current_user_id(), 'school_id', true);
    }
    return absint( $school_id );
}


/**
 * Fetching Teacher ID for logged in user
 *
 * @since 1.0.0
 *
 * @return int
 */
function badgeos_get_teacher_id(){

    // getting teacher id for user. If user not logged in result empty
    if(is_user_logged_in() && badgeos_get_user_role()==get_option('badgeos_group_management_teacher_role')){
        $teacher_id = get_current_user_id();
    }elseif(is_user_logged_in() && badgeos_get_user_role()==get_option('badgeos_group_management_student_role')){
        $teacher_id = get_user_meta(get_current_user_id(), 'teacher_id', true);
    }
    return absint( $teacher_id );
}


/**
 * Get logged in user role
 *
 * @since 1.0.0
 *
 * @param int $user_id
 * @return mixed
 */
function badgeos_get_user_role( $user_id = 0 ) {

    global $current_user;
    $badgeos_user_roles = (array) $current_user->roles;

    if(!empty($user_id)) {
        $badgeos_user    = get_userdata( $user_id );
        $badgeos_user_roles = $badgeos_user->roles;
    }
    $role = false;

    $badgeos_user_role = array_shift($badgeos_user_roles);

    return $badgeos_user_role;
}


/**
 * Disallow group creation option for students
 *
 * @since 1.0.0
 *
 * @return bool
 */
function bp_disable_group_creation_for_students(){

    if(badgeos_get_user_role()==get_option('badgeos_group_management_student_role')){
        $can_create = false;
    }else{
        $can_create = true;
    }

    return $can_create;
}
add_filter('bp_user_can_create_groups','bp_disable_group_creation_for_students');



/**
 * Control user roles based on school admin and author
 *
 * @since 1.0.0
 *
 * @return array
 */
function badgeos_schools_control_user_roles($all_roles) {

    if (!function_exists('get_current_screen')) {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
    }

    $screen = get_current_screen();

    if($screen->id == 'user'){
        $user_role = badgeos_get_user_role();
        if($user_role == "school_admin"){
            foreach($all_roles as $role => $capabilities){
                if($role != get_option('badgeos_group_management_teacher_role')){
                    unset($all_roles[$role]);
                }
            }
        }elseif($user_role == get_option('badgeos_group_management_teacher_role')){
            foreach($all_roles as $role => $capabilities){
                if($role != get_option('badgeos_group_management_student_role')){
                    unset($all_roles[$role]);
                }
            }
        }
    }
    return $all_roles;
}
add_filter('editable_roles', 'badgeos_schools_control_user_roles');


/**
 * Create a new school from admin screen
 *
 * @since 1.0.0
 *
 */
function badgeos_create_school_form_submission(){

    global $add_user_errors;
    global $wpdb;
    if ( isset($_REQUEST['action']) && 'badgeos_create_school' == $_REQUEST['action'] ) {
        check_admin_referer( 'create-user', '_wpnonce_create-user' );

        if ( ! current_user_can('create_users') )
            wp_die( __( 'Cheatin&#8217; uh?' ), 403 );

        if ( ! is_multisite() ) {
            $user_id = edit_user();

            if ( is_wp_error( $user_id ) ) {
                $add_user_errors = $user_id;
            } else {
                if ( current_user_can( 'list_users' ) )
                    $redirect = 'users.php?role=school_admin&update=add&id=' . $user_id;
                else
                    $redirect = admin_url("admin.php?page=badgeos-school");
                wp_redirect( $redirect );
                die();
            }
        } else {
            // Adding a new user to this site
            $new_user_email = wp_unslash( $_REQUEST['email'] );
            $user_details = wpmu_validate_user_signup( $_REQUEST['user_login'], $new_user_email );
            if ( is_wp_error( $user_details[ 'errors' ] ) && !empty( $user_details[ 'errors' ]->errors ) ) {
                $add_user_errors = $user_details[ 'errors' ];
            } else {
                /**
                 * Filter the user_login, also known as the username, before it is added to the site.
                 *
                 * @since 2.0.3
                 *
                 * @param string $user_login The sanitized username.
                 */
                $new_user_login = apply_filters( 'pre_user_login', sanitize_user( wp_unslash( $_REQUEST['user_login'] ), true ) );
                if ( isset( $_POST[ 'noconfirmation' ] ) && is_super_admin() ) {
                    add_filter( 'wpmu_signup_user_notification', '__return_false' ); // Disable confirmation email
                    add_filter( 'wpmu_welcome_user_notification', '__return_false' ); // Disable welcome email
                }
                wpmu_signup_user( $new_user_login, $new_user_email, array( 'add_to_blog' => $wpdb->blogid, 'new_role' => 'school_admin' ) );
                if ( isset( $_POST[ 'noconfirmation' ] ) && is_super_admin() ) {
                    $key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $new_user_login, $new_user_email ) );
                    wpmu_activate_signup( $key );
                    $redirect = add_query_arg( array('update' => 'addnoconfirmation'), $_REQUEST['_wp_http_referer'] );
                } else {
                    $redirect = add_query_arg( array('update' => 'newuserconfirmation'), $_REQUEST['_wp_http_referer'] );
                }
                wp_redirect( $redirect );
                die();
            }
        }
    }

}

/**
 * School Creation form integration
 */
function badgeos_schools_creation(){
global $add_user_errors;
badgeos_create_school_form_submission();
?>

<div class="wrap">
    <h2 id="add-new-user">
        <?php echo _x( 'Add New School', 'user' ); ?>
    </h2>

    <?php if ( isset($errors) && is_wp_error( $errors ) ) : ?>
        <div class="error">
            <ul>
                <?php
                foreach ( $errors->get_error_messages() as $err )
                    echo "<li>$err</li>\n";
                ?>
            </ul>
        </div>
    <?php endif;

    if ( ! empty( $messages ) ) {
        foreach ( $messages as $msg )
            echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';
    } ?>

    <?php if ( isset($add_user_errors) && is_wp_error( $add_user_errors ) ) : ?>
        <div class="error">
            <?php
            foreach ( $add_user_errors->get_error_messages() as $message )
                echo "<p>$message</p>";
            ?>
        </div>
    <?php endif; ?>
    <div id="ajax-response"></div>

    <form action="" method="post" name="createuser" id="createuser" class="validate" novalidate="novalidate"<?php do_action( 'user_new_form_tag' );?>>
        <input name="action" type="hidden" value="badgeos_create_school" />
        <?php wp_nonce_field( 'create-user', '_wpnonce_create-user' ); ?>
        <?php
        // Load up the passed data, else set to a default.
        $creating = isset( $_POST['createuser'] );
        $new_user_login = $creating && isset( $_POST['user_login'] ) ? wp_unslash( $_POST['user_login'] ) : '';
        $new_user_firstname = $creating && isset( $_POST['first_name'] ) ? wp_unslash( $_POST['first_name'] ) : '';
        $new_user_email = $creating && isset( $_POST['email'] ) ? wp_unslash( $_POST['email'] ) : '';
        $new_user_description = $creating && isset( $_POST['description'] ) ? wp_unslash( $_POST['description'] ) : '';
        $new_user_ignore_pass = $creating && isset( $_POST['noconfirmation'] ) ? wp_unslash( $_POST['noconfirmation'] ) : '';
        ?>
        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row" style="padding:0 0 30px 0px "><label for="first_name"><?php _e('School Name') ?> <span class="description"><?php _e('(required)'); ?></label></th>
                <td style="padding:0 0 30px 10px "><input name="first_name" type="text" id="first_name" value="<?php echo esc_attr($new_user_firstname); ?>" maxlength="50" />
                    <p class="description indicator-hint" style="position: absolute;"><?php _e('School name field accept, maximum of 50 characters only.'); ?></p>
                </td>
            </tr>

            <tr class="form-field">
                <th scope="row"><label for="description"><?php _e('School Description'); ?></label></th>
                <td><textarea name="description"  id="description" style="width: 30.5%" maxlength="250"><?php echo esc_attr( $new_user_description ); ?></textarea>
                    <p class="description indicator-hint"><?php _e('School description field accept, maximum of 250 characters only.'); ?></p>
                </td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row"><label for="user_login"><?php _e('Username'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input name="user_login" type="text" id="user_login" value="<?php echo esc_attr($new_user_login); ?>" aria-required="true" /></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="email"><?php _e('E-mail'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
                <td><input name="email" type="email" id="email" value="<?php echo esc_attr( $new_user_email ); ?>" /></td>
            </tr>

            <?php if ( !is_multisite() ) { ?>

                <?php
                /**
                 * Filter the display of the password fields.
                 *
                 * @since 1.5.1
                 *
                 * @param bool $show Whether to show the password fields. Default true.
                 */
                if ( apply_filters( 'show_password_fields', true ) ) : ?>
                    <tr class="form-field form-required user-pass1-wrap">
                        <th scope="row" style="padding: 0 0 30px 0px">
                            <label for="pass1">
                                <?php _e( 'Password' ); ?>
                                <span class="description hide-if-js"><?php _e( '(required)' ); ?></span>
                            </label>
                        </th>
                        <td style="padding: 0 0 30px 10px">
                            <input class="hidden" value=" " /><!-- #24364 workaround -->
                            <button type="button" class="button button-secondary wp-generate-pw hide-if-no-js"><?php _e( 'Show password' ); ?></button>
                            <div class="wp-pwd hide-if-js">
                                <?php $initial_password = wp_generate_password( 24 ); ?>
                                <span class="password-input-wrapper">
					<input type="password" name="pass1" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
				</span>
                                <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password' ); ?>">
                                    <span class="dashicons dashicons-hidden"></span>
                                    <span class="text"><?php _e( 'Hide' ); ?></span>
                                </button>
                                <button type="button" class="button button-secondary wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change' ); ?>">
                                    <span class="text"><?php _e( 'Cancel' ); ?></span>
                                </button>
                                <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                            </div>
                            <p><span class="description"><?php _e( 'A password reset link will be sent to the user via email.' ); ?></span></p>
                        </td>
                    </tr>
                    <tr class="form-field form-required user-pass2-wrap hide-if-js">
                        <th scope="row"><label for="pass2"><?php _e( 'Repeat Password' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
                        <td>
                            <input name="pass2" type="password" id="pass2" autocomplete="off" />
                        </td>
                    </tr>
                    <tr class="pw-weak">
                        <th><?php _e( 'Confirm Password' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                                <?php _e( 'Confirm use of weak password' ); ?>
                            </label>
                        </td>
                    </tr>
                    <input type="hidden" name="send_password" id="send_password" value="1" />
                    <input type="hidden" name="role" id="role" value="school_admin" />
                <?php endif; ?>
            <?php } // !is_multisite ?>
            <?php if ( is_multisite() && is_super_admin() ) { ?>
                <tr>
                    <th scope="row"><label for="noconfirmation"><?php _e('Skip Confirmation Email') ?></label></th>
                    <td><label for="noconfirmation"><input type="checkbox" name="noconfirmation" id="noconfirmation" value="1" <?php checked( $new_user_ignore_pass ); ?> /> <?php _e( 'Add the user without sending an email that requires their confirmation.' ); ?></label></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        /** This action is documented in wp-admin/user-new.php */
        do_action( 'user_new_form', 'add-new-user' );
        ?>
        <?php submit_button( __( 'Add New School '), 'primary', 'createuser', true, array( 'id' => 'create_school' ) ); ?>
    </form>
    <?php
}
