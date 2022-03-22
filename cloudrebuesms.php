<?php
/**
* Plugin Name: Cloud Rebue WPSMS
* Plugin URI:  https://wordpress.org/plugins/cloud-rebue-wpsms
* Description: send SMS WooCommerce order Notifications in wordpress using Cloud Rebue API, Add SMS authentication.
* Version:     1.0.7
* Author:      Cloud Rebue
* Author URI:  http://edwardmuss.tech/
* Developer: Cloud Rebue
* Developer URI: http://cloudrebue.co.ke/
* Text Domain: woocommerce-extension
* WC requires at least: 4.6
* WC tested up to: 6.3.1
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: crsms
 */
if (!defined('ABSPATH')) die('Cannot be accessed directly!');
const CLOUDREBUE_VERSION = '1.0.7';
$tblname = 'crsms_logs';
global $sms_log_table, $wpdb;
$sms_log_table = $wpdb->prefix . "$tblname";

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // echo 'WooCommerce is active.';
} else {
    echo admin_notice__success("Cloud Rebue Plugin Recommends Woocomerce plugin to be installed and active");
}


create_plugin_database_table();

function getsms_logs(){
	global $sms_log_table, $wpdb;
	$sql = "SELECT * FROM $sms_log_table ORDER BY created_at DESC LIMIT 1000";
	$results = $wpdb->get_results($sql);
	$db_results = json_encode($results);
	$table= '';
    foreach( $results as $result ) {}
	
	return ($results);
}


function crsms_dir(){ return __DIR__; }

function crsms_url(){
    static $dir;
    if ($dir) return $dir;
    $dir = plugin_dir_url(__FILE__);
    return $dir;
}

/*
	sms sending interface; available for use everywhere.
*/
function crsms_send_sms($message, $recipients, $senderid='',$send_at=0, $flash=0, $unicode=null){
	if(is_array($recipients)){
		$contacts=array();
		$first=current($recipients);
		if(is_array($first)){
			$contacts=$recipients;
			$recipients=$first['phone'];
		}
		else $recipients=implode(',',$recipients);
	}
	
	$default_unicode=get_option('crsms_default_unicode',0);
    if($unicode===null)$unicode=$default_unicode;
    
	$token=get_option('crsms_token');
	$senderid=$senderid?:get_option('crsms_default_sender');
	$endpoint = 'https://bulk.cloudrebue.co.ke/api/v1/send-sms';

	$post_data=array(
	'action'=>'send_sms',
	'sender'=>$senderid,
	'phone'=>$recipients,
	'correlator'=>'wp-sms' . $senderid,
	'link_id'=>null,
	'message'=>$message
	);
	if(!empty($contacts))$post_data['contacts']=$contacts;	
	$data_string = json_encode($post_data);

	$request = wp_remote_post(
	$endpoint,
		array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			),
			'body'    => $data_string,
		)
	);

	if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		return false;
	}

	$response = wp_remote_retrieve_body( $request );
	// var_dump($response);

	$results = json_decode($response,true);

	foreach($results as $result){
		global $sms_log_table, $wpdb;
		$wpdb->insert($sms_log_table, array(
		"sender" => $result['data']['short_code'],
		"phone" => $result['data']['phone'],
		"message" => $result['data']['message'],
		"status" => $result['message']
	));
	}

	
}

function create_plugin_database_table() {
	global $sms_log_table, $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS $sms_log_table ( ";
	$sql .= "  `id`  int(11)   NOT NULL auto_increment PRIMARY KEY, ";
	$sql .= "  `sender`  VARCHAR(255)   NOT NULL, ";
	$sql .= "  `phone`  VARCHAR(255)   NOT NULL, ";
	$sql .= "  `message`  VARCHAR(1007)   NOT NULL, ";
	$sql .= "  `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP, ";
	$sql .= "  `status`  VARCHAR(1007)   NOT NULL";
	$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
	require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	dbDelta($sql);  
  }

function admin_notice__success($message) 
{
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( $message, 'cloudrebue' ); ?></p>
    </div>
    <?php
}

add_action('init', function () {
    $crsms_plugin_dir = crsms_dir();
	
    if (get_option('crsms_enable_ui')) {
        include "$crsms_plugin_dir/inc/admin_widget_menu.php";
		add_action( 'admin_menu', 'cgsms_widget_menu' );
    }
	
	if (!is_admin()) {
		include "$crsms_plugin_dir/inc/shortcode.php";
	}

	if (get_option('crsms_security_enable')) {
        include "$crsms_plugin_dir/inc/security_two_factor.php";
    }
	
    if (!current_user_can('edit_others_posts')) return;

	// register admin menu pages
	add_action('admin_menu', 'add_admin_pages');

	add_action('admin_init', function () {
		register_setting('cloudrebuesms', 'crsms_sub_account');
		register_setting('cloudrebuesms', 'crsms_token');
		register_setting('cloudrebuesms', 'crsms_default_sender');
		register_setting('cloudrebuesms', 'crsms_default_unicode');
		register_setting('cloudrebuesms', 'crsms_enable_ui');
		
		register_setting('cloudrebuesms', 'crsms_security_enable');
		register_setting('cloudrebuesms', 'crsms_security_required_roles');
		register_setting('cloudrebuesms', 'crsms_security_cookie_lifetime');
		register_setting('cloudrebuesms', 'crsms_security_bypass_code');
        
		register_setting('cloudrebuesms', 'crsms_notif_wc-new');
		register_setting('cloudrebuesms', 'crsms_notif_wc-payment');
        
        if(function_exists('wc_get_order_statuses')){
            $woo_statuses=wc_get_order_statuses();
            foreach($woo_statuses as $woo_status=>$woo_status_descr)
                register_setting('cloudrebuesms', "crsms_notif_$woo_status");            
        }
	});
	
}, 9);

// add admin menu and page
function add_admin_pages(){
	//page title, menu title, permision, plugin slug, template function callback/views, icon, position
	add_menu_page( 'Cloud Rebue Settings', 'Cloud Rebue', 'manage_options', 'cloudrebue', 'sms_settings_page', 'dashicons-email-alt', 110);

	// add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
	add_submenu_page('options-general.php','Cloud Rebue','Cloud Rebue','manage_options','cloudrebue','sms_settings_page');
	add_submenu_page('cloudrebue','Log History','Log History','manage_options','sms_notifications_log_history', 'sms_logs_page');
}
//Page Templeates
function sms_settings_page(){
	//Put HTML templates here
	require_once crsms_dir() . "/tpl/settings_page.php";
}

//Page Templeates
function sms_logs_page(){
	//Put HTML templates here
	require_once crsms_dir() . "/tpl/log_page.php";
}

/*
add_action('plugins_loaded', function() {
    // load translations
    //load_plugin_textdomain('cloudrebuesms', false, 'cloudrebuesms/languages/');
});
*/

function _cgsms_replace_placeholders($template,$order,array $more_values=array()){
    $values=array();
    $values['billing_first_name']=$order->get_billing_first_name();
    $values['billing_last_name']=$order->get_billing_last_name();
    $values['billing_company']=$order->get_billing_company();
    $values['billing_address']=$order->get_billing_address_1();
    $values['order_id']=$order->get_id(); //$order->order_id;
    $values['order_number']=$order->get_order_number();
    $values['order_total']=$order->get_formatted_order_total();
    $values['status']=$order->get_status();
    if(is_array($more_values)&&!empty($more_values))$values=array_merge($values,$more_values);
    
    $find=array(); $replace=array();
    foreach($values as $rk=>$rv){
        $find[]="%$rk%"; $replace[]=$rv;
    }
    return str_ireplace($find,$replace,$template);
}


function cgsms_woo_order_status_changed($order_id,$old_status,$new_status) {
    $order = wc_get_order($order_id);
    $recipient=$order->get_billing_phone('view');
    if(empty($recipient))return;    
    $message_template=trim(get_option("crsms_notif_wc-$new_status"));
    if(empty($message_template))return;
    
    $message=_cgsms_replace_placeholders($message_template,$order);
    crsms_send_sms($message,$recipient);
}
add_action('woocommerce_order_status_changed', 'cgsms_woo_order_status_changed', 10, 3);

add_action('woocommerce_new_order',function($order_id){
    $order = wc_get_order($order_id);
    $recipient=$order->get_billing_phone('view');
    if(empty($recipient))return;
    $message_template=trim(get_option("crsms_notif_wc-new"));
    if(empty($message_template))return;
    $message=_cgsms_replace_placeholders($message_template,$order);
    crsms_send_sms($message,$recipient);
});

add_action('woocommerce_payment_complete',function($order_id){
    $order = wc_get_order($order_id );
    $recipient=$order->get_billing_phone('view'); //or 'edit'
    //$recipient=$order->billing_phone;
    if(empty($recipient))return;    
    $message_template=trim(get_option("crsms_notif_wc-payment"));
    if(empty($message_template))return;
    /*
    $user = $order->get_user();
    if($user ){} // do something with the user
    */
    $message=_cgsms_replace_placeholders($message_template,$order);
    crsms_send_sms($message,$recipient);
});

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'cloudrebuesms_add_plugin_page_settings_link');
function cloudrebuesms_add_plugin_page_settings_link($links){
	$links[]='<a href="'.admin_url('options-general.php?page=cloudrebuesms').'">'.__('Settings').'</a>';
	return $links;
}