<?php

/*
    Plugin Name:    Kia Signup
    Author:         Kia Boluki
    Author URI:     http://kiaboluki.com
    Description:    Add custom field to signup form
    Version:        1.0.0

*/

defined('ABSPATH') or exit();
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/*
enqueue bootstrap 
*/
add_action('wp_enqueue_scripts', 'kia_enqueue_scripts', 999);
add_action('wp_head', 'kia_enqueue_head', 999);
function kia_enqueue_head()
{
    ?>
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store">
    <meta http-equiv="Cache-Control" content="max-age=1">
    <?php
}
function kia_enqueue_scripts()
{
    wp_enqueue_style('kia_bs4_css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css');
    wp_enqueue_script('kia_bs4_js', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js', ['jquery']);
    wp_enqueue_script('kia_insert_user_js', plugins_url('kia-signup/kia-insert.js') , ['jquery']);
}
add_shortcode('kia_signup_form', 'kia_new_registration_form');
function kia_new_registration_form()
{

    if (!is_user_logged_in())
        wp_safe_redirect(home_url('login'));

    if (!current_user_can('administrator'))
        wp_safe_redirect(home_url());

?>

    <form id="kia-insert-new-user-form" class="" action="<?php echo plugins_url('kia-insert-new-user.php?v='.rand(), __FILE__); ?>" method="post">
    <?php wp_nonce_field('kia_new_user_nonce_action','kia_new_user_nonce'); ?>
        <fieldset style="direction: rtl !important; font-family:Iran;">
            <legend>ثبت نام هنرآموز</legend>
            <div class="form-group">
                <input type="text" class="form-control" name="username" id="username" placeholder="نام کاربری">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="password" id="password" placeholder="پسورد">
            </div>
            <!-- <div class="form-group">
                <input type="password" class="form-control" name="conf-password" id="conf-password" placeholder="تکرار پسورد">
            </div> -->
            <div class="form-group">
                <input type="phone" class="form-control" name="phone" id="phone" placeholder="شماره موبایل">
            </div>
            <div class="form-group">
                <input type="desc" class="form-control" name="desc" id="desc" placeholder="توضیحات">
            </div>
            <div class="form-group row">
                <div class="col">
                    <label class="input-label" for="lessons_number">تعداد درس :</label>
                </div>
                    <div class="col">
                        <input type="number" class="form-control" value="1" min="1" max="45" name="lessons_number" id="lessons_number">
                    </div>
                
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-primary" value="ثبت نام">
            </div>
        </fieldset>
    </form>

    <div id="kia-response-container">
        <p id="kia-response"></p>
    </div>
<?php


}

?>