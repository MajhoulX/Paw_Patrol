<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Session\Session;

class AuthController extends BaseController
{
    public function signup()
{
    $data = $this->request->getPost();
    log_message('info', 'Received signup data: ' . json_encode($data));

    $validation = \Config\Services::validation();
    if (!$this->validate([
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'name' => 'required',
        'surname' => 'required',
    ])) {
        log_message('error', 'Validation errors: ' . json_encode($validation->getErrors()));
        return $this->response->setStatusCode(400)->setJSON([
            'message' => 'Validation failed',
            'errors' => $validation->getErrors(),
        ]);
    }

    $data['role'] = 'user';
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

    $userModel = new UserModel();
    try {
        
        $userModel->insert($data);
        log_message('info', 'User successfully registered: ' . $data['email']);
    } catch (\Exception $e) {
        log_message('error', 'Database error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'message' => 'A server error occurred while processing your request.',
        ]);
    }

    return $this->response->setStatusCode(200)->setJSON([
        'message' => 'User successfully registered',
        'role' => $data['role'],
    ]);
}




    public function login()
    {
        $data = $this->request->getPost();

        if (empty($data)) {
            $data = json_decode($this->request->getBody(), true);
        }

        log_message('info', 'Login payload: ' . json_encode($data));

        if (!$data || !isset($data['email']) || !isset($data['password'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Email and password are required.',
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $data['email'])->first();

        // Log the user data fetched from the database
        log_message('info', 'Fetched user: ' . json_encode($user));

        if ($user && password_verify($data['password'], $user['password'])) {
            session()->set([
                'email' => $user['email'],
                'role' => $user['role'],
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => [
                    'email' => $user['email'],
                    'role' => $user['role'],
                ],
            ]);
        } else {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Invalid email or password.',
            ]);
        }
    }
    

    public function logout()
    {
        session()->remove(['email', 'role']);
        session()->destroy();
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function assignAdmin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $user['role'] = 'admin';
            $userModel->save($user);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => "User {$email} is now an admin.",
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not found.',
            ]);
        }
    }
    public function hashPasswordForUser()
{
    $email = 'yerrouihel@gmail.com'; // Replace with the target email
    $plainPassword = 'yassir00'; // Replace with the plain password

    $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

    $userModel = new \App\Models\UserModel();
    $userModel->update($email, ['password' => $hashedPassword]);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Password updated successfully for ' . $email,
    ]);
}

}
