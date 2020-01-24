<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/api-authentication-REST/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    // files needed to connect
    include_once 'config/database.php';
    include_once 'config/core.php';
    include_once 'objects/user.php';
    include_once 'objects/Response.php';
    include_once '../libs/vendor/autoload.php';
    use \Firebase\JWT\JWT;

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // instantiate user object
    $user = new User($db);

    // instantiate response
    $response = new Response();

    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // set email and check email exists or not
    $user->email = $data->email;
    $email_exists = $user->emailExists();
    
    // check email exists and password is correct
    if($email_exists && password_verify($data->password, $user->password)) {
        // generate access jwt
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
        
        // generate refresh jwt
        $refresh_payload = array(
            "iss" => $refresh_iss,
            "aud" => $refresh_aud,
            "iat" => $refresh_iat,
            "exp" => $refresh_exp,
            "data" => array(
                "id" => $user->id
            )
        );
        $refresh_token = JWT::encode($refresh_payload, $refresh_key);
        $user->refresh_token = $refresh_token;
        // save refresh token into databse for login user
        $user->saveRefreshToken();

        // reply response
        $response->result(200, "Successful login.", [$access_token, $refresh_token]);
    } else {
        // login failed
        $response->result(401, "Login failed.", null);
    }

?>