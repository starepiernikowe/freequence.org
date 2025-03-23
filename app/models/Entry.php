<?php

class Entry {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function createEntry($data) {
        $stmt = $this->db->prepare("
            INSERT INTO entries (user_id, template_id, entry_name, creation_date, expiration_date,
                                 location_lat, location_lng, comment, description, entry_type, status, parent_entry_id, is_active)
            VALUES (:user_id, :template_id, :entry_name, :creation_date, :expiration_date,
                    :location_lat, :location_lng, :comment, :description, :entry_type, :status, :parent_entry_id, :is_active)
        ");

        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':template_id', $data['template_id'], PDO::PARAM_INT);
        $stmt->bindValue(':entry_name', $data['entry_name'], PDO::PARAM_STR);
        $stmt->bindValue(':creation_date', $data['creation_date'], PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $data['expiration_date'], PDO::PARAM_STR);
        $stmt->bindValue(':location_lat', $data['location_lat']);
        $stmt->bindValue(':location_lng', $data['location_lng']);
        $stmt->bindValue(':comment', $data['comment'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':entry_type', $data['entry_type'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindValue(':parent_entry_id', $data['parent_entry_id'], PDO::PARAM_INT);
        $stmt->bindValue(':is_active', $data['is_active'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function getEntriesByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM entries WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}