<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!');?>

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

		.error{color:red;}
	</style>
	
	<div class="wrap">
			<?php 
			/**
			 * Check if WooCommerce is active
			 **/
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				//Do nothing
			}else{
				echo '<div class="alert alert-danger error"><p>Cloud Rebue SMS plugin requires woocommerce to be installed and active.</p></div>';
			}
			?>
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
                    <?php $link = [':link' => '<a href="https://bulk.cloudrebue.co.ke/account/profile" target="_blank"><strong>Bulk SMS Portal here</strong></a>']; ?>
                    <?php echo strtr(__('Please enter your account details below. You find and manage your account credentials from :link.', 'cloudrebuesms'), $link); ?>
                </p>

                <table class="form-table">
                    <!-- <tr valign="top">
                        <th scope="row"><?php _e('Account ID', 'cloudrebuesms'); ?></th>
                        <td><input type="text" name="account_id" value="<?php echo esc_attr(get_option('account_id')); ?>"
                                   size="32"/></td>
                    </tr> -->
                    <tr valign="top">
                        <th scope="row"><?php _e('Access Token', 'cloudrebuesms'); ?></th>
                        <td><input type="text" name="crsms_token"
                                   value="<?php echo esc_attr(get_option('crsms_token')); ?>" size="100"/>
						<p>
							<?php $link = [':link' => '<a href="https://bulk.cloudrebue.co.ke/img/generate-api-token.jpg" target="_blank"><strong>View</strong></a>']; ?>
							<?php echo strtr(__('See the screenshot :link.', 'cloudrebuesms'), $link); ?>
						</p>
						</td>
                    </tr>
					<tr valign="top">
                        <th scope="row"><?php _e('Default Sender ID', 'cloudrebuesms'); ?></th>
                        <td>
                            <label>
                                <input type="text" maxlength="11" name="crsms_default_sender"
                                       value="<?php echo esc_attr(get_option('crsms_default_sender')); ?>">
                            </label>
                            <p class="help-block description">
                                <?php _e('Must consist of at most 11 characters.', 'cloudrebuesms'); ?>
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
			<br><p>
                    <?php $link = [':link' => '<a href="https://bulk.cloudrebue.co.ke/" target="_blank"><strong>Bulk SMS Portal here</strong></a>']; ?>
                    <?php echo strtr(__('Access the Bulk Portal Using the :link.', 'cloudrebuesms'), $link); ?>
                </p>
			</div>
		</div>
	</div>

	<script type='text/javascript'>
		jQuery(function($){
			$('.custom-nav-tabs .nav-tab').on('click',function(evt){
				evt.preventDefault();
				var tab_id = $(this).attr('href');
				$('.custom-nav-tabs .nav-tab').removeClass('active');
				$('.custom-nav-pane').removeClass('active');

				$(this).addClass('active');
				$(tab_id).addClass('active');
			})
		});
		
	</script>

