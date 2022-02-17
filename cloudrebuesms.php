<?php
/*
Plugin Name: Cloud Rebue WPSMS
Plugin URI:  https://wordpress.org/plugins/cloud-rebue-wpsms
Description: send SMS WooCommerce Order Notifications in wordpress using Cloud Rebue API.
Version:     1.0.4
Author:      Cloud Rebue
Author URI:  http://cloudrebue.co.ke/
Developer: Cloud Rebue
Developer URI: http://cloudrebue.co.ke/
Text Domain: woocommerce-extension

WC requires at least: 4.6
WC tested up to: 5.9.0

License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: crsms
*/
if (!defined('ABSPATH')) die('Cannot be accessed directly!');
const CLOUDREBUESMS_VERSION = '1.0.0';

function crbsms_dir(){ return __DIR__; }

function crbsms_url(){
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
	// $account_id=get_option('account_id');
	$token=get_option('crsms_token');
	$senderid=$senderid?:get_option('crsms_default_sender');
	// $token = base64_encode($account_id.':'.$account_api_key);
	
	$default_unicode=get_option('crsms_default_unicode',0);
    if($unicode===null)$unicode=$default_unicode;
    
	$post_data=array(
		'action'=>'send_sms',
		'sender'=>$senderid,
		'phone'=>$recipients,
		'correlator'=>'wp-sms',
		'link_id'=>null,
		'endpoint'=>null,
		'message'=>$message
		);

	if(!empty($contacts))$post_data['contacts']=$contacts;
	
	if(!empty($flash))$post_data['type']=$flash;
	if(!empty($send_at))$post_data['send_at']=date('Y-m-d H:i',$send_at);
	
 	$endpoint = "https://bulk.cloudrebue.co.ke/api/v1/send-sms";

	$data_string = json_encode($post_data);

	$response = wp_remote_post(
	$endpoint,
	array(
		'headers' => array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		),
		'body'    => $data_string,
	)
	);
	$result = is_wp_error($response)
	? array('errorCode' => 1, 'errorMessage' => wp_remote_retrieve_response_message($response))
	: json_decode($response['body'], true);

	return is_null()
	? $result
	: call_user_func('', $result);
}

add_action('init', function () {
    $D = crbsms_dir();

    if (!current_user_can('edit_others_posts')) return;

	add_action('admin_menu', function () {
		if (!current_user_can('activate_plugins')) return;

		add_submenu_page('options-general.php', __('CloudRebueSMS Settings', 'cloudrebuesms'), __('CloudRebueSMS Settings', 'cloudrebuesms'), 'administrator', 'cloudrebuesms', function () {
			wp_enqueue_script('jquery-ui-tooltip');
			wp_enqueue_script('jquery-ui-sortable');
			include crbsms_dir() . "/templates/settings_page.php";
		});
	});

	add_action('admin_init', function () {
		register_setting('cloudrebuesms', 'account_id');
		register_setting('cloudrebuesms', 'account_api_key');
		register_setting('cloudrebuesms', 'crsms_token');
		register_setting('cloudrebuesms', 'crsms_default_sender');
        
		register_setting('cloudrebuesms', 'crsms_notif_wc-new');
		register_setting('cloudrebuesms', 'crsms_notif_wc-payment');
        
        if(function_exists('wc_get_order_statuses')){
            $woo_statuses=wc_get_order_statuses();
            foreach($woo_statuses as $woo_status=>$woo_status_descr)
                register_setting('cloudrebuesms', "crsms_notif_$woo_status");            
        }
	});
	
}, 9);

function crsms_replace_placeholders($template,$order,array $more_values=array()){
    $values=array();
    $values['billing_first_name']=$order->billing_first_name;
    $values['billing_last_name']=$order->billing_last_name;
    $values['billing_company']=$order->billing_company;
    $values['billing_address']=$order->billing_address;
    $values['order_id']=$order->order_id;
    $values['order_number']=$order->order_number;
    $values['order_total']=$order->order_total;
    $values['status']=$order->status;
    if(is_array($more_values)&&!empty($more_values))$values=array_merge($values,$more_values);
    
    $find=array(); $replace=array();
    foreach($values as $rk=>$rv){
        $find[]="%$rk%"; $replace[]=$rv;
    }
    return str_ireplace($find,$replace,$template);
}


function crsms_woo_order_status_changed($order_id,$old_status,$new_status) {
    $order = wc_get_order($order_id);
    $recipient=$order->get_billing_phone('view');
    if(empty($recipient))return;    
    $message_template=trim(get_option("crsms_notif_wc-$new_status"));
    if(empty($message_template))return;
    
    $message=crsms_replace_placeholders($message_template,$order);
    crsms_send_sms($message,$recipient);
}
add_action('woocommerce_order_status_changed', 'crsms_woo_order_status_changed', 10, 3);

add_action('woocommerce_new_order',function($order_id){
    $order = wc_get_order($order_id);
    $recipient=$order->get_billing_phone('view');
    if(empty($recipient))return;
    $message_template=trim(get_option("crsms_notif_wc-new"));
    if(empty($message_template))return;
    $message=crsms_replace_placeholders($message_template,$order);
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
    $message=crsms_replace_placeholders($message_template,$order);
    crsms_send_sms($message,$recipient);
});

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'cloudrebuesms_add_plugin_page_settings_link');
function cloudrebuesms_add_plugin_page_settings_link($links){
	$links[]='<a href="'.admin_url('options-general.php?page=cloudrebuesms').'">'.__('Settings').'</a>';
	return $links;
}