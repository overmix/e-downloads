<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('output_msg')) {
	function output_msg($type = null) {
		$CI =& get_instance();
		
		if ($CI->messages->sum($type) > 0) {
		$messages = $CI->messages->get($type);
		// display all messages of the type
		if (is_array($messages)) {
			$output = '';
			foreach ($messages as $type => $msgs) {
				if (count($msgs) > 0) {
					$output .= '<div class="box">';
					$output .= '<p class="msg ' . $type . '">';
					foreach ($msgs as $message) {
						$output .= $message;
					}
					$output .= '</p>';
					$output .= '</div>';
				}
			}
		}
		return $output;
		}
	} 
}