<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

if(!function_exists('makeClass')){
	function makeClass($sortby, $field, $sorttype){
		if($sortby==$field) 
			return ' bs-sort-'.$sorttype;
		return '';
	}
}
if(!function_exists('makeTooltip')){
	function makeTooltip($tooltip, $pos='top'){
		global $core;
		$html = '&nbsp;<span data-bs-toggle="tooltip" title="'.$tooltip.'" data-html="'.$html.'">
			'.$core->makeIcon('info-circle').'
		</span>';
		return $html;
	}
}