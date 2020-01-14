<?php
// user object
class User {
    //db connection and table name;
    private $conn;
    private $table_name = "users";

    //object properties;
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // insert user into db
    public function create() {
        // insert query
        $query = "INSERT INTO ".$this->table_name. " SET firstname=:firstname, lastname=:lastname, email=:email, password=:password";

        // prepare query
        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before save
        $pwd_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':email', $pwd_hash);

        //execute query
        if($stmt->execute()) {
            return true;
        }
        return false;

    }

    // check user email exists in db
    public function emailExists() {
        // query to check email
        $query = "SELECT id, firstname, lastname, password FROM ".$this->table_name." WHERE email=? ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign value to access
        if($num > 0) {
            // get rcord details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign value to object property
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
        
            // return true because email exists in the database
            return true;
        }

        // return false if email does not exists in the database
        return false;
    }
}
?>