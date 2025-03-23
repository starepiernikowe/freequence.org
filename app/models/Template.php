<?php

class Template {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }   catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    public function getAllTemplates() {
         $stmt = $this->db->prepare("SELECT * FROM templates WHERE is_active = 1"); // Only get active
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTemplateById($id){
         $stmt = $this->db->prepare("SELECT * FROM templates WHERE id = :id AND is_active = 1");
         $stmt->bindValue(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getParametersByTemplateId($templateId) {
        $stmt = $this->db->prepare("SELECT * FROM parameters WHERE template_id = ? ORDER BY sort_order");
        $stmt->execute([$templateId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getParameterById($parameterId) {
        $stmt = $this->db->prepare("SELECT * FROM parameters WHERE id = ?");
        $stmt->execute([$parameterId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}