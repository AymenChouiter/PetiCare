<?php

require_once __DIR__ . '/../models/ItemModel.php';

class ItemController
{
    private ItemModel $model;

    public function __construct()
    {
        $this->model = new ItemModel();
    }

    /**
     * Render HTML page listing all animals and providing forms.
     */
    public function index(): void
    {
        $animals = $this->model->all();

        if ($animals === false) {
            http_response_code(500);
            header('Content-Type: text/plain');
            echo 'Failed to load animals from database.';
            return;
        }

        foreach ($animals as &$animal) {
            $birth = new DateTime($animal['birth_date']);
            $diff = (new DateTime())->diff($birth);
            $animal['age'] = $diff->y . 'y ' . $diff->m . 'm';

            $animal['gender'] = ucfirst(strtolower($animal['gender']));
            $animal['health_status'] = ucfirst(strtolower($animal['health_status']));

            // "UNDER_TREATMENT" -> "Under Treatment"
            $lcHealth = strtolower($animal['health_status']);
            $animal['health_status'] = ucwords(str_replace('_', ' ', $lcHealth));
            $animal['health_class'] = str_replace('_', '-', $lcHealth);

            $animal['adoption_status'] = ucfirst(strtolower($animal['adoption_status']));
            $animal['adoption_fee'] = $animal['adoption_fee'] > 0
                ? '$' . number_format($animal['adoption_fee'], 2)
                : 'Free';

            $animal['created_at'] = date('M d, Y', strtotime($animal['created_at']));
            $animal['updated_at'] = date('M d, Y', strtotime($animal['updated_at']));
        }
        unset($animal);

        // Render the main admin page (HTML)
        require __DIR__ . '/../../public/index.php';
    }

    /**
     * Get request data.
     * - If Content-Type is application/json, read JSON body.
     * - Otherwise use $_POST (form data).
     */
    private function getRequestData(): array
    {
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
    private function extractPicture(): ?string
    {
        if (!isset($_FILES['picture']) || !is_array($_FILES['picture'])) {
            return null;
        }

        if (!empty($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $content = file_get_contents($_FILES['picture']['tmp_name']);
            return $content === false ? null : $content;
        }

        return null;
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        $data = $this->getRequestData();

        // Map expected fields for an animal
        $requiredFields = ['animalName', 'species', 'gender', 'animalAge', 'animalPrice', 'status'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => $field . ' is required',
                ]);
                return;
            }
        }

        // Optional fields
        $data['description'] = $data['description'] ?? null;
        $data['illnesses'] = $data['illnesses'] ?? null;

        // Picture from upload (if any)
        $picture = $this->extractPicture();
        if ($picture !== null) {
            $data['picture'] = $picture;
        }

        $animal = $this->model->create($data);

        if ($animal === false) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create animal',
            ]);
            return;
        }

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => $animal,
        ]);
    }

    public function update(): void
    {
        header('Content-Type: application/json');

        $data = $this->getRequestData();

        // ID is required
        $id = $data['animalId'] ?? ($_GET['animalId'] ?? null);
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'animalId is required',
            ]);
            return;
        }

        // At least one updatable field must be present
        $updatableFields = [
            'animalName',
            'species',
            'gender',
            'animalAge',
            'description',
            'illnesses',
            'animalPrice',
            'status',
        ];

        $hasUpdateField = false;
        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== '') {
                $hasUpdateField = true;
                break;
            }
        }

        // Picture from upload (if any) also counts as an update field
        $picture = $this->extractPicture();
        if ($picture !== null) {
            $data['picture'] = $picture;
            $hasUpdateField = true;
        }

        if (!$hasUpdateField) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'At least one field must be provided for update',
            ]);
            return;
        }

        $updated = $this->model->update($id, $data);

        if ($updated === false) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update animal',
            ]);
            return;
        }

        if ($updated === null) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Animal not found',
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'data' => $updated,
        ]);
    }

    public function destroy(): void
    {
        header('Content-Type: application/json');

        $data = $this->getRequestData();
        $id = $data['animalId'] ?? ($_GET['animalId'] ?? null);

        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'animalId is required',
            ]);
            return;
        }

        $deleted = $this->model->delete($id);

        if ($deleted === false) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete animal',
            ]);
            return;
        }

        if ($deleted === null) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Animal not found',
            ]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Animal deleted successfully',
        ]);
    }
}

