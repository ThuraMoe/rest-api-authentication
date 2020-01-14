<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // connect files
    include_once 'config/database.php';
    include_once 'config/core.php';
    include_once 'objects/user.php';
    include_once 'objects/Response.php';
    include_once '../libs/vendor/autoload.php';
    use \Firebase\JWT\JWT;

    // create database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate response object
    $response = new Response();

    // instantiate user object
    $user = new User($db);

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    $jwt = isset($data->jwt)? $data->jwt : "";

    if($jwt) {
        try {
            // if decode succeed, show user details
            $decode = JWT::decode($jwt, $key, array('HS256'));

            // set user property values
            $user->firstname = $data->firstname;
            $user->lastname = $data->lastname;
            $user->email = $data->email;
            $user->password = $data->password;
            $user->id = $decode->data->id;

            // update the user record
            if($user->update()) {
                // regenerate jwt will be here
                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "data" => array(
                        "id" => $user->id,
                        "firstname" => $user->firstname,
                        "lastname" => $user->lastname,
                        "email" => $user->email
                    )
                );
                $jwt = JWT::encode($token, $key);
                $response->result(200, "User was updated.", $jwt);
            } else {
                // update failed
                $response->result(401, "Unable to update user.", null);
            }
        } catch (Exception $e) {
            // fail to decode
            $response->result(401, "Decode failed.".$e->getMessage(), null);
        }
    } else {
        // if empty jwt, can't access
        $response->result(401, "Access denined.", null);
    }

