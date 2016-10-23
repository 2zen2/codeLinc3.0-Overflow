<?php

/**
 * Adding school id for user while register
 *
 * @since 1.0.0
 *
 * @param integer $user_id User ID
 */
function badgeos_schools_user_register( $user_id ) {

    if(is_user_logged_in() && (badgeos_get_user_role() =="school_admin" || badgeos_get_user_role() ==get_option('badgeos_group_management_teacher_role'))){

        // update user metadata for registered user
        if( !update_user_meta( absint( $user_id ), "school_id", absint( badgeos_get_school_id() ) ) ){
            add_user_meta( absint( $user_id ), "school_id", absint( badgeos_get_school_id() ) );
        }

        if(badgeos_get_user_role() ==get_option('badgeos_group_management_teacher_role')){
            if( !update_user_meta( absint( $user_id ), "teacher_id", absint( get_current_user_id() ) ) ){
                add_user_meta( absint( $user_id ), "teacher_id", absint( get_current_user_id() ) );
            }
        }
    }

}
add_action( 'user_register', 'badgeos_schools_user_register',10, 1 );

/*
 * Assign Group Management student role, Override default site role for new registered users
 * While activationg account
 *
 * @param integer $user User ID
 */
function badgeos_group_management_user_role_assign( $user ) {


    if ( empty( $user ) ) {
        return false;
    }

    if ( is_array( $user ) ) {
        $user_id = $user['user_id'];
    } else {
        $user_id = $user;
    }

    if ( empty( $user_id ) ) {
        return false;
    }

    //Assign Group Management student role
    $activated_user = new WP_User($user_id);
    $activated_user->set_role( get_option('badgeos_group_management_student_role') );

}
add_action( 'bp_core_activated_user', 'badgeos_group_management_user_role_assign');


/**
 * Adding school id for every posts
 *
 * @since 1.0.0
 *
 * @param integer $post_id Post ID
 */
function badgeos_add_schools_to_post($post_id){

    // Inserting school id as post meta data
    if(is_user_logged_in() && (badgeos_get_user_role() =="school_admin" || badgeos_get_user_role() ==get_option('badgeos_group_management_teacher_role'))) {
        if (!update_post_meta(absint($post_id), "school_id", absint(badgeos_get_school_id()))) {
            add_post_meta(absint($post_id), "school_id", absint(badgeos_get_school_id()));
        }
    }

    if(is_user_logged_in() && (badgeos_get_user_role() == get_option('badgeos_group_management_student_role'))){

        if (!update_post_meta(absint($post_id), "school_id", absint(badgeos_get_school_id()))) {
            add_post_meta(absint($post_id), "school_id", absint(badgeos_get_school_id()));
        }

        // Inserting teacher id as post meta data
        if( !update_post_meta( absint( $post_id ), "teacher_id", absint( badgeos_get_teacher_id() ) ) ){
            add_post_meta( absint( $post_id ), "teacher_id", absint( badgeos_get_teacher_id() ) );
        }
    }

    flush_rewrite_rules();
}
add_action( 'save_post', 'badgeos_add_schools_to_post' );


/**
 * Adding school id for every group creation
 *
 * @since 1.0.0
 *
 * @param integer $group_id Group ID
 * @param object $member Member Object
 * @param object $group Group Object
 */
function badgeos_add_schools_to_group($group_id, $member, $group ){

    // Inserting group meta data as school id
    if(is_user_logged_in() && (badgeos_get_user_role() =="school_admin" || badgeos_get_user_role() ==get_option('badgeos_group_management_teacher_role'))) {
        if (!groups_update_groupmeta(absint($group_id), 'school_id', absint(badgeos_get_school_id()))) {
            groups_add_groupmeta(absint($group_id), 'school_id', absint(badgeos_get_school_id()));
        }
    }
}
add_action( 'groups_create_group','badgeos_add_schools_to_group');


/**
 * Add filter for submissions listing using School ID
 *
 * @since 1.0.0
 *
 * @param array $args Arguments for filtering submissions
 * @return mixed
 */
function badgeos_bp_school_filter_feedback_args($args){

    // Getting user role for current logged in user
    $role = badgeos_get_user_role(get_current_user_id());

    // adding meta query for filtering submissions
    if($role=="school_admin"){
        $args['meta_query'][] = array( 'key' => "school_id", 'value' => absint( get_current_user_id() ) );

    }elseif($role==get_option('badgeos_group_management_teacher_role')){

        $student_ids = badgeos_get_students( absint( get_current_user_id() ) );

        if(isset($_GET['group_id'])) {
            //Filter Group Member Id
            $group_member = array();
            $group_member_ids = badgeos_bp_get_group_member_ids_from_group($_GET['group_id']);

            foreach($student_ids as $id){
                if(in_array($id,$group_member_ids)){
                    //Filter Group Member Id
                    $group_member[] = $id;
                }
            }
            if($group_member){
                unset($student_ids);
                $student_ids = $group_member;
            }
        }
        if ( ! empty( $student_ids ) ) {
            $args['author__in'] = $student_ids;
            $args['author'] = absint( get_current_user_id() );
        }else{
            $args['author'] = absint( get_current_user_id() );
        }
    }
    return $args;
}
add_filter( 'badgeos_get_feedback_args', 'badgeos_bp_school_filter_feedback_args' );


/**
 * Filter achievements based on schools in administrator backend
 *
 * @since 1.0.0
 *
 * @param object $query Query Object
 * @return mixed Resulting submissions and achievements
 */
function badgeos_schools_pre_get_posts($query){

    global $pagenow;


    $role = badgeos_get_user_role( absint( get_current_user_id() ) );

    if ( ($role == 'school_admin') && ( 'edit.php' == $pagenow ) ) {

        if(isset($_REQUEST["post_type"])){

            $post_type = $_REQUEST["post_type"];

            if($post_type == "submission" || $post_type == "achievement-type"){

                if($role=="school_admin"){

                    //Set query for display all achievements and submissions lists of school admin groups
                    if($post_type == "achievement-type"){
                        $query->set('author', absint( get_current_user_id() ));
                    }else{
                        $query->set('author','');
                        $query->set('meta_key', 'school_id');
                        $query->set('meta_value', absint( get_current_user_id() ) );
                    }
                }

            }else if ($post_type == "bp-invite-codes") {

                //Invite code filter based on schools in administrator backend
                if ($role == "school_admin") {
                    //Set query for display all achivements and submissions lists of school admin group
                    $query->set('author', '');
                    $query->set('meta_key', 'school_id');
                    $query->set('meta_value', absint( get_current_user_id() ) );

                    //If post of group created is available after deleted a group, remove posts based on deleted group ids
                    if($deleted_groups = get_deleted_groups_post_ids(absint( get_current_user_id()))){
                        $query->set('post__not_in', $deleted_groups);
                    }
                }
            }
        }

    }
    return $query;
}
add_filter('pre_get_posts', 'badgeos_schools_pre_get_posts');


/**
 * Filter for buddypress invite code based on schools in administrator backend
 *
 * @since 1.0.0
 *
 * @param object $user_id Int
 * @return mixed array post ids
 */
function get_deleted_groups_post_ids($user_id){

    global $wpdb;

    //Get bp-invite-codes post type posts.
    $results = $wpdb->get_results( $wpdb->prepare(
                                "SELECT * FROM {$wpdb->posts} AS p
                                 JOIN {$wpdb->postmeta} AS pm
                                 ON p.ID = pm.post_id
                                 WHERE p.post_type = 'bp-invite-codes'
                                 AND pm.meta_key = 'school_id'
                                 AND pm.meta_value = %d
                                 ",
                                $user_id));

    $deleted_group_id_post = array();
    foreach($results as $post){
       $group_ids = get_post_meta($post->ID,'_bp_invite_codes_group_id',true);
        foreach($group_ids as $group_id){
            //Check deleted groups from table
            $group = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bp_groups
                                         WHERE id = %d", $group_id));

            if(empty($group)){
                //Build deleted groups post id
                array_push($deleted_group_id_post , $post->ID);
            }
        }
    }

    return $deleted_group_id_post;
}

/**
 * Filter posts count based on schools in administrator backend
 *
 * @since 1.0.0
 *
 * @param object $wp_query Query Object
 * @return mixed Resulting submissions , achievements and invite codes
 */
function badgeos_school_edit_post_counts($views){

    if (!function_exists('get_current_screen')) {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
    }

    //Get current screen details
    $screen = get_current_screen();

    //Only allow following screen ids
    if($screen->id == 'edit-achievement-type' || $screen->id == 'edit-submission' || $screen->id == 'edit-bp-invite-codes'){

    if(isset($_REQUEST["post_type"])){

        $post_type = $_REQUEST["post_type"];

        //Get user role
        $role = badgeos_get_user_role( absint( get_current_user_id() ) );

        if($role=="school_admin"){

            $achievements = array();
            $trash = array();

            $achievement_args = array(
                'post_type' => $_REQUEST["post_type"],
                'author' => absint(get_current_user_id()),
                'posts_per_page'=> -1,
                'meta_key'=>'school_id',
                'meta_value'=>get_current_user_id()
            );

            $achievements = get_posts($achievement_args);


            $get_draft_args = array(
                'post_type' => $_REQUEST["post_type"],
                'post_status' => 'draft',
                'posts_per_page'=> -1,
                'author' => get_current_user_id(),
                'meta_key'=>'school_id',
                'meta_value'=>get_current_user_id()
            );

            $draft = get_posts($get_draft_args);


            $get_trash_args = array(
                'post_type' => $_REQUEST["post_type"],
                'post_status' => 'trash',
                'author' => get_current_user_id(),
                'posts_per_page'=> -1,
                'meta_key'=>'school_id',
                'meta_value'=>get_current_user_id()
            );

            $trash = get_posts($get_trash_args);


            //Get all total count from filter query
            $total_count = count($achievements);
            $draft_count = count($draft);
            $total_count = (absint($draft_count))?$total_count + $draft_count:$total_count;
            $trash_count = count($trash);

            //Get all published posts count from filter query
            $publish = array();
            $publish_count = 0;
            if($achievements){
                foreach($achievements as $post_data){
                    if($post_data->post_status == 'publish'){
                        $publish[] = 1;
                    }
                }
            }
            $publish_count = count($publish);
        }

        if($post_type == "submission" || $post_type == "achievement-type" || $post_type == "bp-invite-codes"){

            if($role=="school_admin"){

                //get edit url on dashboard
                $edit_url = admin_url( 'edit.php' );

                $all_posts = null;
                if($post_type == "submission" || $post_type == "achievement-type"){
                    $all_posts = '&all_posts=1';
                }
                /* translators: Post status name with count */
                $all = sprintf( __('%1$s <span class="count">(%2$s)</span>'), 'All', number_format_i18n( $total_count ) );
                $published = sprintf( __('%1$s <span class="count">(%2$s)</span>'), 'Published', number_format_i18n( $publish_count ) );
                $trashed = sprintf( __('%1$s <span class="count">(%2$s)</span>'), 'Trash', number_format_i18n( $trash_count ) );
                $drafted = sprintf( __('%1$s <span class="count">(%2$s)</span>'), 'Draft', number_format_i18n( $draft_count ) );

                $class = null;
                if(isset($_REQUEST["all_posts"])){
                    $class = ' class="current"';
                }

                $class1 = null;
                if(isset($_REQUEST["post_status"]) && $_REQUEST["post_status"]=='publish'){
                    $class1 = ' class="current"';
                }

                $class2 = null;
                if(isset($_REQUEST["post_status"]) && $_REQUEST["post_status"]=='trash'){
                    $class2 = ' class="current"';
                }

                $class3 = null;
                if(isset($_REQUEST["post_status"]) && $_REQUEST["post_status"]=='draft'){
                    $class3 = ' class="current"';
                }

                //Generate posts lists link
                $views['all'] = '<a href="'.$edit_url.'?post_type='.$post_type.''.$all_posts.'"'.$class.'>'.$all.'</a>';
                $views['publish'] = '<a href="'.$edit_url.'?post_status=publish&post_type='.$post_type.'"'.$class1.'>'.$published.'</a>';
                $views['draft'] = '<a href="'.$edit_url.'?post_status=draft&post_type='.$post_type.'"'.$class3.'>'.$drafted.'</a>';
                $views['trash'] = '<a href="'.$edit_url.'?post_status=trash&post_type='.$post_type.'"'.$class2.'>'.$trashed.'</a>';

                switch($post_type){
                    case "achievement-type":
                        unset($views['mine']);
                        break;
                    case "submission":
                        unset($views['mine']);
                        break;
                }
            }
        }
      }
    }
    return $views;
}

/**
 * Filter posts type with views count based on schools in administrator backend
 *
 * @since 1.0.0
 *
 * @param array $_REQUEST["post_type"]
 * @return related post type views with count (post_type : submissions , achievements and invite codes)
 */
if(isset($_REQUEST["post_type"])){
    $post_type = $_REQUEST["post_type"];
    add_filter('views_edit-'.$post_type.'', 'badgeos_school_edit_post_counts');
}

/**
 * Fetching administrator id of the group
 *
 * @since  1.0.0
 */
function badgeos_get_group_admin_form_group_id($group_id){
      global $wpdb;
      $group_admin = '';
      $results = $wpdb->get_results( $wpdb->prepare("
                        SELECT user_id FROM ".$wpdb->prefix."bp_groups_members
                        WHERE group_id = %d AND is_admin = 1 AND is_banned = 0",
                        absint( $group_id )
                    )
                 );
      // Selecting author role from group admins
      foreach($results as $result){
            if(badgeos_get_user_role($result->user_id)== get_option('badgeos_group_management_teacher_role')){
                $group_admin = $result->user_id;
                break;
            }else{
                $group_admin = $result->user_id;
            }
      }

      return $group_admin;
}


/**
 * Fetching Group members from group ID
 *
 * @since 1.0.0
 *
 * @param $group_id
 * @return array
 */
function badgeos_get_group_members_form_group_id($group_id){
    global $wpdb;

    $group_members = array();
    $results = $wpdb->get_results( $wpdb->prepare("
                        SELECT user_id FROM ".$wpdb->prefix."bp_groups_members
                        WHERE group_id = %d AND is_admin = 0 AND is_mod = 0 AND is_banned = 0",
            absint( $group_id )
        )
    );
    // Selecting author role from group admins
    foreach($results as $result){
        if(badgeos_get_user_role($result->user_id)== get_option('badgeos_group_management_student_role')){
            $group_members[] = $result->user_id;
        }
    }

    return $group_members;
}


/**
 * Get registered roles
 *
 * @since 1.0.0
 *
 * @return array
 */
function badgeos_get_role_names() {

    global $wp_roles;

    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    return $wp_roles->get_names();
}

/**
 * Remove author and subscriber mapping before delete a group
 *
 * @since 1.0.0
 *
 * @param int $group_id
 * @return null
 */
function badgeos_school_before_delete_group($group_id){

    //Get group author id
    $group_author_id = badgeos_get_group_admin_form_group_id($group_id);

    //Get members based on a group
    $members = badgeos_get_group_members_form_group_id($group_id);

    foreach($members as $member){

        if($group_author_id == get_user_meta($member,'teacher_id',true)){
            //Remove mapping between author and subscriber
            update_user_meta($member, 'teacher_id', '');
        }
    }
}

/**
 * Hook for remove author and subscriber mapping before delete a group
 */
add_action( 'groups_before_delete_group', 'badgeos_school_before_delete_group',10,2);


/**
 * Map Students with School and Teachers by using invite codes
 *
 * @since 1.0.0
 *
 * @param $group_id
 * @param $user_id
 * @return bool
 */
function badgeos_students_mapping($group_id,$user_id){

    // User is already a member, just return true
    if ( groups_is_user_member( $user_id, $group_id ) ){

        if(empty($group_id) || empty($user_id))
            return true;

        $roles = badgeos_get_role_names();
        $student_role = get_option('badgeos_group_management_student_role');
        unset($roles[$student_role]);

        $user_role = trim( badgeos_get_user_role( $user_id ));
        if (array_key_exists($user_role, $roles) && !empty($user_role))
            return true;

        // Fetching group admin information
            $admin_id = badgeos_get_group_admin_form_group_id( $group_id );

        // Adding user meta data
        if(!empty($admin_id) && (badgeos_get_user_role($admin_id)== get_option('badgeos_group_management_teacher_role'))){
            if( !update_user_meta($user_id, "teacher_id",$admin_id)){
                add_user_meta( $user_id, "teacher_id", $admin_id);
            }
            $school_id = get_user_meta($admin_id, 'school_id', true);

        }elseif(badgeos_get_user_role($admin_id)=='school_admin'){
            $school_id = $admin_id;
        }

        if(!empty($school_id)){
            if( !update_user_meta($user_id, "school_id",$school_id)){
                add_user_meta( $user_id, "school_id", $school_id);
            }
        }

        // Setting subscriber role to user
        if( badgeos_get_user_role($user_id) == "" ){
            $user_id_role = new WP_User($user_id);
            $user_id_role->set_role($student_role);
        }

    }
    return true;
}

/**
 * Hook for filter groups based on the user roles
 */
add_action('groups_join_group','badgeos_students_mapping',11,2);
add_action('groups_premote_member','badgeos_students_mapping',11,2);


/**
 * Modified group filter for Groups Admin screen
 * @return bool
 */
function bp_groups_filter_admin_screen(){

    global $bp_groups_list_table, $plugin_page, $groups_template;

    $current_user_role = badgeos_get_user_role(get_current_user_id());
    $roles = array('school_admin',get_option('badgeos_group_management_teacher_role'));

    if(!empty($current_user_role) && in_array($current_user_role , $roles)) {

        if (!function_exists('get_current_screen')) {
            require_once(ABSPATH . 'wp-admin/includes/screen.php');
        }

        $screen = get_current_screen();

        // Set current page
        $page = $bp_groups_list_table->get_pagenum();

        // Set per page from the screen options
        $per_page = $bp_groups_list_table->get_items_per_page(str_replace('-', '_', "{$screen->id}_per_page"));

        // Sort order.
        $order = 'DESC';
        if (!empty($_REQUEST['order'])) {
            $order = ('desc' == strtolower($_REQUEST['order'])) ? 'DESC' : 'ASC';
        }

        // Order by - default to newest
        $orderby = 'last_activity';
        if (!empty($_REQUEST['orderby'])) {
            switch ($_REQUEST['orderby']) {
                case 'name' :
                    $orderby = 'name';
                    break;
                case 'id' :
                    $orderby = 'date_created';
                    break;
                case 'members' :
                    $orderby = 'total_member_count';
                    break;
                case 'last_active' :
                    $orderby = 'last_activity';
                    break;
            }
        }

        // Set the current view
        if ( isset( $_GET['group_status'] ) && in_array( $_GET['group_status'], array( 'public', 'private', 'hidden' ) ) ) {
            $bp_groups_list_table->view = $_GET['group_status'];
        }

        // We'll use the ids of group types for the 'include' param
        $bp_groups_list_table->group_type_ids = BP_Groups_Group::get_group_type_ids();

        // Pass a dummy array if there are no groups of this type
        $include = false;
        if ( 'all' != $bp_groups_list_table->view && isset( $bp_groups_list_table->group_type_ids[ $bp_groups_list_table->view ] ) ) {
            $include = ! empty( $bp_groups_list_table->group_type_ids[ $bp_groups_list_table->view ] ) ? $bp_groups_list_table->group_type_ids[ $bp_groups_list_table->view ] : array( 0 );
        }

        // Get group type counts for display in the filter tabs
        $bp_groups_list_table->group_counts = array();
        foreach ( $bp_groups_list_table->group_type_ids as $group_type => $group_ids ) {
            $bp_groups_list_table->group_counts[ $group_type ] = count( $group_ids );
        }

        //Filter group lists by school admin and author
        if ($screen->parent_base == "bp-groups") {
            if (!empty($current_user_role) && $current_user_role == "school_admin") {
                $groups_meta_query = array(array('key' => 'school_id', 'value' => get_current_user_id()));
            } elseif (!empty($current_user_role) && $current_user_role == get_option('badgeos_group_management_teacher_role')) {
                $groups_user_id = get_current_user_id();
                $groups_meta_query = array(array('key' => 'school_id', 'value' => badgeos_get_school_id()));
            } else {
                $groups_user_id = bp_displayed_user_id();
                $groups_meta_query = false;
            }
        } else {
            $groups_user_id = bp_displayed_user_id();
            $groups_meta_query = false;
        }

        $groups_args = array(
            'include' => $include,
            'per_page' => $per_page,
            'page' => $page,
            'orderby' => $orderby,
            'order' => $order,
            'user_id' => $groups_user_id,
            'meta_query' => $groups_meta_query,
        );

        $groups = array();
        $all = array();

        if (bp_has_groups($groups_args)) {
            while (bp_groups()) {
                bp_the_group();

                if (isset($_GET['group_status']) && $_GET['group_status'] != $groups_template->group->status) {
                    continue;
                }

                array_push($all, $groups_template->group->id);
                $groups[] = (array)$groups_template->group;
            }
        }

        //Assign filter groups to display items of table
        $bp_groups_list_table->items = $groups;


        $groups_args = array(
            'user_id' => $groups_user_id,
            'meta_query' => $groups_meta_query,
        );

        $per_page_count = array();
        if (bp_has_groups($groups_args)) {
            while (bp_groups()) {
                bp_the_group();
                if (isset($_GET['group_status']) && $_GET['group_status'] != $groups_template->group->status) {
                    continue;
                }
                array_push($per_page_count, $groups_template->group->id);
            }
        }

        $total_items = count($per_page_count);

        $total_pages = ceil($total_items / $per_page);

        //Set Pagination args
        $bp_groups_list_table->set_pagination_args(array(
            'per_page' => $per_page,
            'total_items' => $total_items,
            'total_pages' => $total_pages
        ));
    }

    return true;
}
add_action("bp_groups_admin_index","bp_groups_filter_admin_screen",10,2);


/**
 * Fix css issues of admin menu section
 */
function admin_menu_css() {
    echo '<style>#adminmenuwrap {position: inherit;}#adminmenuwrap {position: fixed;}
          .toplevel_page_badgeos-group-management .form-table tr{position: relative;}' .
          '</style>';
}

/**
 *  CSS Design fix for Teacher and School Admin Dashboard
 */
add_action('admin_head', 'admin_menu_css');

/**
 * Allow users to skip achievements
 *
 * @since  1.0.0
 */
function bp_group_views(){
    global $wpdb;

    $ids = array();
    $where = '';
    // Current User ID
    $user_id = absint( get_current_user_id() );
    $user_role = badgeos_get_user_role($user_id); // User role for currently logged in user
    $school_id = absint( badgeos_get_school_id() ); // School ID of logged in user

    $roles = array('school_admin', get_option('badgeos_group_management_teacher_role'));

    if(in_array($user_role,$roles)){
        $join_query = "INNER JOIN ".$wpdb->prefix."bp_groups_groupmeta AS groupmeta ON  groups.id = groupmeta.group_id";
    }

    if($user_role== get_option('badgeos_group_management_teacher_role')){
        $where = " AND (members.user_id = $user_id) AND (groupmeta.meta_key = 'school_id' AND groupmeta.meta_value = $school_id )";
    }elseif($user_role=='school_admin'){
        $where = " AND ( groupmeta.meta_key = 'school_id' AND groupmeta.meta_value = $user_id )";
    }

    $groups_query = "SELECT DISTINCT(groups.id),groups.* FROM ".$wpdb->prefix."bp_groups AS groups
                                         INNER JOIN ".$wpdb->prefix."bp_groups_members AS members
                                         ON groups.id = members.group_id
                                         $join_query
                                         WHERE  members.is_banned = 0 $where ";

    $ids['all']     = $wpdb->get_col( $groups_query );
    $ids['public']     = $wpdb->get_col( $groups_query." AND groups.status = 'public'" );
    $ids['private']     = $wpdb->get_col( $groups_query." AND groups.status = 'private'" );
    $ids['hidden']     = $wpdb->get_col( $groups_query." AND groups.status = 'hidden'" );

    $response = array(
        "all" => count($ids['all']),
        "public" => count($ids['public']),
        "private" => count($ids['private']),
        "hidden" => count($ids['hidden'])
    );

    // Send back a successful response
    wp_send_json_success( $response );
}
add_action( 'wp_ajax_group_views', 'bp_group_views' );


