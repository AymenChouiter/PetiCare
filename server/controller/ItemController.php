<?php

require_once __DIR__ . '/../models/ItemModel.php';

class ItemController{
    private ItemModel $model;
    public function __construct(){
        $this->model = new ItemModel();
    }

    /**
     * Render HTML page listing all animals and providing forms.
     */
    public function index(): void{
        $animals = $this->model->all();

        if ($animals === false) {
            http_response_code(500);
            header('Content-Type: text/plain');
            echo 'Failed to load animals from database.';
            return;
        }

        // Render the main admin page (HTML)
        require __DIR__ . '/../../public/index.php';
    }

    /**
     * Get request data.
     * - If Content-Type is application/json, read JSON body.
     * - Otherwise use $_POST (form data).
     */
    private function getRequestData(): array{
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            if (!empty($raw)) {
                $data = json_decode($raw, true);
                if (is_array($data)) {
                    return $data;
                }
            }
            return [];
        }

        // For form submissions (including multipart/form-data)
        return $_POST ?? [];
    }

    /**
     * Handle file upload for picture if present.
     */
    private function extractPicture(): ?string{
        if (!isset($_FILES['picture']) || !is_array($_FILES['picture'])) {
            return null;
        }

        if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $content = file_get_contents($_FILES['picture']['tmp_name']);
            return $content === false ? null : $content;
        }

        return null;
    }

    private function encodeAnimalPictureForOutput(array $animalRecord): array{
        if (!empty($animalRecord['picture_data'])) {
            $animalRecord['picture_data'] = base64_encode($animalRecord['picture_data']);
        }
        return $animalRecord;
    }

    private function encodeAnimalCollectionForOutput(array $animalRecords): array{
        $encodedAnimalRecords = [];
        foreach ($animalRecords as $animalRecord) {
            $encodedAnimalRecords[] = $this->encodeAnimalPictureForOutput($animalRecord);
        }
        return $encodedAnimalRecords;
    }

    public function store(): void{
        header('Content-Type: application/json');
        $requestData = $this->getRequestData();

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

        $uploadedPictureBinaryData = $this->extractPicture();
        if ($uploadedPictureBinaryData !== null) {
            $requestData['picture_data'] = $uploadedPictureBinaryData;
        }

        $createdAnimalRecord = $this->model->create($requestData);

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
            'data'   => $this->encodeAnimalPictureForOutput($createdAnimalRecord),
        ]);
    }

    public function update(): void{
        header('Content-Type: application/json');
        $requestData = $this->getRequestData();

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

        $uploadedPictureBinaryData = $this->extractPicture();
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

        $updatedAnimalRecord = $this->model->update($animalId, $requestData);

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
            'data'   => $this->encodeAnimalPictureForOutput($updatedAnimalRecord),
        ]);
    }

    public function destroy(): void{
        header('Content-Type: application/json');
        $requestData = $this->getRequestData();
        $animalId = $requestData['id'] ?? ($_GET['id'] ?? null);

        if (empty($animalId)) {
            http_response_code(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'id is required',
            ]);
            return;
        }

        $deleted = $this->model->delete($animalId);

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