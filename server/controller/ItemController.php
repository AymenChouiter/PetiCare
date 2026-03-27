<?php

require_once __DIR__ . '/../models/ItemModel.php';

class ItemController{
    private ItemModel $animalDataRepository;

    public function __construct(){
        $this->animalDataRepository = new ItemModel();
    }

    public function renderAnimalManagementPage(): void{
        $animalRecords = $this->animalDataRepository->fetchAllAnimalRecords();

        if ($animalRecords === false) {
            http_response_code(500);
            header('Content-Type: text/plain');
            echo 'Failed to load animals from database.';
            return;
        }

        $animals = array_map(function ($animal) {
            if (!empty($animal['birth_date'])) {
                $birthDate = new DateTime($animal['birth_date']);
                $today = new DateTime();
                $ageInterval = $today->diff($birthDate);

                if ($ageInterval->y > 0) {
                    $animal['age'] = $ageInterval->y . ($ageInterval->y === 1 ? ' year' : ' years');
                } else {
                    $animal['age'] = $ageInterval->m . ($ageInterval->m === 1 ? ' month' : ' months');
                }
            } else {
                $animal['age'] = 'Unknown';
            }

            return $animal;
        }, $animalRecords);
        require __DIR__ . '/../../public/index.php';
    }

    private function extractIncomingRequestData(): array{
        $incomingRequestContentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($incomingRequestContentType, 'application/json') !== false) {
            $rawRequestBodyContent = file_get_contents('php://input');
            if (!empty($rawRequestBodyContent)) {
                $decodedJsonRequestBody = json_decode($rawRequestBodyContent, true);
                if (is_array($decodedJsonRequestBody)) {
                    return $decodedJsonRequestBody;
                }
            }
            return [];
        }

        return $_POST ?? [];
    }

    private function extractUploadedAnimalPictureBinaryData(): ?string{
        if (!isset($_FILES['picture']) || !is_array($_FILES['picture'])) {
            return null;
        }

        if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $uploadedPictureBinaryContent = file_get_contents($_FILES['picture']['tmp_name']);
            return $uploadedPictureBinaryContent === false ? null : $uploadedPictureBinaryContent;
        }

        return null;
    }


    public function createAnimalRecord(): void{
        header('Content-Type: application/json');
        $requestData = $this->extractIncomingRequestData();

        $requiredFields = ['name', 'species', 'gender', 'birth_date'];
        foreach ($requiredFields as $requiredFieldName) {
            if (empty($requestData[$requiredFieldName])) {
                http_response_code(400);
                echo json_encode([
                    'status'  => 'error',
                    'message' => $requiredFieldName . ' is required',
                ]);
                return;
            }
        }

        $requestData['description'] = $requestData['description'] ?? null;
        $requestData['health_status'] = $requestData['health_status'] ?? 'HEALTHY';
        $requestData['adoption_fee'] = $requestData['adoption_fee'] ?? '0.00';
        $requestData['adoption_status'] = $requestData['adoption_status'] ?? 'AVAILABLE';

        $uploadedPictureBinaryData = $this->extractUploadedAnimalPictureBinaryData();
        if ($uploadedPictureBinaryData !== null) {
            $requestData['picture_data'] = $uploadedPictureBinaryData;
        }

        $createdAnimalRecord = $this->animalDataRepository->createAnimalRecord($requestData);

        if ($createdAnimalRecord === false) {
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Failed to create animal',
            ]);
            return;
        }

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
        ]);
    }

    public function updateAnimalRecord(): void{
        header('Content-Type: application/json');
        $requestData = $this->extractIncomingRequestData();

        $animalId = $requestData['id'] ?? ($_GET['id'] ?? null);
        if (empty($animalId)) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'id is required',
            ]);
            return;
        }

        $updatableFields = [
            'name',
            'species',
            'gender',
            'birth_date',
            'description',
            'health_status',
            'adoption_fee',
            'adoption_status',
        ];

        $requestIncludesUpdatableFields = false;
        foreach ($updatableFields as $updatableFieldName) {
            if (array_key_exists($updatableFieldName, $requestData) && $requestData[$updatableFieldName] !== '') {
                $requestIncludesUpdatableFields = true;
                break;
            }
        }

        $uploadedPictureBinaryData = $this->extractUploadedAnimalPictureBinaryData();
        if ($uploadedPictureBinaryData !== null) {
            $requestData['picture_data'] = $uploadedPictureBinaryData;
            $requestIncludesUpdatableFields = true;
        }

        if (!$requestIncludesUpdatableFields) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'At least one field must be provided for update',
            ]);
            return;
        }

        $updatedAnimalRecord = $this->animalDataRepository->updateAnimalRecordById($animalId, $requestData);

        if ($updatedAnimalRecord === false) {
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Failed to update animal',
            ]);
            return;
        }

        if ($updatedAnimalRecord === null) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Animal not found',
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
        ]);
    }

    public function deleteAnimalRecord(): void{
        header('Content-Type: application/json');
        $requestData = $this->extractIncomingRequestData();
        $animalId = $requestData['id'] ?? ($_GET['id'] ?? null);

        if (empty($animalId)) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'id is required',
            ]);
            return;
        }

        $deleted = $this->animalDataRepository->deleteAnimalRecordById($animalId);

        if ($deleted === false) {
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Failed to delete animal',
            ]);
            return;
        }

        if ($deleted === null) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Animal not found',
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'status'  => 'success',
            'message' => 'Animal deleted successfully',
        ]);
    }
}