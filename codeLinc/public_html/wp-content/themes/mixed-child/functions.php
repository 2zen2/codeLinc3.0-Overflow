<?php
//Gets list of actions for each roll
add_action('wp', function(){ echo '<pre>';print_r($GLOBALS['wp_filter']); echo '</pre>';exit; } );


//add custom user role for teachers
/*$result = add_role('teacher', _(
    'Teacher'),
array(
    'read' => true, //allows reading
    'edit_posts' => true, //allows user to edit own post
    'edit_others_posts' => false,

)
);*/