<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<p class="message">
    <?php echo strtr(
        __('We have just sent you a SMS, to the number <strong>+:mcc :mno</strong>. Please enter the confirmation code from it, in the field below.', 'cloudrebuesms'),
        [':mcc' => $mcc, ':mno' => $mno]
    ); ?>
    <br/>
</p>

<form method="post" id="<?= apply_filters('cgsms_confirm_phone_form_id', 'cgsms_confirm_phone_form'); ?>">

    <input type="hidden" name="cgsms_2f_tmp" value="<?= esc_attr($tmp_token); ?>" />

    <p>
        <label for="cgsms_confirm_code"><?php _e('Confirmation code', 'cloudrebuesms'); ?><br>
            <input type="number" name="code" id="cgsms_confirm_code" class="input" value="" size="20">
        </label>
    </p>

    <p class="submit">
        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large"
               value="<?php esc_attr_e('Confirm code', 'cloudrebuesms'); ?>"
               data-loading="<?= esc_attr_e('Confirming...', 'cloudrebuesms'); ?>">
    </p>
</form>