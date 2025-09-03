<?php
require_once "db.php";

class User {
    protected $conn;
    protected $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $email, $password, $role) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name,email,password,role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hash, $role]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>