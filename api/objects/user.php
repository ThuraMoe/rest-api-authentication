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
        $stmt->bindParam(':password', $pwd_hash);

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

    // update a user record
    public function update() {
        // if password needs to be update
        $password_set = !empty($this->password) ? " , password=:password " : "";
        
        // if no posted password, do not update the password
        $query = "UPDATE ". $this->table_name ." SET firstname=:firstname, lastname=:lastname, email=:email {$password_set} WHERE id=:id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before saving into database
        if(!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $this->password);
        }

        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);

        // excute the query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // check email already exists
    public function checkEmailExists() {
        $query = "SELECT * FROM ".$this->table_name." WHERE email=:email";
        $stmt = $this->conn->prepare($query);

        // sanitize email
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind values
        $stmt->bindParam(':email', $this->email);

        // execute query
        $stmt->execute();

        // get row count
        $num = $stmt->rowCount();

        if($num > 0) {
            return true;
        } else {
            return false;
        }

    }


}
?>