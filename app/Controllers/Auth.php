<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function attemptLogin()
    {
        $email = trim($this->request->getPost('email') ?? '');
        $password = (string)$this->request->getPost('password');

        $user = $this->users->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email atau password salah.')->withInput();
        }

        session()->regenerate(true);
        session()->set('user_id', $user['id']);
        session()->set('user_name', $user['full_name'] ?? $user['username'] ?? 'User');

        return redirect()->to(base_url());
    }

    public function attemptRegister()
    {
        $fullName = trim($this->request->getPost('name') ?? '');
        $usernameInput = trim($this->request->getPost('username') ?? '');
        $email = trim($this->request->getPost('email') ?? '');
        $password = (string)$this->request->getPost('password');
        $confirm = (string)$this->request->getPost('password_confirm');

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.')->withInput();
        }

        if ($this->users->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Email sudah terdaftar.')->withInput();
        }

        // Use provided username or derive from email prefix
        $username = $usernameInput !== '' ? $usernameInput : (strstr($email, '@', true) ?: $email);

        $this->users->insert([
            'full_name' => $fullName,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
}
