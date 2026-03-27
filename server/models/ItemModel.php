<?php

require_once __DIR__ . '/../../database/database.php';

class ItemModel{
    private mysqli $databaseConnection;
    private string $animalTableName = '`animals`';

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

        return true;
    }

    public function updateAnimalRecordById($id, array $data){
        $animalName = $data['name'] ;
        $animalSpecies = $data['species'] ;
        $animalGender = $data['gender'] ;
        $animalBirthDate = $data['birth_date'] ;
        $animalDescription = $data['description'] ;
        $animalHealthStatus = $data['health_status'] ;
        $animalAdoptionFee = $data['adoption_fee'] ;
        $animalAdoptionStatus = $data['adoption_status'] ;
        $animalPictureBinaryData = $data['picture_data'] ;

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

        return true;
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