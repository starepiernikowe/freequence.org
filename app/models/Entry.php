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
        $stmt = $this->db->prepare("SELECT * FROM entries WHERE user_id = ? AND is_active = 1"); // Added is_active check
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getEntryById($entryId) {
        $stmt = $this->db->prepare("
            SELECT
                e.*,
                t.name AS template_name,
                t.user_id AS template_user_id,
                p.name AS parameter_name,
                p.data_type AS parameter_data_type,
                p.options AS parameter_options,
                ed.value AS parameter_value,
                ed.parameter_id AS parameter_id,
                ed.custom_parameter_name AS custom_parameter_name
            FROM
                entries AS e
            INNER JOIN
                templates AS t ON e.template_id = t.id
            LEFT JOIN
                entry_details AS ed ON e.id = ed.entry_id
            LEFT JOIN
                parameters AS p ON ed.parameter_id = p.id
            WHERE
                e.id = ? AND e.is_active = 1
        ");
        $stmt->execute([$entryId]);

        $entryData = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // First row: initialize entry data
            if (empty($entryData)) {
                $entryData = [
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'template_id' => $row['template_id'],
                    'template_name' => $row['template_name'],
                    'entry_name' => $row['entry_name'],
                    'creation_date' => $row['creation_date'],
                    'expiration_date' => $row['expiration_date'],
                    'location_lat' => $row['location_lat'],
                    'location_lng' => $row['location_lng'],
                    'comment' => $row['comment'],
                    'description' => $row['description'],
                    'entry_type' => $row['entry_type'],
                    'status' => $row['status'],
                    'parent_entry_id' => $row['parent_entry_id'],
                    'parameters' => [], // Initialize an empty array for parameters
                ];
            }

            // Add parameter details to the parameters array
            if ($row['parameter_id']) {
                //Regular parameter
                $entryData['parameters'][$row['parameter_id']] = [
                    'id' => $row['parameter_id'],
                    'name' => $row['parameter_name'],
                    'data_type' => $row['parameter_data_type'],
                    'options' => $row['parameter_options'],
                    'value' => $row['parameter_value'],
                ];

            } elseif ($row['custom_parameter_name']) {
                // Custom parameter (no parameter_id, use custom name as key)

                $entryData['parameters'][$row['custom_parameter_name']] = [
                  'id' => null,
                  'name' => $row['custom_parameter_name'],
                  'data_type' => 'text', //Assume text for custom
                  'options' => null,
                  'value' => $row['parameter_value'],
                ];

            }
        }
    // If entry is not found fetch will return false
    return $entryData ? $entryData : false;
    }
}