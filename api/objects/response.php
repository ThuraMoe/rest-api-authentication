<?php
// response object 
class Response {

    // response result
    public function result($status, $message, $jwt) {
        echo json_encode(array(
            'status' => $status, 
            'message' => $message, 
            'jwt' => $jwt
        ));
    }
}
?>