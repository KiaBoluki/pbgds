
<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once('../../../wp-load.php');


defined('ABSPATH') or exit('you can\'t access this page.');

$nonce = $_POST['kia_new_user_nonce'];

if (!isset($nonce) || !wp_verify_nonce($nonce, 'kia_new_user_nonce_action'))
    exit('nonce is not valid');

$username = sanitize_text_field($_POST['username']);
$password = sanitize_text_field($_POST['password']);
//$conf_password = sanitize_text_field($_POST['conf-password']);
$lessons_number = intval($_POST['lessons_number']);
$phone = sanitize_text_field($_POST['phone']);
$desc = sanitize_text_field($_POST['desc']);

validate($username, $password, $phone, $lessons_number, $conf_password, $desc);
insert($username, $password, $phone, $lessons_number, $conf_password, $desc);


function addError($msg,$status = '0')
{
    ob_end_clean();
    /* array_push($GLOBALS['kia_errors'], $msg); */
    echo json_encode(
        [
            'status'   => $status,
            'msg'      => [$msg]
        ],
    );
    exit();
}

function validate($username, $password, $phone, $lessons_number)
{
    if (!isset($username) || empty($username)) {
        addError('username is required.');
    }
    if (strlen($username) > 30 || strlen($username) < 3) {
        addError('username must be between 3-30 characters.');
    }
    if (!isset($password) || empty($password)) {
        addError('password is required.');
    }
    if (strlen($password) > 30 || strlen($password) < 8) {
        addError('password must be between 8-30 characters.');
    }

    if (strlen($phone) > 11 || strlen($phone) < 8) {
        addError('phone must be between 8-11 characters.');
    }
    if (!isset($phone) || empty($phone)) {
        addError('phone is required.');
    }
    if ( $lessons_number > 45 || $lessons_number < 1)
        addError('Lesson number is invalid. between 1-45 is accepted.');
}

function insert($username, $password, $phone, $desc, $lessons_number)
{
    $userdata = array(
        'user_login' =>  $username,
        'user_url'   =>  NULL,
        'user_pass'  =>  $password, // When creating an user, `user_pass` is expected.
        'description' =>  $desc,
    );

    $user_id = wp_insert_user($userdata);

    // On success.
    if (!is_wp_error($user_id)) {
        add_user_meta($user_id, 'kia_phone', $phone);
        add_user_meta($user_id, 'kia_lessons_number', $lessons_number);
        addError("{$username} registered. <br/> Password: {$password}",'1');
            
    } else {
       addError( $user_id->get_error_message());
    }
}


?>