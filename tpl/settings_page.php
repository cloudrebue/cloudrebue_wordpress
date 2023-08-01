<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!');
// double check for admin privileges
if (!current_user_can('administrator')) {
    wp_die(__('Sorry, you are not allowed to access this page.', 'wp-reset'));
}
?>

<div class="wrap">
    <div id="wpr-content">
        <!-- <header>
            <div class="wpr-container2">';
                <img id="logo-icon" src="<?php echo crsms_url() ?>/img/banner.png" title="' . __('Cloud Rebue SMS', 'wp-reset') . '" alt="' . __('Cloud Rebue SMS', 'cloudrebuesms') . '">
            </div>
        </header> -->
        <h2 class="m-5"><?php _e('Cloud Rebue SMS Settings', 'cloudrebuesms'); ?></h2>
        <div id="wpr-notifications">
            <div class="wpr-container">
                <div class="card2 notice-wrapper notice-info postbox" style="padding: 50px;">
                    <h2>Please help us spread the word &amp; keep the plugin up-to-date</h2>
                    <p>If you use &amp; enjoy Cloud Rebue SMS, <b>please rate it on WordPress.org</b>. It only takes a second and helps us keep the plugin maintained. Thank you!</p>
                    <p><a class="button-primary button" title="Rate Cloud Rebue SMS" target="_blank" href="https://wordpress.org/support/plugin/cloud-rebue-wpsms/reviews/#new-post">Rate the plugin ★★★★★</a> <a href="#" class="wpr-dismiss-notice dismiss-notice-rate" data-notice="rate">I've already rated it</a></p>
                </div>
            </div>
        </div>

        <div class='custom-nav-tabs' id='settings_tabs' style="padding-bottom: 50px;">
            <a class='active nav-tab' href='#baseTab'>
                <?php _e('Settings', 'cloudrebuesms'); ?>
            </a>
            <!-- <a class='nav-tab' href='#smsPortalTab'>
                <?php _e('SMS Portal', 'cloudrebuesms'); ?>
            </a> -->
            <!-- <a class='nav-tab' href='#buildShortcodeTab'>
				<?php _e('Build Shortcode', 'cloudrebuesms'); ?>
			</a> -->
            <a class='nav-tab' href='#securityTab'>
                <?php _e('Security / 2FA', 'cloudrebuesms'); ?>
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
                        <?php $link = [':link' => '<a href="https://bulk.cloudrebue.co.ke/" target="_blank"><strong>Cloud Rebue SMS here</strong></a>']; ?>
                        <?= strtr(__('Please enter your account details below. You will find, create and manage your account credentials from :link.', 'cloudrebuesms'), $link); ?>
                    </p>

                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('API Key', 'cloudrebuesms'); ?></th>
                            <td><input type="text" name="crsms_token" value="<?php echo esc_attr(get_option('crsms_token')); ?>" size="64" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Default sender', 'cloudrebuesms'); ?></th>
                            <td>
                                <label>
                                    <input type="text" maxlength="15" name="crsms_default_sender" size="64" value="<?= esc_attr(get_option('crsms_default_sender')); ?>">
                                </label>
                                <p class="help-block description">
                                    <?php _e('Must consist of at most 11 characters or 15 digits.', 'cloudrebuesms'); ?>
                                </p>
                            </td>
                            <!-- </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Enable sending UI', 'cloudrebuesms'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox"
                                       name="crsms_enable_ui" <?= get_option('crsms_enable_ui') ? 'checked' : ''; ?>>
                                <?php _e('Yes, enable the SMS sending UI', 'cloudrebuesms'); ?>
                            </label>
                            <p class="help-block description">
                                <?php _e('Enabling this adds a new admin menu for sending SMSs and listing sent messages, as well as managing contacts.', 'cloudrebuesms'); ?>
                            </p>
                        </td>
                    </tr> -->
                            <!-- <tr valign="top">
                        <th scope="row"><?php _e('Unicode By Default', 'cloudrebuesms'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox"
                                       name="crsms_default_unicode" <?= get_option('crsms_default_unicode') ? 'checked' : ''; ?>
                                        value="1">
                                <?php _e('Yes, send sms with unicode encoding by default', 'cloudrebuesms'); ?>
                            </label>
                            <p class="help-block description">
                                <?php _e('Unicode preserves special non-english characters (e.g arabic, chinese...), but at 72 characters per page (instead of 160)', 'cloudrebuesms'); ?>
                                <i class="info has-tooltip"
                                   title="<?= esc_attr(__('The choice here only applies to the auto-triggered sms like woo-commerce notification hooks', 'cloudrebuesms')) ?>"></i>
                            </p>
                        </td>
                    </tr> -->
                        <tr valign="top">
                            <th scope="row"><?php _e('Enable security-tab', 'cloudrebuesms'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" class="checked" name="crsms_security_enable" <?= get_option('crsms_security_enable') ? 'checked' : ''; ?> id="cgsmsSecurityEnable" value="1">
                                    <?php _e('Yes, enable the security settings tab', 'cloudrebuesms'); ?>
                                </label>
                                <p class="help-block description">
                                    <?php _e('Used to enable two-factor security for logging into your WordPress backend.', 'cloudrebuesms'); ?>
                                    <i class="info has-tooltip" title="<?= esc_attr(__('Enabling two-factor forces users with certain roles, to connect their cellphone with their user account. Afterwards an additional step is added after the user/password-prompt, which asks for a one-time code. This code is immediately sent via SMS to the users cellphone. It is possible to remember a device for an extended period of time, so the user will not have to reauthorize all the time.', 'cloudrebuesms')) ?>"></i>
                                </p>
                            </td>
                        </tr>

                    </table>

                    <hr>
                    <?php submit_button(); ?>
                </div>

                <div class='custom-nav-pane' id='securityTab'>
                    <p>
                        <?= __('The two-factor login system is based solely on SMS, so your users will not need any apps, thus making it compatible with any mobile phone. All you will ever pay, is the cost of the text messages sent as part of the login process, while getting the greatly added security of two-factor security.', 'cloudrebuesms'); ?>
                    </p>

                    <?php if (get_option('crsms_security_enable')) { ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php _e('Emergency bypass URL', 'cloudrebuesms'); ?></th>
                                <?php
                                $login_bypass_url = wp_login_url();
                                $login_bypass_url .= (strpos($login_bypass_url, '?') === false) ? '?' : '&';
                                $login_bypass_url .= 'action=gwb2fa&c=' . CgsmsSecurityTwoFactor::getBypassCode();
                                ?>
                                <td>
                                    <input type="hidden" name="crsms_security_bypass_code" value="<?= CgsmsSecurityTwoFactor::getBypassCode(); ?>" />
                                    <input type="text" size="85" readonly value="<?= $login_bypass_url; ?>" placeholder="<?php _e('New code is generated on save', 'cloudrebuesms'); ?>" /> <button id="cgsmsSecurityBypassCodeReset" type="button" class="button button-secondary"><?php _e('Reset', 'cloudrebuesms'); ?></button>
                                    <p class="help-block description">
                                        <strong style="color: blue"><?php _e('This URL should be copied to a safe place!', 'cloudrebuesms'); ?></strong> <?php _e('Use it to bypass all two-factor security measures when logging in.', 'cloudrebuesms'); ?>
                                        <i class="info has-tooltip" title="<?= esc_attr(__('This could rescue you from a site lockout, in case your Cloud Rebue SMS-account ran out of credit (you should enable auto-charge to avoid this) or if you forgot to update your profile when you got a new number. You should not share this URL, but keep it as a recovery measure for you as an administrator.', 'cloudrebuesms')) ?>"></i>
                                    </p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('User roles with two-factor', 'cloudrebuesms'); ?></th>
                                <td>
                                    <?php $roles = CgsmsSecurityTwoFactor::getRoles(); ?>
                                    <select name="crsms_security_required_roles[]" multiple size="<?= min(count($roles), 6); ?>" style="min-width: 250px;">
                                        <?php foreach ($roles as $role_key => $role_opt) : ?>
                                            <option value="<?= $role_key ?>" <?= $role_opt[1] ? 'selected' : '' ?>><?= $role_opt[0]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="help-block description">
                                        <?php _e('All roles selected will be forced to upgrade to two-factor on their next login. We recommend that all roles above subscriber-level are selected.', 'cloudrebuesms'); ?>
                                        <br>
                                        <?php _e('Hold down CTRL (PC) / CMD (Mac) to select multiple roles.', 'cloudrebuesms'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Two-factor cookie lifetime', 'cloudrebuesms'); ?></th>
                                <td>
                                    <select name="crsms_security_cookie_lifetime">
                                        <?php $options = [
                                            0 => __('Re-authorize with every login', 'cloudrebuesms'),
                                            1 => __('Remember for up to 1 day', 'cloudrebuesms'),
                                            7 => __('Remember for up to 1 week', 'cloudrebuesms'),
                                            14 => __('Remember for up to 2 weeks', 'cloudrebuesms'),
                                            30 => __('Remember for up to 1 month', 'cloudrebuesms')
                                        ]; ?>
                                        <?php foreach ($options as $days => $text) : ?>
                                            <option <?= get_option('crsms_security_cookie_lifetime') == $days ? 'selected' : ''; ?> value="<?= $days; ?>"><?= $text; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="help-block description">
                                        <?php _e('How often must the user be forced to re-authorize via two-factor, when visiting from the same web browser?', 'cloudrebuesms'); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    <?php } else { ?>
                        <strong>Security feature has been disabled</strong>
                    <?php } ?>

                    <hr>
                    <p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Changes" /></p>
                </div>

                <div class='custom-nav-pane' id='woocommerceNotificationsTab'>
                    <?php
                    if (!function_exists('wc_get_order_statuses')) { ?>
                        <h4>Woo-commerce is not active.</h4>
                    <?php
                    } else { ?>
                        <div class="card2 postbox" style="padding: 50px;">
                            <h4 id="tool-delete-wp-content"><span class="card-name">You can use any of the following - <a data-feature="tool-delete-wp-content" class="pro-feature tooltip tooltipstered" href="https://cloudrebue.co.ke/knowledgebase/using-placeholders-in-woocommerce/" target="_blank"><span class="pro">placeholders</span> in the SMS</a></span>
                            </h4>
                            <div class="card-body" style="">
                                <p>This is the available placeholders/merge tags: <code>%billing_first_name%</code>, <code>%billing_last_name%</code>, <code>%billing_company%</code>, <code>%billing_address%</code>, <code>%billing_country%</code>, <code>%billing_city%</code>, <code>%billing_state%</code>, <code>%billing_email%</code>,<code>%billing_phone%</code>, <code>%payment_method%</code>, <code>%payment_method_title%</code>, <code>%order_number%</code>, <code>%order_total%</code>, <code>%order_discount%</code>, <code>%status%</code>, <code>%date_completed%</code>, <code>%date_paid%</code> .</p>
                                <p class="tool-icons"><i class="icon-doc-text-inv red"></i> Access Full <b>Documentation</b> by visiting the link below</p>
                                <p class="mb0"><a class="button button-delete button-pro-feature" href="https://cloudrebue.co.ke/cat/cloud-rebue-wpsms-plugin/" target="_blank">Read full - <span data-feature="tool-delete-wp-content" class="pro-feature"><span class="pro">Documentation</span> Here</span></a></p>
                            </div>
                        </div>
                        <div class="card2 postbox" style="padding: 50px;">
                            <h3>New Order & Payment Notifications Settings</h3>
                            <div class="card-body" style="">
                                <p>Enter the contents of the SMS message to be sent to the billing-phone number. <br />
                                    <i>(or leave the field empty to disable the notification type)</i>.
                                </p>
                                <div class='form-group'>
                                    <label style='display:block;font-weight:bold;'>New Order SMS Content</label>
                                    <textarea name='crsms_notif_wc-new' style='width:90%;max-width:100%; margin-top:20px; margin-bottom:10px;'><?php echo get_option("crsms_notif_wc-new"); ?></textarea>
                                </div>
                                <div class='form-group'>
                                    <label style='display:block;font-weight:bold; margin-top:20px; margin-bottom:10px;'>Payment Completed SMS Content</label>
                                    <textarea name='crsms_notif_wc-payment' style='width:90%;max-width:100%;'><?php echo get_option("crsms_notif_wc-payment"); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card2 postbox" style="padding: 50px;">
                            <h3>New Order & Payment Notifications Settings</h3>
                            <div class="card-body" style="">
                                <p>Enter the contents of the SMS message to be sent to the billing-phone number. <br />
                                    <i>(or leave the field empty to disable the notification type)</i>.
                                </p>
                                <h3>Order Status Change</h3>
                                <?php
                                $woo_statuses = wc_get_order_statuses();
                                foreach ($woo_statuses as $woo_status => $woo_status_descr) { ?>
                                    <div class='form-group'>
                                        <label style='display:block;font-weight:bold; margin-top:20px; margin-bottom:10px;'>Order <?php echo $woo_status_descr; ?> - SMS Content</label>
                                        <textarea name='crsms_notif_<?php echo $woo_status; ?>' style='width:90%;max-width:100%;'><?php echo get_option("crsms_notif_$woo_status"); ?></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>
                        <p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Changes" /></p>
                    <?php
                    }
                    ?>
                </div>
            </form>

            <div class='custom-nav-pane' id='smsPortalTab'>
                <br><br>
                <a href="https://bulk.cloudrebue.co.ke">Visit Bulk Portal Here</a>

            </div>

            <div class='custom-nav-pane' id='buildShortcodeTab'>
                <div>
                    <h2 style='margin-top:5px;'><i class='fa fa-code'></i> Shortcode Generator</h2>
                    <hr />
                    <p>Generate and copy short-code here, which you can then copy into any of your article, for it to be automatically replaced with the corresponding Cloud Rebue SMS interface</p>
                    <div class='clearfix'></div>
                    <div id='cgsms_shortcode_generator'>
                        <div class='form-group'>
                            <label>Sub-account</label>
                            <input type='text' name='temp_sub_account' id='temp_sub_account' />
                        </div>
                        <div class='form-group'>
                            <label>Sub-account Password</label>
                            <input type='password' name='temp_sub_account_pass' id='temp_sub_account_pass' />
                        </div>

                        <div class='form-group'>
                            <label>
                                <input type='checkbox' value='1' name='no_tabs' />
                                Hide Tabs/Menu Bar
                            </label>
                            <label>
                                <input type='checkbox' value='1' name='no_translate' />
                                Hide Language Switch
                            </label>
                            <label>
                                <input type='checkbox' value='1' name='disable_login' />
                                Disable Login Page <small>(only access through token)</small>
                            </label>
                        </div>

                        <?php
                        $all_tabs_list = array('account' => 1, 'send_sms' => 1, 'sms_log' => 1, 'sms_batches' => 1, 'contacts' => 1, 'sub_transactions' => 1, 'coverage_list' => 1, 'gateway_api' => 1);
                        ?>
                        <div class='form-group'>
                            <label>Tabs Menu</label>
                            <select class='form-control input-sm' id='cgsms_shortcode_multiple_option' name='tabs_list[]' multiple>
                                <option value=''>ALL</option>
                                <?php foreach ($all_tabs_list as $tab => $tab_flag) { ?>
                                    <option value='<?php echo $tab; ?>'><?php echo ucwords(str_replace('_', ' ', $tab)); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class='clearfix'></div>
                    <h4 id='generated_shortcode_div'></h4>
                    <div>Shortcodes generated here can also be used directly inside template source code with php function, like: <code>&lt;?php echo do_shortcode("[crsms]"); ?></code></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    jQuery(function($) {
        $('#cgsms_shortcode_generator [name]').on('change keyup', function() {
            var str = "[crsms";
            $('#cgsms_shortcode_generator input[type=checkbox]').each(function() {
                var temp_val = $(this).is(':checked') ? 1 : 0;
                var temp_name = $(this).attr('name');
                str += " " + temp_name + "='" + temp_val + "'";
            });

            var temp_tab_list = [];

            if ($('#cgsms_shortcode_multiple_option option:selected').length) {
                if (!$("#cgsms_shortcode_multiple_option option[value='']").is(':selected')) {
                    $('#cgsms_shortcode_multiple_option option:selected').each(function() {
                        var temp_val = $(this).attr('value');
                        if (temp_val != '') temp_tab_list.push(temp_val);
                    });
                } else {
                    $('#cgsms_shortcode_multiple_option option').each(function() {
                        var temp_val = $(this).attr('value');
                        if (temp_val != '') temp_tab_list.push(temp_val);
                    });
                }
            }

            if (temp_tab_list.length) {
                str += " tabs_list='" + (temp_tab_list.join(',')) + "' ";
            }

            var temp_name = $('#temp_sub_account').val();
            var temp_pass = $('#temp_sub_account_pass').val();
            if (temp_name != '' || temp_pass != '') {
                var token_str = base64_encode(temp_name + ':' + temp_pass);
                str += " token='" + token_str + "' ";
            }

            str += " ]";

            $('#generated_shortcode_div').html("<strong>Generated Shortcode:</strong> <code>" + str + "</code>");
        });

        $('#cgsms_shortcode_multiple_option').trigger('change');

        $('.custom-nav-tabs .nav-tab').on('click', function(evt) {
            evt.preventDefault();
            var tab_id = $(this).attr('href');
            $('.custom-nav-tabs .nav-tab').removeClass('active');
            $('.custom-nav-pane').removeClass('active');

            $(this).addClass('active');
            $(tab_id).addClass('active');
        })
    });
</script>