<?php

class Crud {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($query) {
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute()) {
                return json_encode(["message" => "Record created successfully."]);
            } else {
                return json_encode(["message" => "Unable to create record."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function read($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($result);
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function update($query) {
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute()) {
                return json_encode(["message" => "Record updated successfully."]);
            } else {
                return json_encode(["message" => "Unable to update record."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function delete($query) {
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute()) {
                return json_encode(["message" => "Record deleted successfully."]);
            } else {
                return json_encode(["message" => "Unable to delete record."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => $e->getMessage()]);
        }
    }
}
?>
