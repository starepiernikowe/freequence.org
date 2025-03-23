<?php

class EntryDetail {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function createEntryDetail($data) {
        // Prepare common fields
        $fields = [
            'entry_id' => [PDO::PARAM_INT, $data['entry_id']],
            'value' => [PDO::PARAM_STR, $data['value']],
        ];

        // Add specific fields based on whether parameter_id exists
        if (isset($data['parameter_id'])) {
            $fields['parameter_id'] = [PDO::PARAM_INT, $data['parameter_id']];
            $fields['is_option'] = [PDO::PARAM_INT, $data['is_option'] ?? null]; // Default to NULL if not provided

             $sql = "INSERT INTO entry_details (entry_id, parameter_id, value, is_option) VALUES (:entry_id, :parameter_id, :value, :is_option)";
        } else if (isset($data['custom_parameter_name'])) {

            $fields['custom_parameter_name'] = [PDO::PARAM_STR, $data['custom_parameter_name']];
            $fields['is_option'] = [PDO::PARAM_INT, $data['is_option'] ?? null];

            $sql = "INSERT INTO entry_details (entry_id, custom_parameter_name, value, is_option) VALUES (:entry_id, :custom_parameter_name, :value, :is_option)";

        } else {
            // Handle error - neither parameter_id nor custom_parameter_name provided.
            error_log("Neither parameter_id nor custom_parameter_name provided for entry detail.");
            return false;
        }

        $stmt = $this->db->prepare($sql);

        // Bind parameters
        foreach ($fields as $key => $field) {
            $stmt->bindValue(":$key", $field[1], $field[0]);
        }

        return $stmt->execute();
    }
}