<?php
/**
 * Insert new column as School Name
 *
 * @since 1.0.0
 *
 * @param $columns Pre-defined columns for user list
 * @return array Resulting column school name included
 */
function badgeos_add_school_name_column($columns) {

    // Include new column for administrator
    if(current_user_can('administrator')){
        return array_merge( $columns, array('school' => __('School Name','badgeos-group-management')) );
    }else{
        return $columns;
    }
}
add_filter('manage_users_columns' , 'badgeos_add_school_name_column');


/**
 * Get school name for the user and display in user list
 *
 * @since 1.0.0
 *
 * @param $value
 * @param string $column_name Column Name as School Name
 * @param integer $user_id User ID
 * @return string
 */
function badgeos_get_school_name_column_content($value, $column_name, $user_id) {

    //Check the school id in user meta
    if($user_id){
        $role = badgeos_get_user_role($user_id);
        $school_id = ($role == "school_admin") ? $user_id : get_user_meta( absint( $user_id ), 'school_id', true );
    }

    // print the school name
    switch ($column_name) {
        case 'school' :
            if(!empty($school_id)){
                //Get school name by school admin id
                $user = get_userdata( absint( $school_id ) );
                return $user->data->display_name;
            }else{
                return 'None';
            }
            break;
        default:
            return $value;
            break;
    }
}
add_action('manage_users_custom_column',  'badgeos_get_school_name_column_content', 10, 3);


/**
 * Filtering users based on user role in backend
 *
 * @since 1.0.0
 *
 * @param $query User Query
 * @return mixed Query included joins
 */
function badgeos_display_users_lists_by_usermeta($query){

    $users_list_page = (function_exists('get_current_screen'))? get_current_screen()->id :"";

    if($users_list_page=='users' || $_GET['page'] == 'bp-signups'){
        // Get user values
        $role = badgeos_get_user_role();      // User role for currently logged in user
        $school_id = absint( badgeos_get_school_id() ); // School ID of logged in user
        $user_id = absint( get_current_user_id() );     // Current User ID

        switch($role){

            case 'school_admin':
                //Get author and subscriber lists belongs to school admin
                if(!empty($school_id)){
                    $query->query_from .= " JOIN wp_usermeta M ON ( wp_users.ID = M.user_id )";
                    $query->query_where .= ' AND (( M.meta_key = "school_id" AND M.meta_value = '.$school_id.')) AND ( wp_users.user_status != 2 )';
                    $query->query_fields = 'DISTINCT '.$query->query_fields;
                }
                break;

            case get_option('badgeos_group_management_teacher_role'):
                //Get author and subscriber's lists belongs to author
                $query->query_from .= " JOIN wp_usermeta M ON ( wp_users.ID = M.user_id )";
                $query->query_where .= ' AND (( M.meta_key = "teacher_id" AND M.meta_value = '.$user_id.')) AND ( wp_users.user_status != 2 )';
                $query->query_fields = 'DISTINCT '.$query->query_fields;
                break;
            default:
                $query->query_where .= ' AND ( wp_users.user_status != 2 )';
                break;
        }
    }

    return $query;
}
add_action('pre_user_query', 'badgeos_display_users_lists_by_usermeta');


/**
 * Alter user counts based on logged in user role
 *
 * @since 1.0.0
 *
 * @param array $views pre-defined views with user counts
 * @return array
 */
function badgeos_modify_users_views_by_user_role( $views )
{

    global $wp_roles,$role;

    //Initial variable declaration
    $user_role = badgeos_get_user_role(); // User role for currently logged in user
    $school_id = absint( badgeos_get_school_id() ); // School ID of logged in user
    $user_id = absint( get_current_user_id() );     // Current User ID
    $teacher_role = get_option('badgeos_group_management_teacher_role');
    $student_role = get_option('badgeos_group_management_student_role');

    $key = null;
    $value = null;
    $total_users = 0;

    if($user_role == "school_admin"){
        $key = 'school_id';
        $value = $school_id;
    }elseif($user_role == $teacher_role){
        $key = 'teacher_id';
        $value = $user_id;
    }

   //if(!empty($key) && !empty($value) && !empty($user_role)){

        $args = array(
            'meta_query' => array(
                'relation' => 'OR',
                0 => array(
                    'key'     => $key,
                    'value'   => $value
                )
            )
        );
        $query = new WP_User_Query( $args );
        $users  = $query->get_results();
        $total_users  = $query->get_total();

        //Get specific role count - If login user role "school_admin"
        foreach($users as $user){
            if($user->roles[0] == $teacher_role){
                $author[] = $user->roles[0];
                $author_count = count($author);
            }else if($user->roles[0] == $student_role){
                $subscriber[] = $user->roles[0];
                $subscriber_count = count($subscriber);
            }
        }

        $url = 'users.php';
        $users_of_blog = count_users();
        $avail_roles =& $users_of_blog['avail_roles'];

        $class = empty($role) ? ' class="current"' : '';
        $role_links = array();
        $role_links['all'] = "<a href='$url'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_users, 'users' ), number_format_i18n( $total_users ) ) . '</a>';
        foreach ( $wp_roles->get_names() as $this_role => $name ) {
            if ( !isset($avail_roles[$this_role]) )
                continue;

            $class = '';
            if ( $this_role == $role ) {
                $class = ' class="current"';
            }
            $name = translate_user_role( $name );

            $avail_roles[$teacher_role] = $author_count;
            $avail_roles[$student_role] = $subscriber_count;

            /* translators: User role name with count */
            $name = sprintf( __('%1$s <span class="count">(%2$s)</span>'), $name, number_format_i18n( $avail_roles[$this_role] ) );
            $role_links[$this_role] = "<a href='" . esc_url( add_query_arg( 'role', $this_role, $url ) ) . "'$class>$name</a>";
        }

        $pending = null;
        if($views['registered']){
            $pending = $views['registered'];
        }
        unset($views);
        $views = $role_links;

        if($pending){
            $views['registered'] = $pending;
        }

   // }

    switch($user_role){
        case 'school_admin':
            //Get author and subscriber lists belongs to school admin, remain options are unset
            foreach ($views as $roleName => $link){
                if($roleName == 'all' || $teacher_role == $roleName || $student_role == $roleName){
                }else{
                   unset($views[$roleName]);
                }
            }

            break;

        case $teacher_role:
            //Get subscriber's lists belongs to author, remain options are unset
            foreach ($views as $roleName => $link){
                if ($roleName == 'all' || $student_role == $roleName){
                }else{
                    unset($views[$roleName]);
                }
            }

            break;
        default:
    }

    return $views;
}
add_filter( 'views_users', 'badgeos_modify_users_views_by_user_role',99);


