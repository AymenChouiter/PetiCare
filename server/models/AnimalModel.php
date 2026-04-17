<?php

require_once __DIR__ . '/../../database/database.php';

class animalModel
{
    private mysqli $dbConnection;
    private string $animalTableName = 'animals';

    public function __construct()
    {
        global $conn;
        $this->dbConnection = $conn;
    }

    public function fetchAllAnimals()
    {
        $qry = "
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
        $resultObj = $this->dbConnection->query($qry);

        if ($resultObj === false)
            return false;

        $allAnimals = $resultObj->fetch_all(MYSQLI_ASSOC);
        $resultObj->free();

        return $allAnimals;
    }

    public function createAnimal(array $data)
    {
        $qry = "
        INSERT INTO {$this->animalTableName} (
            name, species, gender, birth_date, description,
            health_status, adoption_fee, adoption_status, picture_data
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->dbConnection->prepare($qry);

        if (!$stmt)
            return false;

        $picture = $data['picture_data'] ?? null;
        if (
            !$stmt->bind_param(
                'sssssssss',
                $data['name'],
                $data['species'],
                $data['gender'],
                $data['birth_date'],
                $data['description'],
                $data['health_status'],
                $data['adoption_fee'],
                $data['adoption_status'],
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

        $stmt->close();
        return true;
    }

    public function updateAnimal(int $id, array $data)
    {
        $check = $this->dbConnection->prepare("SELECT id FROM {$this->animalTableName} WHERE id = ?");
        $check->bind_param('i', $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $check->close();
            return null;
        }
        $check->close();

        $picture = $data['picture_data'] ?? null;
        $deletePicture = ($picture === '');

        if ($deletePicture) {
            $qry = "UPDATE {$this->animalTableName}
                SET name=?, species=?, gender=?, birth_date=?, description=?,
                    health_status=?, adoption_fee=?, adoption_status=?,
                    picture_data = NULL
                WHERE id=?";
            $stmt = $this->dbConnection->prepare($qry);
            if (!$stmt)
                return false;
            if (
                !$stmt->bind_param(
                    'ssssssssi',
                    $data['name'],
                    $data['species'],
                    $data['gender'],
                    $data['birth_date'],
                    $data['description'],
                    $data['health_status'],
                    $data['adoption_fee'],
                    $data['adoption_status'],
                    $id
                )
            ) {
                $stmt->close();
                return false;
            }
        } else {
            $qry = "UPDATE {$this->animalTableName}
                SET name=?, species=?, gender=?, birth_date=?, description=?,
                    health_status=?, adoption_fee=?, adoption_status=?,
                    picture_data = COALESCE(?, picture_data)
                WHERE id=?";
            $stmt = $this->dbConnection->prepare($qry);
            if (!$stmt)
                return false;
            if (
                !$stmt->bind_param(
                    'sssssssssi',
                    $data['name'],
                    $data['species'],
                    $data['gender'],
                    $data['birth_date'],
                    $data['description'],
                    $data['health_status'],
                    $data['adoption_fee'],
                    $data['adoption_status'],
                    $picture,
                    $id
                )
            ) {
                $stmt->close();
                return false;
            }
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }

    public function deleteAnimal($id)
    {
        $qry = "DELETE FROM {$this->animalTableName} WHERE id = ?";
        $deleteAnimalStmt = $this->dbConnection->prepare($qry);

        if (!$deleteAnimalStmt)
            return false;

        $animalId = (int) $id;

        if (!$deleteAnimalStmt->bind_param('i', $animalId)) {
            $deleteAnimalStmt->close();
            return false;
        }

        if (!$deleteAnimalStmt->execute()) {
            $deleteAnimalStmt->close();
            return false;
        }

        $affectedRows = $deleteAnimalStmt->affected_rows;
        $deleteAnimalStmt->close();

        if ($affectedRows === 0) {
            return null;
        }

        return true;
    }
}