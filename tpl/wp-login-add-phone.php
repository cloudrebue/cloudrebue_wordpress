<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>
<?php login_header(__('Register mobile phone number', 'cloudrebuesms')); ?>

    <div class="step current">

        <p class="message">
            <?php _e('Please add your cellphone number below, so we can send you a one-time verification code.<br />This is mandatory, but free of charge to you.', 'cloudrebuesms'); ?>
            <br/>
        </p>

        <form method="post" id="cgsms_add_phone_form">

            <input type="hidden" name="cgsms_2f_tmp" value="<?= esc_attr(CgsmsSecurityTwoFactor::$TMP_TOKEN); ?>">

            <p>
                <label for="mcc"><?php _e('Mobile country code', 'cloudrebuesms'); ?></label><br>
                <select name="mcc" id="cgsms_mcc" data-crsms-mobile-cc data-default-cc="254" style="width: 100%"
                        class="input"></select>
            </p>

            <p>
                <label for="cgsms_mno"><?php _e('Mobile number', 'cloudrebuesms'); ?><br>
                    <input type="number" name="mno" id="cgsms_mno" placeholder="708361797" class="input" value="" size="20">
                </label>
            </p>

            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large"
                       value="<?php esc_attr_e('Verify phone', 'cloudrebuesms'); ?>" data-loading="<?= esc_attr_e('Sending SMS...', 'cloudrebuesms'); ?>">
            </p>
        </form>
    </div>


<?php login_footer(); ?>