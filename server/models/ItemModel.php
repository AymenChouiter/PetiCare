<?php

require_once __DIR__ . '/../../database/database.php';

class ItemModel{
    private mysqli $databaseConnection;
    private string $animalTableName = '`animal`';

    public function __construct(){
        global $conn;
        $this->databaseConnection = $conn;
    }

    public function fetchAllAnimalRecords(){
        $selectAllAnimalRecordsQuery = "
            SELECT
                id,
                name,
                species,
                gender,
                birth_date,
                description,
                health_status,
                adoption_fee,
                adoption_status,
                picture_data,
                created_at,
                updated_at
            FROM {$this->animalTableName}
        ";
        $allAnimalRecordsQueryResult = $this->databaseConnection->query($selectAllAnimalRecordsQuery);

        if ($allAnimalRecordsQueryResult === false) {
            return false;
        }

        $allAnimalRecords = $allAnimalRecordsQueryResult->fetch_all(MYSQLI_ASSOC);
        $allAnimalRecordsQueryResult->free();

        return $allAnimalRecords;
    }

    public function fetchAnimalRecordById($id){
        $findAnimalByIdQuery = "
            SELECT
                id,
                name,
                species,
                gender,
                birth_date,
                description,
                health_status,
                adoption_fee,
                adoption_status,
                picture_data,
                created_at,
                updated_at
            FROM {$this->animalTableName}
            WHERE id = ?
        ";
        $findAnimalByIdStatement = $this->databaseConnection->prepare($findAnimalByIdQuery);

        if (!$findAnimalByIdStatement) {
            return null;
        }

        $animalId = (int) $id;

        if (!$findAnimalByIdStatement->bind_param('i', $animalId)) {
            $findAnimalByIdStatement->close();
            return null;
        }

        if (!$findAnimalByIdStatement->execute()) {
            $findAnimalByIdStatement->close();
            return null;
        }

        $result = $findAnimalByIdStatement->get_result();
        if ($result === false) {
            $findAnimalByIdStatement->close();
            return null;
        }

        $animalRecord = $result->fetch_assoc() ?: null;

        $result->free();
        $findAnimalByIdStatement->close();

        return $animalRecord;
    }

    public function createAnimalRecord(array $data){
        $insertAnimalQuery = "
            INSERT INTO {$this->animalTableName} (
                name,
                species,
                gender,
                birth_date,
                description,
                health_status,
                adoption_fee,
                adoption_status,
                picture_data
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $insertAnimalStatement = $this->databaseConnection->prepare($insertAnimalQuery);

        if (!$insertAnimalStatement) {
            return false;
        }

        $animalName = $data['name'] ?? null;
        $animalSpecies = $data['species'] ?? null;
        $animalGender = $data['gender'] ?? null;
        $animalBirthDate = $data['birth_date'] ?? null;
        $animalDescription = $data['description'] ?? null;
        $animalHealthStatus = $data['health_status'] ?? 'HEALTHY';
        $animalAdoptionFee = $data['adoption_fee'] ?? '0.00';
        $animalAdoptionStatus = $data['adoption_status'] ?? 'AVAILABLE';
        $animalPictureBinaryData = $data['picture_data'] ?? null;

        if (!$insertAnimalStatement->bind_param(
            'sssssssss',
            $animalName,
            $animalSpecies,
            $animalGender,
            $animalBirthDate,
            $animalDescription,
            $animalHealthStatus,
            $animalAdoptionFee,
            $animalAdoptionStatus,
            $animalPictureBinaryData
        )) {
            $insertAnimalStatement->close();
            return false;
        }

        if (!$insertAnimalStatement->execute()) {
            $insertAnimalStatement->close();
            return false;
        }

        $createdAnimalId = $insertAnimalStatement->insert_id ?: $this->databaseConnection->insert_id;
        $insertAnimalStatement->close();

        return $this->fetchAnimalRecordById($createdAnimalId);
    }

    public function updateAnimalRecordById($id, array $data){
        $existingAnimalRecord = $this->fetchAnimalRecordById($id);
        if ($existingAnimalRecord === null) {
            return null;
        }

        $animalName = array_key_exists('name', $data) ? $data['name'] : $existingAnimalRecord['name'];
        $animalSpecies = array_key_exists('species', $data) ? $data['species'] : $existingAnimalRecord['species'];
        $animalGender = array_key_exists('gender', $data) ? $data['gender'] : $existingAnimalRecord['gender'];
        $animalBirthDate = array_key_exists('birth_date', $data) ? $data['birth_date'] : $existingAnimalRecord['birth_date'];
        $animalDescription = array_key_exists('description', $data) ? $data['description'] : $existingAnimalRecord['description'];
        $animalHealthStatus = array_key_exists('health_status', $data) ? $data['health_status'] : $existingAnimalRecord['health_status'];
        $animalAdoptionFee = array_key_exists('adoption_fee', $data) ? $data['adoption_fee'] : $existingAnimalRecord['adoption_fee'];
        $animalAdoptionStatus = array_key_exists('adoption_status', $data) ? $data['adoption_status'] : $existingAnimalRecord['adoption_status'];
        $animalPictureBinaryData = array_key_exists('picture_data', $data) ? $data['picture_data'] : $existingAnimalRecord['picture_data'];

        $updateAnimalQuery = "
            UPDATE {$this->animalTableName}
            SET
                name = ?,
                species = ?,
                gender = ?,
                birth_date = ?,
                description = ?,
                health_status = ?,
                adoption_fee = ?,
                adoption_status = ?,
                picture_data = ?
            WHERE id = ?
        ";
        $updateAnimalStatement = $this->databaseConnection->prepare($updateAnimalQuery);

        if (!$updateAnimalStatement) {
            return false;
        }

        $animalId = (int) $id;

        if (!$updateAnimalStatement->bind_param(
            'sssssssssi',
            $animalName,
            $animalSpecies,
            $animalGender,
            $animalBirthDate,
            $animalDescription,
            $animalHealthStatus,
            $animalAdoptionFee,
            $animalAdoptionStatus,
            $animalPictureBinaryData,
            $animalId
        )) {
            $updateAnimalStatement->close();
            return false;
        }

        if (!$updateAnimalStatement->execute()) {
            $updateAnimalStatement->close();
            return false;
        }

        $affectedRows = $updateAnimalStatement->affected_rows;
        $updateAnimalStatement->close();

        if ($affectedRows === 0) {
            return $this->fetchAnimalRecordById($animalId);
        }

        return $this->fetchAnimalRecordById($animalId);
    }

    public function deleteAnimalRecordById($id){
        $deleteAnimalQuery = "DELETE FROM {$this->animalTableName} WHERE id = ?";
        $deleteAnimalStatement = $this->databaseConnection->prepare($deleteAnimalQuery);

        if (!$deleteAnimalStatement) {
            return false;
        }

        $animalId = (int) $id;

        if (!$deleteAnimalStatement->bind_param('i', $animalId)) {
            $deleteAnimalStatement->close();
            return false;
        }

        if (!$deleteAnimalStatement->execute()) {
            $deleteAnimalStatement->close();
            return false;
        }

        $affectedRows = $deleteAnimalStatement->affected_rows;
        $deleteAnimalStatement->close();

        if ($affectedRows === 0) {
            return null;
        }

        return true;
    }
}