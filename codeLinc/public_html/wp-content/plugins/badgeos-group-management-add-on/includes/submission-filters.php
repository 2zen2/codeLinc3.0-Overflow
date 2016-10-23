<?php
/**
 * School filter to submission list filters
 *
 * @since  1.0.0
 *
 * @param  string $output HTML Markup.
 * @param  array $atts    Shortcode Attributes.
 * @return string         HTML Markup.
 */
function badgeos_school_submission_filters( $output, $atts ) {

	if ( 'false' !== $atts['show_filter'] && badgeos_user_can_manage_submissions() && current_user_can('administrator')) {

        $args = array(
            'role'         => 'school_admin',
            'fields'       => array("ID","display_name")
        );

        $schools = get_users($args);

		$selected_id = isset( $atts['school_id'] ) ? absint( $atts['school_id'] ) : 0;

		if ( count($schools) > 0 ) {
			$output .= '<div class="badgeos-feedback-filter badgeos-feedback-bp-schools">';
				$output .= '<label for="school_id">' . __( 'School:', 'badgeos-schools' ) . '</label>';
				$output .= ' <select name="school_id" id="school_id">';
					$output .= '<option value="0">' . __( 'All', 'badgeos-schools' ) . '</option>';
				foreach( $schools as $school ) {
					$output .= '<option value="' . absint( $school->ID ) . '" ' . selected( $selected_id, $school->ID, false ) . '>' . esc_attr( $school->display_name ) . '</option>';
				}
				$output .= '</select>';
			$output .= '</div>';
		}
	}

	return $output;
}
add_filter( 'badgeos_render_feedback_filters', 'badgeos_school_submission_filters', 10, 2 );

/**
 * Limit feedback query to specific School members.
 *
 * @since  1.0.0
 *
 * @param  array $args Feedback args.
 * @return array       Feedback args.
 */
function badgeos_bp_filter_feedback_args_school( $args ) {

	if ( ! empty( $_REQUEST['school_id'] ) ) {
        $args['meta_query'][] = array(
            'key'   => "school_id",
            'value' => $_REQUEST['school_id']
        );
    }

	return $args;
}
add_filter( 'badgeos_get_feedback_args', 'badgeos_bp_filter_feedback_args_school' );

/**
 * Register group_id filter selector for submission list shortcode.
 *
 * @since  1.4.0
 *
 * @param  array $atts     Available attributes.
 * @param  array $defaults Default attributes.
 * @param  array $passed   Passed attributes.
 * @return array           Available attributes.
 */
function badgeos_bp_submissions_atts_school( $atts, $defaults, $passed ) {
	$atts['school_id'] = isset( $passed['school_id'] ) ? absint( $passed['school_id'] ) : 0;
	$atts['filters']['school_id'] = '.badgeos-feedback-bp-schools select';
	return $atts;
}
add_filter( 'shortcode_atts_badgeos_submissions', 'badgeos_bp_submissions_atts_school', 10, 3 );


