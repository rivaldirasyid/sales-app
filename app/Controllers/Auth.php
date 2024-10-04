<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login', ['title' => 'Login']);
    }

    public function loginAction()
    {
        helper('cookie');

        // Ambil input username dan password
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Cari pengguna berdasarkan username
        $model = new UserModel();
        $user = $model->where('username', $username)->first();

        if ($user) {
            // Cek password hash
            if (password_verify($password, $user['password_hash'])) {
                // Set session pengguna
                $this->setUserSession($user);

                // Jika opsi 'Remember Me' dipilih, set token remember
                if ($remember) {
                    $this->setRememberMe($user);
                }
                session()->setFlashdata('success', 'Login Successfully.');
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('error', 'Password is incorrect.');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Username does not exist.');
            return redirect()->back()->withInput();
        }
    }

    private function setUserSession($user)
    {
        // Set session untuk pengguna yang berhasil login
        $data = [
            'user_id' => $user['user_id'], // Pastikan user_id tersimpan di session
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role'], // Simpan role pengguna
            'isLoggedIn' => true,
        ];

        session()->set($data);
        return true;
    }


    private function setRememberMe($user)
    {
        helper('cookie'); // Pastikan helper cookie sudah di-load

        // Generate token unik
        $token = bin2hex(random_bytes(16));

        // Simpan token di database
        $model = new UserModel();

        // Update data pengguna dengan token remember
        $dataToUpdate = [
            'remember_token' => $token
        ];

        $model->update($user['user_id'], $dataToUpdate);

        // Set cookie remember_token di browser
        set_cookie('remember_token', $token, 3600 * 24 * 30); // 30 hari
    }

    public function register()
    {
        return view('auth/register', ['title' => 'Register']);
    }

    public function registerAction()
    {
        helper(['form', 'url']); // Load helper form dan URL

        // Ambil input dari form
        $username = $this->request->getPost('username');
        $full_name = $this->request->getPost('full_name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = 'Sales'; // Default role saat pendaftaran
        $terms = $this->request->getPost('terms');

        // Validasi input
        if (!$terms) {
            session()->setFlashdata('error', 'You must agree to the terms & conditions.');
            return redirect()->back()->withInput();
        }

        // Validasi form
        if (
            !$this->validate([
                'username' => 'required|min_length[3]|max_length[20]',
                'full_name' => 'required|min_length[3]|max_length[50]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]|max_length[255]',
            ])
        ) {
            // Ambil pesan error dari validator
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        // Simpan pengguna baru ke database
        $userModel = new UserModel();
        $userData = [
            'username' => $username,
            'full_name' => $full_name,
            'email' => $email,
            'password_hash' => $password,
            'role' => $role  // Default role adalah Sales
        ];

        if ($userModel->save($userData)) {
            return redirect()->to('/auth/login')->with('success', 'Your account has been created successfully.');
        } else {
            session()->setFlashdata('error', 'Registration failed!');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        helper('cookie');

        // Hapus cookie remember_token saat logout
        delete_cookie('remember_token');

        // Hapus remember_token dari database
        $userId = session()->get('id');
        if ($userId) {
            $model = new UserModel();
            $model->update($userId, ['remember_token' => null]);
        }

        // Hapus sesi saat logout
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
