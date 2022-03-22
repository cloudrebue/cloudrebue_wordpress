<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<?php

class CgsmsSecurityTwoFactor
{
    public static $TMP_TOKEN = null;

    /**
     * Returns a list of roles with the key being the role attribute name, and the value being a 0-indexed array consiting
     * of the pretty title and the current status (boolean).
     *
     * @return array
     */
    public static function getRoles()
    {
        $roles = wp_roles()->get_names();
        $current_roles = get_option('crsms_security_required_roles') ? : [];

        $out = [];
        foreach ($roles as $k => $t) {
            $enabled = in_array($k, $current_roles);
            $out[$k] = [$t, $enabled];
        }

        return $out;
    }

    /**
     * Returns the current bypass code or generates and saves a new one. The bypass code can be used to bypass the
     * entire two-factor login scheme and should therefor be a last-result for administrators who are locked-out.
     */
    public static function getBypassCode()
    {
        $code = get_option('crsms_security_bypass_code');
        if (!$code) {
            $code = strtolower(wp_generate_password(32, false, false));
            update_option('crsms_security_bypass_code', $code, false);
        }

        return $code;
    }

    /**
     * Hooks into wp_login and intercepts, if all two-factor steps are not passed.
     *
     * @param $username
     * @param WP_User $user
     */
    public static function handleLoginHook($username, \WP_User $user)
    {
        if (self::bypassModeEnabled()) return;

        if (!self::userNeedsTwoFactor($user)) return;
        if (self::userHasValidTwofactorCookie($user)) return;
        self::replaceLoginCookieWithTempCookie($user);
        self::renderTwoFactorSteps($user);

        die();
    }

    /**
     * Returns true if the user provided requires two-factor authentication.
     *
     * @param WP_User $user
     * @return bool
     */
    public static function userNeedsTwoFactor(\WP_User $user)
    {
        $all_roles = self::getRoles();
        foreach ($user->roles as $role) {
            if (!isset($all_roles[$role])) continue; // a new unknown role
            if ($all_roles[$role][1]) return true;
        }
        return false;
    }

    /**
     * Returns true if the list of cookies contains a valid two-factor cookie.
     *
     * @param WP_User $user
     * @return bool
     */
    private static function userHasValidTwofactorCookie(\WP_User $user)
    {
        if (isset($_COOKIE['cgsms_2f_' . $user->ID]) && ($cookie_token = trim($_COOKIE['cgsms_2f_' . $user->ID])) && $user->cgsms_2f_tokens) {
            self::refreshExpiryOfUserTwoFactorTokens($user);
            $tokens = $user->cgsms_2f_tokens;
            if (isset($tokens[$cookie_token])) return true;
        }

        return false;
    }

    /**
     * Make sure that the WP_User's cgsms_2f_tokens-array only contains non-expired tokens.
     *
     * @param WP_User $user
     */
    private static function refreshExpiryOfUserTwoFactorTokens(\WP_User $user)
    {
        $new_tokens = [];
        foreach ($user->cgsms_2f_tokens ?: [] as $token => $expiry) {
            if (time() < $expiry) $new_tokens[$token] = $expiry;
        }
        if (count($new_tokens) !== count($user->cgsms_2f_tokens)) {
            update_user_meta($user->ID, 'cgsms_2f_tokens', $new_tokens);
            $user->cgsms_2f_tokens = $new_tokens;
        }
    }

    /**
     * Clears the auth cookie and remembers the current user and other login settings in a transient, giving the
     * transients key via a cookie.
     *
     * @param WP_User $user
     * @return string
     */
    public static function replaceLoginCookieWithTempCookie(\WP_User $user)
    {
        $remember = isset($_POST['rememberme']) && $_POST['rememberme'] == 'forever';
        $redir = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : get_admin_url();
        $code = rand(100000, 999999);

        wp_clear_auth_cookie();

        $temp_token = $user->ID . '_' . wp_generate_password(12, false, false);
        set_transient('cgsms_2f_' . $temp_token, ['user' => $user->ID, 'remember' => $remember, 'redirect_to' => $redir, 'code' => $code], 60 * 30);
        self::$TMP_TOKEN = $temp_token;

        return $temp_token;
    }

    /**
     * Sets a regular cookie on the client, but heavily inspired by how WordPress sets cookies - ie. forcing security,
     * using WP's PATH's etc.
     *
     * @param $key
     * @param $value
     * @param $expiration
     */
    public static function setCookie($key, $value, $expiration)
    {
        $secure = is_ssl();
        setcookie($key, $value, $expiration, COOKIEPATH, COOKIE_DOMAIN, $secure, true);
    }

    /**
     * Render the HTML for the two-factor steps.
     *
     * @param WP_User $user
     */
    public static function renderTwoFactorSteps(\WP_User $user)
    {
        // add mobile throttle?
        $throttle = CgsmsSecurityTwoFactor::checkIfThrottled($user->ID, CgsmsSecurityTwoFactorAddMobile::THROTTLE_ACTION, CgsmsSecurityTwoFactorAddMobile::THROTTLE_MAX_TRIES, CgsmsSecurityTwoFactorAddMobile::THROTTLE_EXPIRES);
        if (is_wp_error($throttle)) wp_die($throttle);

        // login code throttle?
        $throttle2 = CgsmsSecurityTwoFactor::checkIfThrottled($user->ID, 'confirmlogin_' . $user->ID, CgsmsSecurityTwoFactorHasMobile::THROTTLE_MAX_TRIES, CgsmsSecurityTwoFactorHasMobile::THROTTLE_EXPIRES);
        if (is_wp_error($throttle2)) wp_die($throttle2);

        // let's get it on, shall we?
        if (!$user->cgsms_mcc || !$user->cgsms_mno) {
            CgsmsSecurityTwoFactorAddMobile::handleAddMobile($user);
        } else {
            CgsmsSecurityTwoFactorHasMobile::handleLogin($user);
        }
    }

    /**
     * Returns the array about the current user, redirect url etc. based on the temp token given.
     *
     * @param $temp_token
     * @return mixed|WP_Error
     */
    public static function getLoginDataByTempToken($temp_token)
    {
        $parts = explode('-', $temp_token, 2);;
        if (!count($parts) == 2) return new WP_Error('INVALID_TOKEN', __('Error: The format of the temporary token is invalid.', 'cloudrebuesms'));

        $token_data = get_transient('cgsms_2f_' . $temp_token);
        if (!$token_data) return new WP_Error('EXPIRED', __('Error: Your token seems to have expired. Please start over.', 'cloudrebuesms'));

        return $token_data;
    }

    /**
     * Given a WP_Error object, fail in a consistent way, converting error to a JSON message.
     */
    public static function jsonFail(\WP_Error $wp_error)
    {
        http_response_code(422);
        die(json_encode([
            'message' => $wp_error->get_error_message(),
            'code' => $wp_error->get_error_code()
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Register an attempt at using a throttled action. If the threshold has been reached, then a WP_Error
     * is returned, otherwise the method returns nothing.
     */
    public static function failOnThrottle($user_ID, $action, $max_attempts, $expires_after_seconds)
    {
        $throttle_key = "_cgsms_throttle_" . $action;

        $err = self::checkIfThrottled($user_ID, $action, $max_attempts, $expires_after_seconds);
        if (is_wp_error($err)) return $err;

        $throttle = get_user_meta($user_ID, $throttle_key, true) ?: [];

        // add the attempt
        $throttle[] = time() + $expires_after_seconds;
        update_user_meta($user_ID, $throttle_key, $throttle);
    }

    /**
     * Just check if the action is throttled.
     */
    public static function checkIfThrottled($user_ID, $action, $max_attempts, $expires_after_seconds)
    {
        $throttle_key = "_cgsms_throttle_" . $action;
        $orig_throttle = get_user_meta($user_ID, $throttle_key, true) ?: [];

        // remove expired
        $throttle = array_filter($orig_throttle, function ($ts) use ($expires_after_seconds) {
            return $ts > time();
        });

        // too many attempts already?
        if (count($throttle) >= $max_attempts) {
            $time_left = "<strong>" . human_time_diff(time(), current($throttle)) . "</strong>";
            $error_msg = apply_filters('cgsms_throttle_error', __('Your account is temporarily locked, due to too many verification attempts in a short time span.<br /><br />Please wait at least :time_left before trying again.', 'cloudrebuesms'), $user_ID, $action, $max_attempts, $expires_after_seconds);
            return new WP_Error('TOO_MANY_ATTEMPTS', strtr($error_msg, [':time_left' => $time_left]));
        }

        update_user_meta($user_ID, $throttle_key, $throttle);
    }

    /**
     * Create a cookie for keeping the two-factor-part of the login persistent across logins.
     *
     * @param $user_ID
     */
    public static function createClientCookie($user_ID)
    {
        $valid_cookie_lifetimes = [0, 1, 7, 14, 30];
        $cookie_lifetime = (int)get_option('crsms_security_cookie_lifetime', 0);
        if (!in_array($cookie_lifetime, $valid_cookie_lifetimes) || !$cookie_lifetime) return; // re-auth every time

        $expires_at = time() + 60 * 60 * 24 * $cookie_lifetime;
        $user = get_user_by('ID', $user_ID);

        self::refreshExpiryOfUserTwoFactorTokens($user);

        $tokens = $user->cgsms_2f_tokens ?: [];
        $token_key = wp_generate_password(24, false, false);
        $tokens[$token_key] = $expires_at;

        update_user_meta($user_ID, 'cgsms_2f_tokens', $tokens);
        $user->cgsms_2f_tokens = $tokens;

        // save cookie in request
        self::setCookie('cgsms_2f_' . $user_ID, $token_key, $expires_at);
    }

    public static function enqueueCssJs(){
        add_action('login_enqueue_scripts', function () {
			?>
			<script type="text/javascript">
				var CGSMS_PLUGINDIR = <?= json_encode(crsms_url()); ?>;
				var CGSMS_I18N_DEFAULT_ERROR = <?= json_encode(__('Sorry, but there are errors in your input. Please attend to the highlighted fields below.', 'cloudrebuesms'));?>;
				var CGSMS_ADMINURL = <?= json_encode(admin_url()); ?>;
			</script>
			<?php
		    wp_enqueue_script('jquery');			
			wp_register_script('crsms-widgets', crsms_url() . 'js/widgets.js', ['jquery','underscore'], CLOUDREBUE_VERSION, true);
            wp_enqueue_script('crsms-widgets', crsms_url() . '/js/widgets.js');
            wp_enqueue_script('crsms-wp-login', crsms_url() . '/js/wp-login.js');
            wp_enqueue_style('crsms-wp-login', crsms_url() . '/css/wp-login.css');

            $i18n = [
                'ajax_tech_error' => __('An unknown technical error occured while processing the request. Please try again or contact your administrator.', 'cloudrebuesms')
            ];
            ?>
            <script>
                var CGSMS_I18N = <?= json_encode($i18n, JSON_PRETTY_PRINT); ?>;
            </script>
            <?php
        });
    }

    /**
     * Enables bypass mode for the two-factor login flow.
     */
    public static function enableBypassMode()
    {
        $bypass_code = self::getBypassCode();
        if (!isset($_GET['c']) || $_GET['c'] != $bypass_code) {
            wp_die('<h1>' . __('Bypass code is invalid!', 'cloudrebuesms') . '</h1><p>' . __('Your two-factor bypass code in the URL, is invalid. Two-factor security is still enabled.', 'cloudrebuesms') . '</p>');
        }

        // add the extra hidden field for the bypass-code, to the login form
        add_action('login_form', function () use ($bypass_code) {
            ?>
            <input type="hidden" name="cgsms_bypass_2fa" value="<?= esc_attr($bypass_code); ?>">
            <?php
        });

        // show a message to clearly tell the user, that the bypass mode is enabled for this request
        ob_start();
        ?>
        <strong><?php _e('Two-factor bypass mode active', 'cloudrebuesms'); ?></strong><br><br/>
        <?php _e('Issues related to two-factor, should be temporarily resolved if you log in now.', 'cloudrebuesms'); ?>
        <?php
        global $error;
        $error = ob_get_clean();
    }

    /**
     * @return bool True if the current request should bypass the two-factor login security.
     */
    public static function bypassModeEnabled()
    {
        return (isset($_POST['cgsms_bypass_2fa']) && $_POST['cgsms_bypass_2fa'] == self::getBypassCode());
    }
}

/**
 * This class handles the flow related to a person logging in, who does not yet have a mobile phone attached to the
 * user profile. This runs a flow pairing the mobile phone with the account, using a one time token.
 */
class CgsmsSecurityTwoFactorAddMobile
{
    const THROTTLE_ACTION = 'add_mobile';
    const THROTTLE_MAX_TRIES = 300;
    const THROTTLE_EXPIRES = 60 * 60;

    /**
     * Show the "add mobile" login flow, ie. the flow "new users" (in two-factor terms) has to go through to add a phone
     * number to their account.
     */
    public static function handleAddMobile(\WP_user $user)
    {
        CgsmsSecurityTwoFactor::enqueueCssJs();

        include crsms_dir() . '/tpl/wp-login-add-phone.php';
    }

    /**
     * Send a code to the mobile number given and return a UI where the result can be entered.
     */
    public static function sendInitialSms()
    {
        header("Content-type: application/json");

        // token
        $tmp_token = trim($_POST['cgsms_2f_tmp']);

        // phone number
        $mcc = preg_replace('/[^0-9]+/', '', $_POST['mcc']);
        $mno = preg_replace('/[^0-9]+/', '', $_POST['mno']);
        $msisdn = preg_replace('/[^0-9]+/', '', $mcc . $mno);

        // validate the token
        $login_info = CgsmsSecurityTwoFactor::getLoginDataByTempToken($tmp_token);
        if (is_wp_error($login_info)) CgsmsSecurityTwoFactor::jsonFail($login_info);

        // save this attempt on the user
        $user_ID = $login_info['user'];
        $maybe_throttle = CgsmsSecurityTwoFactor::failOnThrottle($user_ID, self::THROTTLE_ACTION, self::THROTTLE_MAX_TRIES, self::THROTTLE_EXPIRES);
        if (is_wp_error($maybe_throttle)) CgsmsSecurityTwoFactor::jsonFail($maybe_throttle);

        // send the initial SMS
        $sender = get_bloginfo();
        $home_url = url_shorten(get_home_url());

        $message = strtr(__("Verification code: :code\nKind regards, :sender\n:home_url", 'cloudrebuesms'), [':code' => $login_info['code'], ':sender' => $sender, ':home_url' => $home_url]);
        $status = crsms_send_sms($message, $msisdn);

        // save the phone number
        set_transient('cgsms_2f_' . $tmp_token . '_phone', [$mcc, $mno], 60 * 30);

        if (is_wp_error($status)) {
            if ($status->get_error_code() == 'CGSMS_FAIL') {
                $reason = $status->get_error_message();
                list ($human, $tech) = explode("\n", $reason, 2);
                $reason = $human . "<br /><br /><small>" . nl2br($tech) . "</small>";

                CgsmsSecurityTwoFactor::jsonFail(new WP_Error('cloudrebuesms', strtr(__('The SMS could not be sent, due to the SMS-service rejecting to send the message.<br><br>Technical reason:<br />- :reason', 'cloudrebuesms'), [':reason' => $reason])));
            } else {
                CgsmsSecurityTwoFactor::jsonFail(new WP_Error('cloudrebuesms', __('The SMS could not be sent due to a technical error/misconfiguration of the Cloud Rebue SMS-plugin.<br /><br />You could try again, but probably this can only be resolved by your administrator.', 'cloudrebuesms')));
            }
        }

        // vars for HTML
        $html = self::getHtmlLoginConfirmPhone($mcc, $mno, $tmp_token);

        die(json_encode(['success' => true, 'html' => $html]));
    }

    private static function getHtmlLoginConfirmPhone($mcc, $mno, $tmp_token)
    {
        ob_start();
        include crsms_dir() . '/tpl/wp-login-confirm-phone.php';
        return ob_get_clean();
    }

    /**
     * Send the confirmation SMS.
     */
    public static function confirmSms()
    {
        header("Content-type: application/json");

        // token
        $tmp_token = trim($_POST['cgsms_2f_tmp']);

        // validate the token
        $login_info = CgsmsSecurityTwoFactor::getLoginDataByTempToken($tmp_token);
        if (is_wp_error($login_info)) CgsmsSecurityTwoFactor::jsonFail($login_info);

        // save this attempt on the user
        $user_ID = $login_info['user'];
        add_filter('cgsms_throttle_error', function ($e) {
            return __('You have tried to enter the confirmation token too many times. Please start over to try again.', 'cloudrebuesms');
        });
        $maybe_throttle = CgsmsSecurityTwoFactor::failOnThrottle($user_ID, 'confirm_' . $tmp_token, self::THROTTLE_MAX_TRIES, self::THROTTLE_EXPIRES);
        if (is_wp_error($maybe_throttle)) {
            CgsmsSecurityTwoFactor::jsonFail($maybe_throttle);
        }

        // is the code correct?
        $user_code = preg_replace('/[^0-9]+/', '', $_POST['code']);
        $correct_code = $login_info['code'];
        if ($user_code != $correct_code) {
            CgsmsSecurityTwoFactor::jsonFail(new WP_Error('BAD_CODE', __('The code you have entered, is invalid. Please double check the SMS we sent you and try again.', 'cloudrebuesms')));
        }

        // SUCCESS!
        // save the phone number on the profile
        list($mcc, $mno) = get_transient('cgsms_2f_' . $tmp_token . '_phone');
        update_user_meta($user_ID, 'cgsms_mcc', $mcc);
        update_user_meta($user_ID, 'cgsms_mno', $mno);

        // log the user in
        wp_set_auth_cookie($user_ID, $login_info['remember']);

        // finally set a cookie for remembering the 2fa on the users current device
        CgsmsSecurityTwoFactor::createClientCookie($user_ID);

        // then show a message of the happy success
        $redirect_to = $login_info['redirect_to'];
        $html = self::getHtmlLoginConfirmedPhone($redirect_to);

        die(json_encode(['success' => true, 'html' => $html]));
    }

    private static function getHtmlLoginConfirmedPhone($redirect_to)
    {
        ob_start();
        include crsms_dir() . '/tpl/wp-login-confirmed-phone.php';
        return ob_get_clean();
    }
}


/**
 * Class for handling logins from users who does have a mobile number added to their account.
 */
class CgsmsSecurityTwoFactorHasMobile
{
    const THROTTLE_MAX_TRIES = 3;
    const THROTTLE_EXPIRES = 60 * 60;

    public static function handleLogin(\WP_User $user)
    {
        // send the sms
        $mcc = preg_replace('/[^0-9]+/', '', $user->cgsms_mcc);
        $mno = preg_replace('/[^0-9]+/', '', $user->cgsms_mno);
        $msisdn = $mcc . $mno;

        // our current login-replacement cookie
        $info = CgsmsSecurityTwoFactor::getLoginDataByTempToken(CgsmsSecurityTwoFactor::$TMP_TOKEN);

        // info for sms
        $code = $info['code'];
        $sender = get_bloginfo();
        $home_url = url_shorten(get_home_url());
        $message = strtr(__("Verification code: :code\nKind regards, :sender\n:home_url", 'cloudrebuesms'), [':code' => $code, ':sender' => $sender, ':home_url' => $home_url]);

        // send sms with code
        $status = crsms_send_sms($message, $msisdn);

        // handle errors when sending
        if (is_wp_error($status)) {
            $main_reason = '<h1>' . __('Problem in the two-factor security module', 'cloudrebuesms') . '</h1>';

            if ($status->get_error_code() == 'CGSMS_FAIL') {
                $reason = $status->get_error_message();
                list ($human, $tech) = explode("\n", $reason, 2);
                $reason = $human . "<br /><br /><small>" . nl2br($tech) . "</small>";


                wp_die(new WP_Error('cloudrebuesms', $main_reason . '<p>' . strtr(__('The SMS could not be sent, due to the SMS-service rejecting to send the message.<br><br>Technical reason:<br />- :reason', 'cloudrebuesms'), [':reason' => $reason]) . '</p>'));
            } else {
                wp_die(new WP_Error('cloudrebuesms', $main_reason . '<p>' . __('The SMS could not be sent due to a technical error/misconfiguration of the Cloud Rebue SMS-plugin.<br /><br />You could try again, but probably this can only be resolved by your administrator.', 'cloudrebuesms') . '</p>'));
            }
        }

        self::getHtmlLoginConfirmPhone($mcc, $mno);
    }

    private static function getHtmlLoginConfirmPhone($mcc, $mno)
    {
        // anonymize the phone number
        $mno = str_repeat('·', strlen($mno) - 3) . substr($mno, -3, 3);
        $tmp_token = CgsmsSecurityTwoFactor::$TMP_TOKEN;

        // enqueue css/js
        CgsmsSecurityTwoFactor::enqueueCssJs();

        // tweak the form id, so the JS hooks in properly
        add_filter('cgsms_confirm_phone_form_id', function ($c) {
            return "cgsms_confirm_login_form";
        });

        // output!
        login_header(__('Two-factor security check', 'cloudrebuesms'));
        echo '<div class="step current">';
        include crsms_dir() . '/tpl/wp-login-confirm-phone.php';
        echo '</div>';
        login_footer();
    }

    /**
     * AJAX request for confirming a two-factor sms code and if successful, setting the auth cookie, set the persistence
     * cookie for 2fa and log the user in (by sending a URL to redirect to).
     */
    public static function confirmSms()
    {
        header("Content-type: application/json");

        // token
        $tmp_token = trim($_POST['cgsms_2f_tmp']);

        // validate the token
        $login_info = CgsmsSecurityTwoFactor::getLoginDataByTempToken($tmp_token);
        if (is_wp_error($login_info)) CgsmsSecurityTwoFactor::jsonFail($login_info);

        // save this attempt on the user
        $user_ID = $login_info['user'];
        add_filter('cgsms_throttle_error', function ($e) {
            return __('You have tried to enter the confirmation token too many times. Please start over to try again.', 'cloudrebuesms');
        });
        $maybe_throttle = CgsmsSecurityTwoFactor::failOnThrottle($user_ID, 'confirmlogin_' . $user_ID, self::THROTTLE_MAX_TRIES, self::THROTTLE_EXPIRES);
        if (is_wp_error($maybe_throttle)) {
            CgsmsSecurityTwoFactor::jsonFail($maybe_throttle);
        }

        // is the code correct?
        $user_code = preg_replace('/[^0-9]+/', '', $_POST['code']);
        $correct_code = $login_info['code'];
        if ($user_code != $correct_code) {
            CgsmsSecurityTwoFactor::jsonFail(new WP_Error('BAD_CODE', __('The code you have entered, is invalid. Please double check the SMS we sent you and try again.', 'cloudrebuesms')));
        }

        // SUCCESS!
        // log the user in
        wp_set_auth_cookie($user_ID, $login_info['remember']);

        // finally set a cookie for remembering the 2fa on the users current device
        CgsmsSecurityTwoFactor::createClientCookie($user_ID);

        // redirect the user to whereever he was intending to go after the login
        $redirect_to = $login_info['redirect_to'];

        die(json_encode(['success' => true, 'redirect_to' => $redirect_to]));
    }
}

/**
 * Add a two-factor mobile number field to the users profile page and make it possible to add/update the phone number
 * with a verification SMS as part of the flow.
 */
class CgsmsSecurityTwoFactorUserProfile
{
    public static function addContactMethod($methods, $user = null)
    {
        if (!is_a($user, 'WP_User')) {
            if (is_object($user) && isset($user->ID) && is_int($user->ID)) $user = get_user_by('id', $user->ID);
            else return $methods; // unsupported on brand new users
        }
        if (!CgsmsSecurityTwoFactor::userNeedsTwoFactor($user)) return $methods;

        // only enable this for when editing own profile
        if ($user->ID !== wp_get_current_user()->ID) return $methods;

        $methods['cgsms_msisdn'] = __('Mobile number', 'cloudrebuesms');

        return $methods;
    }

    public static function enqueueJsCss()
    {
        $screen = get_current_screen();
        if ($screen->base != 'profile') return;

        wp_register_script('wpadmin-profile-two_factor', crsms_url() . '/js/wpadmin-profile-two_factor.js', ['jquery']);

        $nonce = wp_create_nonce('cgsms_profile_change_phone');
        $user = wp_get_current_user();

        $i18n = [
            'twofac_section_h1' => __('Two-factor security', 'cloudrebuesms'),
            'twofac_section_intro' => __('For security reasons, it is mandatory to associate your account with a cellphone number. This greatly enhances the integrity of your account and this site in general.', 'cloudrebuesms'),
            'twofac_add_number' => __('Add mobile number', 'cloudrebuesms'),
            'twofac_update_number' => __('Change mobile number', 'cloudrebuesms'),
            'twofac_link' => admin_url('admin-post.php?action=cgsms_profile_change_phone&_nonce='.$nonce),
            'twofac_msisdn' => $user->cgsms_mno ? '+'.$user->cgsms_mcc.' '.$user->cgsms_mno : '',
        ];

        wp_localize_script('wpadmin-profile-two_factor', 'CGSMS_PROFILE_I18N', $i18n);
        wp_enqueue_script('wpadmin-profile-two_factor');
    }

    public static function gotoChangeMobile()
    {
        // verify nonce
        if (!wp_verify_nonce($_GET['_nonce'], 'cgsms_profile_change_phone')) {
            wp_die(__('You have followed a link containing an expired/invalid nonce. Please go back and redirect the page - then it should work.', 'cloudrebuesms'));
        }

        $user = wp_get_current_user();

        // create the temp cookie
        $_POST['redirect_to'] = $_SERVER['HTTP_REFERER'];
        $temp_token = CgsmsSecurityTwoFactor::replaceLoginCookieWithTempCookie($user);

        // remove mobile number from users profile
        delete_user_meta($user->ID, 'cgsms_mcc');
        delete_user_meta($user->ID, 'cgsms_mno');

        // also clear the current tokens on the user
        delete_user_meta($user->ID, 'cgsms_2f_tokens');

        // redirect to login page with this cookie
        wp_redirect(wp_login_url().'?action=gwb2fa_reset&cgsms_reset_token='.$temp_token);
    }

    public static function loginAddNewMobile()
    {
        // validate the reset token
        $token = $_GET['cgsms_reset_token'];
        $login_info = CgsmsSecurityTwoFactor::getLoginDataByTempToken($token);
        if (is_wp_error($login_info)) wp_die($login_info);

        // show the form
        $user = get_user_by('ID', $login_info['user']);
        CgsmsSecurityTwoFactor::$TMP_TOKEN = $token;
        CgsmsSecurityTwoFactor::renderTwoFactorSteps($user);

        die();
    }
}

// login flow
add_action('wp_login', ['CgsmsSecurityTwoFactor', 'handleLoginHook'], 10, 2);
add_action('wp_ajax_nopriv_cgsms_security_add_phone', ['CgsmsSecurityTwoFactorAddMobile', 'sendInitialSms']);
add_action('wp_ajax_nopriv_cgsms_security_confirm_phone', ['CgsmsSecurityTwoFactorAddMobile', 'confirmSms']);
add_action('wp_ajax_nopriv_cgsms_security_confirm_login', ['CgsmsSecurityTwoFactorHasMobile', 'confirmSms']);

// bypass mode
add_action('login_form_gwb2fa', ['CgsmsSecurityTwoFactor', 'enableBypassMode']);

// admin user profile
add_filter('user_contactmethods', ['CgsmsSecurityTwoFactorUserProfile', 'addContactMethod'], 10, 2);
add_filter('admin_enqueue_scripts', ['CgsmsSecurityTwoFactorUserProfile', 'enqueueJsCss']);
add_action('admin_post_cgsms_profile_change_phone', ['CgsmsSecurityTwoFactorUserProfile', 'gotoChangeMobile']);
add_action('login_form_gwb2fa_reset', ['CgsmsSecurityTwoFactorUserProfile', 'loginAddNewMobile']);
