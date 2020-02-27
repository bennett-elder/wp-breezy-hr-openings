<?php
/**
 * @package WP_Breezy_HR_Openings
 * @version 0.0.1
 */
/*
Plugin Name: WP Breezy HR Openings
Version: 0.0.1
*/
// Add Shortcode
function brzyhropenings_shortcode( $atts ) {

	// Attributes - set defaults if no value passed when shortcode used
	$atts = shortcode_atts(
		array(
            'user' => 'jobs',
            'secondstocache' => '60',
		),
		$atts
	);

    $user = $atts['user'];
    $cache_slug = 'brzyhropenings_' . $user;
    $seconds_to_cache = $atts['secondstocache'];
    //echo $seconds_to_cache;
    //$tag = $atts['tag'];
    $url = 'https://' . $user . '.breezy.hr/json';

    $output = '';
    $output .= '<p>In theory I would query ' . $url . ' and display those results here.</p>';

    $openings = get_transient($cache_slug);

    if (false === $openings) {
        $response = file_get_contents($url);
        $openings = json_decode($response);
        echo 'I had to hit the web api';
        set_transient($cache_slug, $openings, $seconds_to_cache);
    }

    $output .= '<div class="positions-container">';
    $output .= '<table class="positions"><tbody>';
    
    foreach ($openings as $record) {
        //$output .= '<' . $tag . '>' . $record->name . '</' . $tag . '>';
        $output .= '<tr class="position">';
        $output .= '<td class="name"><h2>'
        . '<a href="' . $record->url . '">' . $record->name . '</a>'
        . '</h2>'
        . '<div class="mobile"><div class="type">'
        . '<span class="label ' . $record->type->id . '"> ' . $record->type->name . ' in </span>'
        . '<i class="fa fa-map-marker"></i>'
        . '<span> ' . $record->location->name . '</span>';

        if (isset($record->location->is_remote) && $record->location->is_remote == true) {
            $output .= ' <i class="fa fa-wifi"></i><span> Remote OK</span>';
        }

        $output .= '</div></div>' . '</td>' . '</tr>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode( 'brzyhropenings', 'brzyhropenings_shortcode' );