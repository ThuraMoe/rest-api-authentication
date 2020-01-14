<?php
    // show error reporting
    error_reporting(E_ALL);

    // set your default time-zone
    date_default_timezone_set('Asia/Yangon');

    // variables used for jwt
    $key = "#asdfkjhgw!~332kdjuksd0032-kfnhyehfenfjljdfuf!@#*(;kjihifg42";
    $iss = "http://localhost/api-authentication-REST/";
    $aud = "http://localhost/api-authentication-REST/";
    $iat = time();
    $nbf = $iat + 10; // not before in seconds
    $exp = $iat + 60; // expire time in seconds

?>