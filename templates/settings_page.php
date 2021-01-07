<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
	<style type='text/css'>
		.custom-nav-tabs{
			margin: 0px;
			padding: 0px;
			list-style: none;
		}
		.custom-nav-tabs .nav-tab{
			background: none;
			color: #222;
			display: inline-block;
			padding: 10px 15px;
			cursor: pointer;
		}

		.custom-nav-tabs .nav-tab.active{background:#ffffff;color:#222;}
		.custom-nav-tabs .nav-tab{text-decoration:none;}
		.custom-nav-pane{display: none;background: #ededed;}
		.custom-nav-pane.active{display: inherit;}
		.custom-nav-panes{clear:both !important;}
	</style>
	
	<div class="wrap">
		<h2><?php _e('CloudRebueSMS Settings', 'cloudrebuesms'); ?></h2>
		
		<div class='custom-nav-tabs' id='settings_tabs'>
			<a class='active nav-tab' href='#baseTab'>
				<?php _e('General settings', 'cloudrebuesms'); ?>
			</a>
			<a class='nav-tab' href='#smsPortalTab'>
				<?php _e('SMS Portal', 'cloudrebuesms'); ?>
			</a>
			<a class='nav-tab' href='#woocommerceNotificationsTab'>
				<?php _e('Woo-commerce Notifications', 'cloudrebuesms'); ?>
			</a>
		</div>
		<div class='custom-nav-panes'>			
			<form method="post" action="options.php">
			<?php settings_fields('cloudrebuesms'); ?>
			
			<?php do_settings_sections('cloudrebuesms'); ?>
            <div class='custom-nav-pane active' id='baseTab'>
                <p>
                    <?php $link = [':link' => '<a href="https://bulk.cloudrebue.co.ke/account/apikeys" target="_blank"><strong>Bulk SMS Portal here</strong></a>']; ?>
                    <?= strtr(__('Please enter your account details below. You find and manage your account credentials from :link.', 'cloudrebuesms'), $link); ?>
                </p>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Account ID', 'cloudrebuesms'); ?></th>
                        <td><input type="text" name="account_id" value="<?php echo esc_attr(get_option('account_id')); ?>"
                                   size="32"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Account API Key', 'cloudrebuesms'); ?></th>
                        <td><input type="text" name="account_api_key"
                                   value="<?php echo esc_attr(get_option('account_api_key')); ?>" size="100"/></td>
                    </tr>
					<tr valign="top">
                        <th scope="row"><?php _e('Default Sender ID', 'cloudrebuesms'); ?></th>
                        <td>
                            <label>
                                <input type="text" maxlength="11" name="crsms_default_sender"
                                       value="<?= esc_attr(get_option('crsms_default_sender')); ?>">
                            </label>
                            <p class="help-block description">
                                <?php _e('Must consist of at most 11 characters.', 'cloudrebuesms'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Enable SMS portal UI', 'cloudrebuesms'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox"
                                       name="crsms_enable_ui" <?= get_option('crsms_enable_ui') ? 'checked' : ''; ?>>
                                <?php _e('Yes, enable the SMS portal UI', 'cloudrebuesms'); ?>
                            </label>
                            <p class="help-block description">
                                <?php _e('Enabling this adds a new admin menu for Cloud Rebue SMS portal.', 'cloudrebuesms'); ?>
                            </p>
                        </td>
                    </tr>
                   
                </table>
				
				<hr>
				<?php submit_button(); ?>
            </div>
			
            <div class='custom-nav-pane' id='woocommerceNotificationsTab'>
                <?php
                    if(!function_exists('wc_get_order_statuses')){ ?>
                        <h4>Woo-commerce is not active.</h4>
                        <?php
                    } else { ?>
                        <table style='width:100%;'>
                            <tr>
                                <th style='width:50%;'>New Order & Payment</th>
                                <th style='width:50%;'>Order Status Change</th>
                            </tr>
                            <tr>
                                <td style='vertical-align:top;'>
                                    <p>Enter the contents of the SMS message to be sent to the billing-phone number. <br/>
                                    <i>(or leave the field empty to disable the notification type)</i>.</p>
                                    <div class='form-group'>
                                        <label style='display:block;font-weight:bold;'>New Order SMS Content</label>
                                        <textarea name='cgsms_notif_wc-new' style='width:400px;max-width:100%;' ><?php echo get_option("cgsms_notif_wc-new"); ?></textarea>
                                    </div>
                                    <div class='form-group'>
                                        <label style='display:block;font-weight:bold;'>Payment Completed SMS Content</label>
                                        <textarea name='cgsms_notif_wc-payment' style='width:400px;max-width:100%;' ><?php echo get_option("cgsms_notif_wc-payment"); ?></textarea>
                                    </div>
                                    
                                    <div style='font-weight:bold;'>You can use any of the following placeholders in the SMS.</div>
                                    <ul style='font-style:italic;'>                            
                                        <li>Billing First Name: %billing_first_name%</li>
                                        <li>Billing family: %billing_last_name%</li>
                                        <li>Billing Company: %billing_company%</li>
                                        <li>Billing Address: %billing_address%</li>
                                        <li>Order id: %order_id%</li>
                                        <li>Order number: %order_number%</li>
                                        <li>Order Total: %order_total%</li>
                                        <li>Order status: %status%</li>
                                    </ul>
                                </td>
                                <td>
                    <?php 
                        $woo_statuses=wc_get_order_statuses();
                        foreach($woo_statuses as $woo_status=>$woo_status_descr){ ?>
                        <div class='form-group'>
                            <label style='display:block;font-weight:bold;'>Order <?php echo $woo_status_descr; ?> - SMS Content</label>
                            <textarea name='crsms_notif_<?php echo $woo_status; ?>' style='width:400px;max-width:100%;' ><?php echo get_option("crsms_notif_$woo_status"); ?></textarea>
                        </div>
                        <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <p class="submit"><input type="submit" name="submit"  class="button button-primary" value="Save Changes"  /></p>
                        <?php 
                    }
                ?>            
            </div>
            </form>
			
            <div class='custom-nav-pane' id='smsPortalTab'>
				<?php 
					$temp_token='';
					$account_id=get_option('account_id');
					$account_api_key=get_option('account_api_key');
					if(!empty($account_id)&&!empty($account_api_key))$temp_token="?token=".base64_encode($account_id.':'.$account_api_key);
				?>
				<iframe 
					src='//bulk.cloudrebue.co.ke<?php echo $temp_token; ?>' 
					style='width:100%;height:560px;border:1px solid #bbb;border-radius:3px;'>
					</iframe>
			</div>
		</div>
	</div>

	<script type='text/javascript'>
		jQuery(function($){
			$('#cgsms_shortcode_generator [name]').on('change keyup',function(){
				var str="[cgsms";
				$('#cgsms_shortcode_generator input[type=checkbox]').each(function(){
					var temp_val=$(this).is(':checked')?1:0;
					var temp_name=$(this).attr('name');
					str+=" "+temp_name+"='"+temp_val+"'";
				});
				
				var temp_tab_list=[];
				
				if($('#cgsms_shortcode_multiple_option option:selected').length){
					if(!$("#cgsms_shortcode_multiple_option option[value='']").is(':selected')){
						$('#cgsms_shortcode_multiple_option option:selected').each(function(){
							var temp_val=$(this).attr('value');
							if(temp_val!='')temp_tab_list.push(temp_val);
						});
					}
					else {
						$('#cgsms_shortcode_multiple_option option').each(function(){
							var temp_val=$(this).attr('value');
							if(temp_val!='')temp_tab_list.push(temp_val);
						});
					}
				}
				
				if(temp_tab_list.length){
					str+=" tabs_list='"+(temp_tab_list.join(','))+"' ";
				}
				
				var temp_name=$('#temp_sub_account').val();
				var temp_pass=$('#temp_sub_account_pass').val();
				if(temp_name!=''||temp_pass!=''){
					var token_str=base64_encode(temp_name+':'+temp_pass);
					str+=" token='"+token_str+"' ";
				}
				
				str+=" ]";
				
				$('#generated_shortcode_div').html("<strong>Generated Shortcode:</strong> <code>"+str+"</code>");
			});
			
			$('#cgsms_shortcode_multiple_option').trigger('change');
			
			$('.custom-nav-tabs .nav-tab').on('click',function(evt){
				evt.preventDefault();
				var tab_id = $(this).attr('href');
				$('.custom-nav-tabs .nav-tab').removeClass('active');
				$('.custom-nav-pane').removeClass('active');

				$(this).addClass('active');
				$(tab_id).addClass('active');
			})
		});

		var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
		function base64_encode(str){ return Base64.encode(str); }
		function base64_decode(str){ Base64.decode(str); }		
	</script>

