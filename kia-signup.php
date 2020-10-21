<?php

/*
    Plugin Name:    Kia Signup
    Author:         Kia Boluki
    Author URI:     http://kiaboluki.com
    Description:    Add custom field to signup form
    Version:        1.0.0

*/

/**
 * Front end registration
 */

add_action('register_form', 'crf_registration_form');
function crf_registration_form()
{

    $year = !empty($_POST['lesson_number']) ? intval($_POST['lesson_number']) : '';

?>
    <p>
        <label for="lesson_number"><?php esc_html_e('تعداد مجاز درس:', 'crf') ?><br />
            <input type="number" min="1" max="40" step="1" id="lesson_number" name="lesson_number" value="<?php echo esc_attr($lessons_number); ?>" class="input" />
        </label>
    </p>
<?php
}


add_filter('registration_errors', 'crf_registration_errors', 10, 3);
function crf_registration_errors($errors, $sanitized_user_login, $user_email)
{

    if (empty($_POST['lesson_number'])) {
        $errors->add('lesson_number_error', __('<strong>ERROR</strong>: تعداد درسهای مجاز برای دانلود را مشخص کنید', 'crf'));
    }

    if (!empty($_POST['lesson_number']) && intval($_POST['lesson_number']) < 1) {
        $errors->add('lesson_number_error', __('<strong>ERROR</strong>: عدد وارد شده نمیتواند کمتر از یک باشد', 'crf'));
    }

    return $errors;
}

add_action('user_register', 'crf_user_register');
function crf_user_register($user_id)
{
    if (!empty($_POST['lesson_number'])) {
        update_user_meta($user_id, 'lesson_number', intval($_POST['lesson_number']));
    }
}


/**
 * Back end registration
 */

add_action('user_new_form', 'crf_admin_registration_form');
function crf_admin_registration_form($operation)
{
    if ('add-new-user' !== $operation) {
        // $operation may also be 'add-existing-user'
        return;
    }

    $year = !empty($_POST['lesson_number']) ? intval($_POST['lesson_number']) : '';

?>
    <h3><?php esc_html_e('مشخصات پرداخت', 'crf'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="lesson_number"><?php esc_html_e('تعداد درس های مجاز', 'crf'); ?></label> <span class="description"><?php esc_html_e('(لازم)', 'crf'); ?></span></th>
            <td>
                <input type="number" min="1" max="45" step="1" id="lesson_number" name="lesson_number" value="<?php echo esc_attr($lessons_number) ?? 1; ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="lesson_number"><?php esc_html_e('مبلغ پرداخت شده', 'crf'); ?></label> <span class="description"><?php esc_html_e('(لازم)', 'crf'); ?></span></th>
            <td>
                <input type="number" min="0"  step="10000" id="paid_amount" name="paid_amount" value="<?php echo esc_attr($paid_amount) ?? 0; ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}


add_action('user_profile_update_errors', 'crf_user_profile_update_errors', 10, 3);
function crf_user_profile_update_errors($errors, $update, $user)
{
    if ($update) {
        return;
    }

    if (empty($_POST['lesson_number'])) {
        $errors->add('lesson_number_error', __('<strong>ERROR</strong>: تعداد درسهای مجاز را مشخص نمایید', 'crf'));
    }

    if (!empty($_POST['lesson_number']) && intval($_POST['lesson_number']) < 1) {
        $errors->add('lesson_number_error', __('<strong>ERROR</strong>: تعداد درسهای وارد شده معتبر نیست', 'crf'));
    }
    if (!empty($_POST['lesson_number']) && intval($_POST['lesson_number']) > 45) {
        $errors->add('lesson_number_error', __('<strong>ERROR</strong>: تعداد درسهای وارد شده معتبر نیست', 'crf'));
    }
}

add_action('edit_user_created_user', 'crf_user_register');


add_action('show_user_profile', 'crf_show_extra_profile_fields');
add_action('edit_user_profile', 'crf_show_extra_profile_fields');

function crf_show_extra_profile_fields($user)
{
?>
    <table class="form-table">
        <tr>
            <th><label for="lesson_number"><?php esc_html_e('تعداد درس:', 'crf'); ?></label></th>
            <td><?php echo esc_html(get_the_author_meta('lesson_number', $user->ID)); ?></td>
        </tr>
    </table>
<?php
}

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

add_action( 'user_register', 'kia_add_lessons_to_cart', 10, 1 );

function kia_add_lessons_to_cart( $user_id ) {
    
    
    return ;//;
    

}

/* function admin_default_page()
{
    return '/tutorials';
}

add_filter('login_redirect', 'admin_default_page'); */


/*

Create Registration form 
@param 
@retrun string

*/

add_shortcode( 'kia_signup_form', 'kia_create_registration_form' );

function kia_create_registration_form ()
{
    if ( !is_user_logged_in() )
    {
        return 'hello guest';
    }

    if ( !current_user_can('administrator'))
    {
        return 'hello member';
    }

    return plugin_url();
    ob_start();
    get_template_part( plugin_url( 'my_form_template.php' ));
    return ob_get_clean();  

    /* return get_template_part(plugin_url('my_form_template.php')); */
    
}

?>