<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<?php
	function cloudrebuesms_widget_page(){

		echo "<iframe  src='//bulk.cloudrebue.co.ke' style='width:100%;min-height:650px;border:1px solid #bbb;border-radius:3px;'></iframe>";
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
			''=>'',
	   );
		
	 add_submenu_page($menu_slug, 'SMS Portal', 'SMS Portal',$capability,$menu_slug,$function);
	   foreach($sub_menus as $sub_menu=>$sub_menu_title){
		   $sub_menu_slug="$menu_slug-$sub_menu";
		   $sub_menu_title=ucwords(str_replace('_',' ',$sub_menu));
		   $sub_page_title="$sub_menu_title - CloudRebueSMS";
		   
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
