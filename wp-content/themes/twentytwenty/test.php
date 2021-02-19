<?php

/**
 * Test
 */

add_shortcode('test_test','test_test');
function test_test() {
    global $wpdb;
    
    if($wpdb->last_error !== '') :
        $wpdb->print_error();
    endif;


}

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

function bsft_decrypt($encrypted_string) {

    $keyHex = '9cc25c7879fc94d5a19eeb8e47573b8423becb608a9a4e9d3c25c20aa7e04357';

    $key = hex2bin($keyHex);
    $ciphertext = hex2bin($encrypted_string);

    return openssl_decrypt($ciphertext, 'aes-256-ecb', $key, true);
}

