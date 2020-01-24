<?php
    // show error reporting
    error_reporting(E_ALL);

    // set your default time-zone
    date_default_timezone_set('Asia/Yangon');

    // variables used for access_token jwt
    $access_key = "#asdfkjhgw!~332kdjuks$%^&*$;Ed0032-kfnhyehfenfjljdfuf!@#*(;kjihifg42";
    $access_iss = "http://localhost/api-authentication-REST/";
    $access_aud = "http://localhost/api-authentication-REST/";
    $access_iat = time();
    $access_nbf = time(); // not before in seconds
    $access_exp = $access_iat + 120; // expire time in seconds

    // variables used for refresh_token jwt
    $refresh_key = "89ushaos8f4jt#7asd76*23jSD()&^^{}=*YhjsdfikASDfjkalsdjfakskhdfhaF@#@#lrjkfsdjfskddj";
    $refresh_iss = "http://localhost/api-authentication-REST/";
    $refresh_aud = "http://localhost/api-authentication-REST/";
    $refresh_iat = time();
    $refresh_exp = $refresh_iat + 1209600; // expire time in seconds

?>