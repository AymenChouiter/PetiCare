<?php

require_once __DIR__ . '/../models/AnimalModel.php';

class AnimalController
{
    private AnimalModel $model;

    public function __construct()
    {
        $this->model = new AnimalModel();
    }

    public function index(): void
    {
        $animals = $this->model->fetchAllAnimals();

        if ($animals === false) {
            http_response_code(500);
            header('Content-Type: text/plain');
            echo 'Failed to load all animals from database.';
            return;
        }

        // >>>>>>>>>>>>>>>>>>>>>> Formating for the view
        foreach ($animals as &$animal) {
            $birthDate = new DateTime($animal['birth_date']);
            $today = new DateTime();
            $ageInterval = $today->diff($birthDate);

            $y = $ageInterval->y;
            $m = $ageInterval->m;
            $d = $ageInterval->d;

            if ($y > 0 && $m > 0) {
                $animal['age'] = "{$y}y {$m}m";
            } elseif ($y > 0 && !$m) {
                $animal['age'] = "{$y}y";
            } elseif (!$y && $m > 0) {
                $animal['age'] = "{$m}m";
            } else {
                $animal['age'] = "{$d}d";
            }

            $animal['gender_display'] = ucfirst(strtolower($animal['gender']));

            $healthLower = strtolower($animal['health_status']);
            $animal['health_display'] = ucwords(str_replace('_', ' ', $healthLower));
            $animal['health_class'] = str_replace('_', '-', $healthLower);

            $adoptionLower = strtolower($animal['adoption_status']);
            $animal['adoption_display'] = ucfirst($adoptionLower);
            $animal['adoption_class'] = $adoptionLower;

            $fee = (float) $animal['adoption_fee'];
            $animal['adoption_fee'] = $fee > 0
                ? '$' . ($fee == floor($fee) ? number_format($fee, 0) : number_format($fee, 2))
                : 'Free';

            $animal['created_at'] = date('M d, Y', strtotime($animal['created_at']));
            $animal['updated_at'] = date('M d, Y', strtotime($animal['updated_at']));
        }
        unset($animal);

        require __DIR__ . '/../../public/index.php';
    }

    // >>>>>>>>>>>>>>>>>> Utilities
    private function getUploadedAnimalPicture(): ?string
    {
        $file = $_FILES['picture']['tmp_name'] ?? null;
        if (!$file || !is_uploaded_file($file))
            return null;

        $content = file_get_contents($file);
        return $content ?: null;
    }

    private function jsonResponse(string $status, int $code, string $message): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message]);
        exit;
    }

    private function requireFields(array $animalData): bool
    {
        $requiredFields = ['name', 'species', 'gender', 'birth_date', 'health_status', 'adoption_fee', 'adoption_status'];

        foreach ($requiredFields as $field) {
            if (!isset($animalData[$field]) || $animalData[$field] === '') {
                $this->jsonResponse('Error', 400, "{$field} is required");
                return false;
            }
        }
        return true;
    }

    private function validateAnimalData(array $data): bool
    {
        // >>>>>>>>>>>>>>>>>> Enums
        $validValues = [
            'gender' => ['MALE', 'FEMALE'],
            'health_status' => ['HEALTHY', 'UNDER_TREATMENT'],
            'adoption_status' => ['AVAILABLE', 'RESERVED', 'ADOPTED'],
        ];

        foreach ($validValues as $field => $allowed) {
            if (!in_array($data[$field] ?? '', $allowed, true)) {
                $this->jsonResponse('Error', 400, "Invalid value for {$field}");
                return false;
            }
        }

        // >>>>>>>>>>>>>>>>>> Birth date
        $birthTimestamp = strtotime($data['birth_date'] ?? '');
        if (!$birthTimestamp) {
            $this->jsonResponse('Error', 400, 'Invalid birth date');
            return false;
        }
        if ($birthTimestamp > time()) {
            $this->jsonResponse('Error', 400, 'Birth date cannot be in the future');
            return false;
        }

        // >>>>>>>>>>>>>>>>>> Adoption fee
        $fee = isset($data['adoption_fee']) ? (float) $data['adoption_fee'] : 0;
        if ($fee < 0) {
            $this->jsonResponse('Error', 400, 'Adoption fee must be 0 or higher');
            return false;
        }

        return true;
    }

    private function normalizeAnimalData(array $animalData): array
    {
        $animalData['description'] = !empty($animalData['description']) ? $animalData['description'] : null;
        $animalData['adoption_fee'] = isset($animalData['adoption_fee']) ? (float) $animalData['adoption_fee'] : 0.00;
        $animalData['picture_data'] = $this->getUploadedAnimalPicture();
        return $animalData;
    }

    private function checkRequireId($id): bool
    {
        if (!isset($id) || $id === '') {
            $this->jsonResponse('Error', 400, 'id is required');
            return false;
        }
        return true;
    }

    // >>>>>>>>>>>>>>>>>> Main methods
    public function createAnimal(): void
    {
        $data = $_POST;

        if (!$this->requireFields($data) || !$this->validateAnimalData($data))
            return;

        $data = $this->normalizeAnimalData($data);

        $isCreated = $this->model->createAnimal($data);
        if (!$isCreated)
            $this->jsonResponse('Error', 500, 'Failed to create animal');

        $this->jsonResponse('Success', 201, 'Animal created successfully');
    }

    public function updateAnimal(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$this->checkRequireId($id))
            return;

        $data = $_POST;

        if (!$this->requireFields($data) || !$this->validateAnimalData($data))
            return;

        $data = $this->normalizeAnimalData($data);

        $isUpdated = $this->model->updateAnimal((int) $id, $data);
        if ($isUpdated === false)
            $this->jsonResponse('Error', 500, 'Failed to update animal');
        if ($isUpdated === null)
            $this->jsonResponse('Error', 404, 'Animal not found');

        $this->jsonResponse('Success', 200, 'Animal updated successfully');
    }

    public function deleteAnimal(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$this->checkRequireId($id))
            return;

        $isDeleted = $this->model->deleteAnimal($id);
        if ($isDeleted === false)
            $this->jsonResponse('Error', 500, 'Failed to delete animal');
        if ($isDeleted === null)
            $this->jsonResponse('Error', 404, 'Animal not found');

        $this->jsonResponse('Success', 200, 'Animal deleted successfully');
    }
}