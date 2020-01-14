<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/api-authentication-REST/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // database connection will be here
    include_once('config/database.php');
    include_once('objects/user.php');
    include_once('objects/response.php');

    //get db connection
    $database = new Database();
    $db = $database->getConnection();

    //get posted data
    $data = json_decode(file_get_contents("php://input"));

    //instantiate user object
    $user = new User($db);

    //set user property values
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->email = $data->email;
    $user->password = $data->password;

    // instantiate response
    $response = new Response();

    //create user
    if(!empty($user->firstname) && !empty($user->email) && !empty($user->password)) {
        // response
        $response->result(200, "User was created", null);
    } else {
        // response
        $response->result(400, "Unable to create user", null);
    }

?>