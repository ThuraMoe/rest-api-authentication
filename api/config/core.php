<?php
    // show error reporting
    error_reporting(E_ALL);

    // set your default time-zone
    date_default_timezone_set('Asia/Yangon');

    // variables used for jwt
    $key = "#asdfkjhgw!~332kdjuksd0032-kfnhyehfenfjljdfuf!@#*(;kjihifg42";
    $iss = "http://localhost/api-authentication-REST/";
    $aud = "http://localhost/api-authentication-REST/";
    $iat = 1578910513;
    $nbf = 1578910595;

?>