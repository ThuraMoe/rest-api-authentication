<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once 'config/core.php';
    include_once 'config/database.php';
    include_once 'objects/user.php';
    include_once 'objects/Response.php';
    include_once '../libs/vendor/autoload.php';
    use \Firebase\JWT\JWT;

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate user object
    $user = new User($db);

    // instantiate response object
    $response = new Response();

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    // get refresh token
    $jwt = isset($data->refresh_token)? $data->refresh_token : "";

    // check in db
    if($jwt) {
        // if decode successed show user details
        try {
            // decode jwt
            $decode_jwt = JWT::decode($jwt, $refresh_key, array('HS256'));
            $user->id = $decode_jwt->data->id;
            $user->refresh_token = $jwt;

            // check refresh token is valid and get user info
            $is_valid = $user->checkRefreshToken();

            if($is_valid) {
                // generate access token
                $access_payload = array(
                    "iss" => $access_iss,
                    "aud" => $access_aud,
                    "iat" => $access_iat,
                    "nbf" => $access_nbf,
                    "exp" => $access_exp,
                    "data" => array(
                        "id" => $user->id,
                        "firstname" => $user->firstname,
                        "lastname" => $user->lastname,
                        "email" => $user->email
                    )
                );
                $access_token = JWT::encode($access_payload, $access_key);

                // reply response
                $response->result(200, "Access granted.", $access_token);
            } else {
                // reply response
                $response->result(401, "Access Denined.", null);
            }
        } catch (Exception $e) {
            // if decode fails, it means jwt is invalid
            $response->result(401, "Access denined.", $e->getMessage());
        }
    } else {
        // if jwt is empty
        $response->result(401, "Access denined.", null);
    }