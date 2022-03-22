<?php if (!defined('ABSPATH')) die('Cannot be accessed directly!'); ?>

<p class="message">
    <?php echo __('Success! Your mobile is now connected to your profile, which helps secure the integrity of your account and this website.', 'cloudrebuesms'); ?>
    <br/><br/>
    <strong><?php echo __('You have also been logged in 😄', 'cloudrebuesms'); ?></strong>
    <br><br>

    <span class="submit">
        <a href="<?= $redirect_to ?>" class="button button-primary button-large"><?php _e('Continue', 'cloudrebuesms'); ?></a>
    </span>
    <br>
    <br>
</p>

