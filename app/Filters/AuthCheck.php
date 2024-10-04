<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Load cookie helper
        helper('cookie');

        // Cek jika pengguna belum login
        if (!session()->get('isLoggedIn')) {
            // Cek apakah ada remember token di cookie
            $rememberToken = get_cookie('remember_token');

            if ($rememberToken) {
                // Cari pengguna berdasarkan remember token
                $model = new UserModel();
                $user = $model->where('remember_token', $rememberToken)->first();

                if ($user) {
                    // Set ulang session jika token valid
                    $sessionData = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'isLoggedIn' => true,
                    ];

                    session()->set($sessionData);
                    return true;
                }
            }

            // Jika tidak ada sesi atau token, redirect ke halaman login
            return redirect()->to('/auth/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something after the request
    }
}
