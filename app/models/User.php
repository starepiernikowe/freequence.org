<?php

class User {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function registerUser($username, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, registration_date) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$username, $email, $password]);
    }

    public function findUserByUsernameOrEmail($username, $email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}