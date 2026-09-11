<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function login()
    {
        if (session()->get('is_logged_in') === true && session()->get('user_role') === 'admin') {
            return redirect()->to(site_url('admin'));
        }

        return view('auth/login', [
            'redirectTo' => (string) (session()->getFlashdata('redirect_to') ?: site_url('admin')),
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email|max_length[190]',
            'password' => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password belum memenuhi format yang benar.');
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $user = $this->users->findAdminByEmail($email);

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            $this->users->update($user['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        session()->regenerate(true);
        session()->set([
            'is_logged_in' => true,
            'user_id' => (int) $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
        ]);

        $redirectTo = (string) $this->request->getPost('redirect_to');
        if (! str_starts_with($redirectTo, site_url())) {
            $redirectTo = site_url('admin');
        }

        return redirect()->to($redirectTo);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah keluar dari CMS.');
    }
}
