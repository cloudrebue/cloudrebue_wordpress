<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<?php
	/*
		Replaces all shortcodes with equivalent iframe.
		[crsms token="" no_tabs="" tabs_list="" a="" disable_login=""]
	*/
	function _cgsms_shortcode_iframe($atts){
	   $allowed_params=array('token','no_tabs','tabs_list','a','disable_login');
	   $url='//cloudrebuesms.com/widget';
	   $temp_params=array();
	   $arr = shortcode_atts(array('action'=>''), $atts ); 
	   
	   if(!empty($atts)){
		   foreach($arr as $ak=>$av){
			   $ak=strtolower($ak);
			   if(in_array($ak,$allowed_params))$temp_params[$ak]=$av;
		   }
	   }
	   
	   if(!empty($temp_params))$url="$url?".http_build_query($temp_params);
		return "<iframe src='$url' style='width:100%;min-height:560px;border:1px solid #bbb;border-radius:3px;clear:both;'></iframe>";
	}
	
	add_shortcode('crsms','_cgsms_shortcode_iframe' );
	/*
	$shortcode_fn = function($atts) {
		$atts = shortcode_atts(array('action'=>''), $atts ); 
		$action = isset($atts['action']) && $atts['action'] ? $atts['action'] : null;
		if($action=='')_cgsms_shortcode_iframe();		
	}
	
	add_shortcode('cloudrebuesms', $shortcode_fn);
	add_shortcode('crsms', $shortcode_fn);
	*/