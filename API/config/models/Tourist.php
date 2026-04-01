<?php

class Tourist {

    private $conn;
    private $table = "tourists";

    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $phone_number;

    public function __construct($db){
        $this->conn = $db;
    }

    // SIGN UP
    public function register(){

        $query = "INSERT INTO ".$this->table."
        (first_name,last_name,email,password_hash,phone_number,otp,is_verified)
        VALUES
        (:first_name,:last_name,:email,:password_hash,:phone_number,:otp,0)";

        $stmt = $this->conn->prepare($query);

        $hashed = password_hash($this->password, PASSWORD_BCRYPT);

        $otp = rand(100000,999999);

        $stmt->bindParam(":first_name",$this->first_name);
        $stmt->bindParam(":last_name",$this->last_name);
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":password_hash",$hashed);
        $stmt->bindParam(":phone_number",$this->phone_number);
        $stmt->bindParam(":otp",$otp);

        return $stmt->execute();
    }

    // LOGIN
    public function login(){

        $query = "SELECT * FROM ".$this->table."
        WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email",$this->email);

        $stmt->execute();

        return $stmt;
    }
}
