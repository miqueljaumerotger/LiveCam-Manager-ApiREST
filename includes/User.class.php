<?php
require_once('Database.class.php');

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $role_id;
    public $active; // 🆕 Afegeix el camp

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create_user() {
        $query = "INSERT INTO " . $this->table . " (username, email, password_hash, role_id)
                  VALUES (:username, :email, :password_hash, :role_id)";
        
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);

        if (!$this->role_id) {
            $this->role_id = 2;
        }

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);
        $stmt->bindParam(":role_id", $this->role_id);

        return $stmt->execute();
    }

    public function login($password_input) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->role_id = $row['role_id'];
            $this->email = $row['email'];
            $this->active = (int)$row['active']; // 🆕 assignació del valor de la BBDD

            if (password_verify($password_input, $row['password_hash'])) {
                return true;
            }
        }
        return false;
    }
}
?>
