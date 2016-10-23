<?php

/**
 * Setup group management roles from admin screen
 *
 * @since 1.0.1
 *
 */
function badgeos_setup_group_management_roles_form_submission(){

    global $errors;

    $errors = new WP_Error();

    if ( isset($_REQUEST['action']) && 'badgeos_group_management_setup_roles' == $_REQUEST['action'] ) {

        check_admin_referer( 'group-roles', '_wpnonce_group-roles' );

        $teacher_role = trim($_REQUEST['teacher-role']);

        $student_role = trim($_REQUEST['student-role']);

        if ( $teacher_role == '' || $student_role == '') {

            $errors->add('group_management_roles',  __('Please select a role from each option.'));

        }else if ( $teacher_role == $student_role ){

            $errors->add('group_management_roles',  __('Students and Teachers can not have the same role, Please select different role.'));

        }else {

            $teacher_role_option = 'badgeos_group_management_teacher_role';
            $student_role_option = 'badgeos_group_management_student_role';

            if (get_option($teacher_role_option) !== false) {
                // The option already exists, so we just update it.
                update_option($teacher_role_option, $teacher_role);
            } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                add_option($teacher_role_option, $teacher_role , null, 'yes');
            }

            if (get_option($student_role_option) !== false) {
                // The option already exists, so we just update it.
                update_option($student_role_option, $student_role);
            } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                add_option($student_role_option, $student_role , null, 'yes');
            }

            return true;
        }
    }

    return false;
}

/**
 * Check group management roles from admin screen
 *
 * @since 1.0.1
 *
 */
function check_group_management_group_roles(){

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

/*
 * Check group mapping with user defined roles from
 * Group management roles setup page
 */

function check_group_management_roles_mapping(){

    $group_exists = false;

    //If group management role exists in user defined roles values, Then checks the group mapping between schools,teachers and students
    if( check_group_management_group_roles() != false) {

        //Get group mapped records based on school admin
        $user_query = new WP_User_Query(array('role' => 'school_admin'));
        $schools = $user_query->get_results();

        if ($schools && count($schools) > 0) {

            foreach ($schools as $key => $school) {
                //Get group mapping with school id from user meta table
                $query = new WP_User_Query(array('meta_key' => 'school_id', 'meta_value' => $schools->ID));
                $group_members = $query->get_results();

                //Find atleast one group from exists records
                if ($group_members && count($group_members) > 0) {
                    $group_exists = true;
                    break;
                }
            }

        }
    }

    return $group_exists;

}

/**
 * Group Management roles setup form integration
 */
function badgeos_group_roles_setup_page(){

    global $errors;

    $response = badgeos_setup_group_management_roles_form_submission();

    $setuproles = isset($_POST['setuproles']);

    $student_role = $setuproles && isset($_POST['student-role']) ? wp_unslash($_POST['student-role']) : get_option( 'badgeos_group_management_student_role' );
    $teacher_role = $setuproles && isset($_POST['teacher-role']) ? wp_unslash($_POST['teacher-role']) : get_option( 'badgeos_group_management_teacher_role' );

    $groups = true;
    if( check_group_management_roles_mapping() == false){
        $groups = false;
    }

    ?>

    <div class="wrap">
    <h2 id="setup-user-role">
        <?php echo _x('Group Management Role Setup', 'user'); ?>
    </h2>

    <?php if (isset($errors) && $errors->get_error_messages() && is_wp_error($errors)) : ?>
        <div class="error">
            <ul>
                <?php
                foreach ($errors->get_error_messages() as $err)
                    echo "<li>$err</li>\n";
                ?>
            </ul>
        </div>
    <?php endif;

    if (!empty($response)) {
        echo '<div id="message" class="updated"><p>Group management roles updated successfully.</p></div>';
    } ?>
    <div id="ajax-response"></div>
    <form action="" method="post" name="setup_user_roles" id="setup_user_roles" class="validate" novalidate="novalidate">
        <input name="action" type="hidden" value="badgeos_group_management_setup_roles"/>
        <p>After Student and Teacher accounts are created, roles can no longer be modified.</p>
        <?php wp_nonce_field('group-roles', '_wpnonce_group-roles'); ?>
        <table class="form-table">
            <tr class="user-role-wrap">
                <th><label for="role"><?php _e('Students') ?></label></th>
                <td>
                    <select name="student-role" id="student-role" <?php echo ($groups) ? 'disabled = disabled':''; ?> >
                        <option value="">-- Select a role --</option>
                        <?php
                        // print the full list of roles with the primary one selected.
                        wp_dropdown_roles($student_role);
                        ?>
                    </select>
                    <p class="description indicator-hint" style="position: absolute;"><?php _e('Typically the Subscriber role, Students are members of schools earning achievements.'); ?></p>
                </td>
            </tr>
        </table>
        <br><br>
        <table class="form-table">
            <tr class="user-role-wrap">
                <th><label for="role"><?php _e('Teachers') ?></label></th>
                <td>
                    <select name="teacher-role" id="teacher-role" <?php  echo ($groups) ? 'disabled = disabled':''; ?> >
                        <option value=""> -- Select a role --</option>
                        <?php
                        // print the full list of roles with the primary one selected.
                        wp_dropdown_roles($teacher_role);
                        ?>
                    </select>
                    <p class="description indicator-hint" style="position: absolute;"><?php _e('Typically the Author role, Teachers can review Submissions from their Students, and issue or revoke achievements.'); ?></p>
                </td>
            </tr>
        </table>
        <?php if(!$groups){ ?>
        <?php submit_button( __( 'Save Changes'), 'primary', 'setuproles', true, array( 'id' => 'setuproles' ) ); ?>
        <?php } ?>
    </form>
    <?php
}