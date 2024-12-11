<?php

namespace App\Controllers;

use App\Models\AnimalModel;

class AnimalController extends BaseController
{
    protected $animalModel;

    public function getAnimals()
    {
        $animals = $this->animalModel->findAll();

        if (empty($animals)) {
            return $this->response->setJSON(['error' => 'No records found in the animals table']);
        }

        foreach ($animals as &$animal) {
            $animal['picture'] = base64_encode($animal['picture']);
        }

        return $this->response->setJSON($animals);
    }

    public function getAnimal($id)
    {
        $animal = $this->animalModel->find($id);

        if ($animal == null) {
            return $this->response->setJSON(['error' => 'No records found in the animals table']);
        }

        $animal['picture'] = base64_encode( $animal['picture']);

        return $this->response->setJSON($animal);
    }

    public function deleteAnimals($id)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'ID not provided'])->setStatusCode(400);
        }

        $deleted = $this->animalModel->delete($id);

        if ($deleted) {
            return $this->response->setJSON(['message' => 'Animal deleted successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Failed to delete animal'])->setStatusCode(500);
        }
    }
    public function createAnimals()
    {
        $data = $this->request->getPost();
        $picture = $this->request->getFile('picture');

        log_message('info', 'Majhoul');

        $validationRules = [
            'name' => 'required',
            'species' => 'required',
            'age' => 'required|integer',
            'sex' => 'required',
            'location' => 'required',
            'description' => 'required',
            'picture' => 'uploaded[picture]|max_size[picture,2048]|is_image[picture]|mime_in[picture,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'error' => 'Validation failed',
                'messages' => $this->validator->getErrors(),
            ])->setStatusCode(422);
        }

        $fileBlob = null;
        if ($picture->isValid() && !$picture->hasMoved()) {
            $fileBlob = file_get_contents($picture->getTempName());
        }

        $animalData = [
            'name' => $data['name'],
            'species' => $data['species'],
            'age' => $data['age'],
            'sex' => $data['sex'],
            'location' => $data['location'],
            'description' => $data['description'],
            'picture' => $fileBlob,
        ];

        // Insert and handle errors
        if ($this->animalModel->insert($animalData)) {
            return $this->response->setJSON(['message' => 'Animal created successfully']);
        } else {
            $dbError = $this->animalModel->db->error(); // Retrieve the error if any
            log_message('error', 'DB Insert Error: ' . json_encode($dbError));

            $errorMessage = $dbError['message'] ?? 'Unknown database error';
            return $this->response->setJSON([
                'error' => 'Failed to create animal',
                'dbError' => $dbError,
                'debug' => $animalData, // Optional: For debugging purposes
            ])->setStatusCode(500);
        }
    }



    public function updateAnimals($id)
    {
        if (!$id) {
            return $this->response->setJSON(['error' => 'ID not provided'])->setStatusCode(400);
        }

        $data = $this->request->getPost(); // Get the POST data
        $picture = $this->request->getFile('picture');

        // Validate the input
        $validationRules = [
            'name' => 'required',
            'species' => 'required',
            'age' => 'required|integer',
            'sex' => 'required',
            'location' => 'required',
            'description' => 'required',
            'picture' => 'is_image[picture]|mime_in[picture,image/jpg,image/jpeg,image/png]', // Optional picture field
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'error' => 'Validation failed',
                'messages' => $this->validator->getErrors(),
                'bla' => json_encode($this->request->getPost())

            ])->setStatusCode(400);
        }

        // Check if the animal exists
        $animal = $this->animalModel->find($id);

        if (!$animal) {
            return $this->response->setJSON(['error' => 'Animal not found'])->setStatusCode(404);
        }

        $fileBlob = null;
        if ($picture->isValid() && !$picture->hasMoved()) {
            $fileBlob = file_get_contents($picture->getTempName()); // Convert the file to a blob
        }

        // Update the animal data
        $animalData = [
            'name' => $data['name'],
            'species' => $data['species'],
            'age' => $data['age'],
            'sex' => $data['sex'],
            'location' => $data['location'],
            'description' => $data['description'],
            'picture' => $fileBlob ?? $animal['picture'], // Retain existing picture if none is uploaded
        ];

        if ($this->animalModel->update($id, $animalData)) {
            return $this->response->setJSON(['message' => 'Animal updated successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Failed to update animal'])->setStatusCode(500);
        }
    }


    public function __construct()
    {
        $this->animalModel = new AnimalModel();
    }
}
