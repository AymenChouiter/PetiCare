<?php

require_once __DIR__ . '/../../database/connection.php';

class ItemModel
{
    /**
     * @var mysqli
     */
    private $conn;

    /**
     * @var string
     */
    private $table = 'animals';

    public function __construct()
    {
        // Use the global mysqli connection defined in database.php
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Fetch all animals.
     *
     * @return array|false
     */
    public function all()
    {
        $sql = "SELECT id, name, species, gender, birth_date, description, health_status, adoption_fee, adoption_status, picture_blob, created_at, updated_at FROM {$this->table}";
        $result = $this->conn->query($sql);

        if ($result === false) {
            return false;
        }

        $animals = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();

        return $animals;
    }

    /**
     * Find a single animal by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $sql = "SELECT animalId, animalName, species, gender, animalAge, description, illnesses, animalPrice, status, picture, createdAt FROM {$this->table} WHERE animalId = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $id = (int) $id;

        if (!$stmt->bind_param('i', $id)) {
            $stmt->close();
            return null;
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        if ($result === false) {
            $stmt->close();
            return null;
        }

        $animal = $result->fetch_assoc() ?: null;

        $result->free();
        $stmt->close();

        return $animal;
    }

    /**
     * Create a new animal.
     *
     * @param array $data
     * @return array|false  Returns created animal on success, false on SQL error.
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (animalName, species, gender, animalAge, description, illnesses, animalPrice, status, picture)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $animalName = $data['animalName'] ?? null;
        $species = $data['species'] ?? null;
        $gender = $data['gender'] ?? null;
        $animalAge = $data['animalAge'] ?? null;
        $description = $data['description'] ?? null;
        $illnesses = $data['illnesses'] ?? null;
        $animalPrice = $data['animalPrice'] ?? null;
        $status = $data['status'] ?? null;
        $picture = $data['picture'] ?? null;

        // Treat all as strings; MySQL will coerce numeric fields appropriately.
        if (
            !$stmt->bind_param(
                'sssssssss',
                $animalName,
                $species,
                $gender,
                $animalAge,
                $description,
                $illnesses,
                $animalPrice,
                $status,
                $picture
            )
        ) {
            $stmt->close();
            return false;
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $id = $stmt->insert_id ?: $this->conn->insert_id;
        $stmt->close();

        return $this->find($id);
    }

    /**
     * Update an existing animal.
     *
     * @param int   $id
     * @param array $data
     * @return array|null|false  Updated animal on success, null if not found, false on SQL error.
     */
    public function update($id, array $data)
    {
        // Load existing record to support partial updates
        $existing = $this->find($id);
        if ($existing === null) {
            return null;
        }

        $animalName = array_key_exists('animalName', $data) ? $data['animalName'] : $existing['animalName'];
        $species = array_key_exists('species', $data) ? $data['species'] : $existing['species'];
        $gender = array_key_exists('gender', $data) ? $data['gender'] : $existing['gender'];
        $animalAge = array_key_exists('animalAge', $data) ? $data['animalAge'] : $existing['animalAge'];
        $description = array_key_exists('description', $data) ? $data['description'] : $existing['description'];
        $illnesses = array_key_exists('illnesses', $data) ? $data['illnesses'] : $existing['illnesses'];
        $animalPrice = array_key_exists('animalPrice', $data) ? $data['animalPrice'] : $existing['animalPrice'];
        $status = array_key_exists('status', $data) ? $data['status'] : $existing['status'];
        $picture = array_key_exists('picture', $data) ? $data['picture'] : $existing['picture'];

        $sql = "UPDATE {$this->table}
                SET animalName = ?, species = ?, gender = ?, animalAge = ?, description = ?, illnesses = ?, animalPrice = ?, status = ?, picture = ?
                WHERE animalId = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $id = (int) $id;

        if (
            !$stmt->bind_param(
                'sssssssssi',
                $animalName,
                $species,
                $gender,
                $animalAge,
                $description,
                $illnesses,
                $animalPrice,
                $status,
                $picture,
                $id
            )
        ) {
            $stmt->close();
            return false;
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $affected = $stmt->affected_rows;
        $stmt->close();

        if ($affected === 0) {
            // No rows updated (e.g. values are identical)
            return $this->find($id);
        }

        return $this->find($id);
    }

    /**
     * Delete an animal.
     *
     * @param int $id
     * @return bool|null  true on delete, null if not found, false on SQL error.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE animalId = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $id = (int) $id;

        if (!$stmt->bind_param('i', $id)) {
            $stmt->close();
            return false;
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $affected = $stmt->affected_rows;
        $stmt->close();

        if ($affected === 0) {
            // No rows deleted (likely ID not found)
            return null;
        }

        return true;
    }
}

