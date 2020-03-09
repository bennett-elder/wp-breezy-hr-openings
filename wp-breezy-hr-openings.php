<?php
/**
 * @package WP_Breezy_HR_Openings
 * @version 0.0.2
 */
/*
Plugin Name: WP Breezy HR Openings
Version: 0.0.2
*/
// Add Shortcode
function brzyhropenings_shortcode( $atts ) {

	// Attributes - set defaults if no value passed when shortcode used
	$atts = shortcode_atts(
		array(
            'user' => 'jobs',
            'secondstocache' => '60',
            'format' => 'table',
            'linktarget' => '_blank',
		),
		$atts
	);

    $user = $atts['user'];
    $cache_slug = 'brzyhropenings_' . $user;
    $seconds_to_cache = $atts['secondstocache'];
    $url = 'https://' . $user . '.breezy.hr/json';
    $outputFormat = $atts['format'];
    $linkTarget = $atts['linktarget'];

	$output = '';

    $openings = get_transient($cache_slug);

    if (false === $openings) {
        $response = file_get_contents($url);
        $openings = json_decode($response);
        // echo 'I had to hit the web api';
        set_transient($cache_slug, $openings, $seconds_to_cache);
    }

	
    $output .= '<div class="positions-container">';

    if ('table' === $outputFormat) {
	
        $output .= '<table class="positions"><tbody>';
        
        foreach ($openings as $record) {
            $output .= '<tr class="position"><td class="name">'
            . '<h2><a href="' . $record->url . '" target="' . $linkTarget . '">' . $record->name . '</a></h2>'
            . '<div class="mobile"><div class="type">'
            . '<span class="label ' . $record->type->id . '"> ' . $record->type->name . ' in </span>'
            . '<span class="location"><i class="fa fa-map-marker"></i> ' . $record->location->name . '</span>';

            if (isset($record->department) && strlen($record->department) > 0) {
                $output .= ' <span class="department"><i class="fa fa-building"></i> ' . $record->department . '</span>';
            }

            if (isset($record->location->is_remote) && $record->location->is_remote == true) {
                $output .= ' <span class="remoteok"><i class="fa fa-wifi"></i> Remote OK</span>';
            }

            $output .= '</div></div>';
            $output .= '</td></tr>';
        }
        
        $output .= '</tbody></table>'; // <table class="positions"><tbody>
    }
    elseif ('div' === $outputFormat) {
	
        $output .= '<div class="positions">';
        
        foreach ($openings as $record) {
            $output .= '<div class="position">';
            $output .= '<div class="name">'
            . '<span class="title"><a href="' . $record->url . '" target="' . $linkTarget . '">' . $record->name . '</a></span>'
            . '<div class="mobile"><div class="type">'
            . '<span class="label ' . $record->type->id . '"> ' . $record->type->name . ' in </span>'
            . '<span class="location"><i class="fa fa-map-marker"></i> ' . $record->location->name . '</span>';

            if (isset($record->department) && strlen($record->department) > 0) {
                $output .= ' <span class="department"><i class="fa fa-building"></i> ' . $record->department . '</span>';
            }

            if (isset($record->location->is_remote) && $record->location->is_remote == true) {
                $output .= ' <span class="remoteok"><i class="fa fa-wifi"></i> Remote OK</span>';
            }

            $output .= '</div></div>';
            $output .= '</div></div>';
        }
        
        $output .= '</div>'; // class="positions"
    }
    $output .= '</div>'; // class="positions-container"

    return $output;
}
add_shortcode( 'brzyhropenings', 'brzyhropenings_shortcode' );