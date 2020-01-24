<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once 'config/core.php';
    include_once 'objects/Response.php';
    include_once '../libs/vendor/autoload.php';
    use \Firebase\JWT\JWT;

    // instantiate response object
    $response = new Response();

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // get jwt
    $access_token = isset($data->jwt)? $data->jwt : "";

    if($access_token) {
        // if decode successed show user details
        try {
            // decode jwt
            $decode_jwt = JWT::decode($access_token, $access_key, array('HS256'));

            // reply response
            $response->result(200, "Access granted.", $decode_jwt);
            
        } catch (\Firebase\JWT\ExpiredException $e) {
            // if decode fails, it means jwt is invalid
            $response->result(408, "Token Expire!", $e->getMessage());
        }
    } else {
        // if jwt is empty
        $response->result(401, "Access denined.", null);
    }