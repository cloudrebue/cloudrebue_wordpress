<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<?php
	function cloudrebuesms_widget_page(){
		$crsms_sub_account=get_option('crsms_sub_account');
		$crsms_token=get_option('crsms_token');
		
		$temp_token='';
		if(!empty($crsms_sub_account)&&!empty($crsms_token))$temp_token="&token=".base64_encode($crsms_sub_account.':'.$crsms_token);
		
		$temp_page=empty($_GET['page'])?'':ltrim(str_replace('cloudrebuesms-widget','',$_GET['page']),'-');
		if(!empty($temp_page))$temp_token.="&a=$temp_page";
		
		echo "<iframe  src='//cloudrebuesms.com/widget?{$temp_token}' style='width:100%;min-height:650px;border:1px solid #bbb;border-radius:3px;'></iframe>";
	}
	
	function cgsms_widget_menu(){
	  $page_title = 'Cloud Rebue SMS Widget';
	  $menu_title = 'Cloud Rebue SMS';
	  $capability = 'manage_options';
	  $menu_slug  = 'cloudrebuesms-widget';
	  $function   = 'cloudrebuesms_widget_page';
	  $icon_url   = 'dashicons-email';
	  $position   = 4;

	  add_menu_page( $page_title,
					 $menu_title, 
					 $capability, 
					 $menu_slug, 
					 $function, 
					 $icon_url, 
					 $position );
		



	   //add sub-menus
	   $sub_menus=array(
			'send_sms'=>'Send SMS',
			'sms_log'=>'SMS Delivery Reports',
			'sms_batches'=>'SMS Batches Overview',
			'contacts'=>'Manage SMS Contacts',
			'sub_transactions'=>'SMS Sub-transaction Log',
			'coverage_list'=>'SMS Coverage & Pricing',
			//'gateway_api'=>null
	   );
		
	 add_submenu_page($menu_slug, 'SMS Balance & Info', 'SMS Balance & Info',$capability,$menu_slug,$function);
	   foreach($sub_menus as $sub_menu=>$sub_menu_title){
		   $sub_menu_slug="$menu_slug-$sub_menu";
		   $sub_menu_title=ucwords(str_replace('_',' ',$sub_menu));
		   $sub_page_title="$sub_menu_title - Cloud Rebue SMS";
		   
			add_submenu_page(
				$menu_slug,
				$sub_page_title,
				$sub_menu_title,
				$capability,
				$sub_menu_slug,
				'cloudrebuesms_widget_page'
			);
	   }
	}
