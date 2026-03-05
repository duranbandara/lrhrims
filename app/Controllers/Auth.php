<?php

namespace App\Controllers;

use App\Models\MainModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected MainModel $model;

    public function __construct()
    {
        $this->model = new MainModel();
        helper(['form', 'url']);
    }

    private function isLoggedIn(): bool
    {
        return (bool) session()->get('logged_in');
    }

    public function index()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('dashboard'));
        }

        file_put_contents(WRITEPATH . 'logs/auth_debug.txt', date('H:i:s') . ' method=' . $this->request->getMethod() . ' uri=' . $this->request->getUri()->getPath() . PHP_EOL, FILE_APPEND);
        if (strtolower($this->request->getMethod()) === 'post') {
            file_put_contents(WRITEPATH . 'logs/auth_debug.txt', date('H:i:s') . ' IN POST BLOCK' . PHP_EOL, FILE_APPEND);
            log_message('debug', 'AUTH: POST received, method=' . $this->request->getMethod());
            $rules = [
                'username' => 'required|trim',
                'password' => 'required|trim',
            ];

            if (! $this->validate($rules)) {
                log_message('debug', 'AUTH: Validation failed');
                return view('templates/auth', [
                    'title'    => 'Login Panel',
                    'content'  => view('auth/login', ['validation' => $this->validator]),
                ]);
            }

            $username = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');
            log_message('debug', 'AUTH: Looking up user: ' . $username);

            $user = $this->model->getRow('user', ['username' => $username]);

            if (empty($user)) {
                log_message('debug', 'AUTH: User not found: ' . $username);
                return redirect()->to(base_url('login'))->with('error', 'Username not registered.');
            }

            log_message('debug', 'AUTH: User found, verifying password, is_active=' . $user['is_active']);

            if (! password_verify($password, $user['password'])) {
                log_message('debug', 'AUTH: Wrong password for user: ' . $username);
                return redirect()->to(base_url('login'))->with('error', 'Wrong password.');
            }

            if ($user['is_active'] != 1) {
                log_message('debug', 'AUTH: Account inactive for user: ' . $username);
                return redirect()->to(base_url('login'))->with('error', 'Your account is not active. Please contact administrator.');
            }

            log_message('debug', 'AUTH: Login successful for user: ' . $username . ', setting session');
            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id_user'],
                'role'      => $user['role'],
                'des'       => $user['des'],
                'photo'     => $user['photo'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'no_telp'   => $user['no_telp'],
                'password'  => $user['password'],
            ]);

            log_message('debug', 'AUTH: Redirecting to dashboard, session logged_in=' . session()->get('logged_in'));
            return redirect()->to(base_url('dashboard'));
        }

        return view('templates/auth', [
            'title'   => 'Login Panel',
            'content' => view('auth/login'),
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('message', 'Logged out successfully!');
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('dashboard'));
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username'  => 'required|trim|alpha_numeric|is_unique[user.username]',
                'password'  => 'required|min_length[3]|trim',
                'password2' => 'matches[password]|trim',
                'des'       => 'required|trim',
                'email'     => 'required|trim|valid_email|is_unique[user.email]',
                'no_telp'   => 'required|trim',
            ];

            if (! $this->validate($rules)) {
                return view('templates/auth', [
                    'title'   => 'Create Account',
                    'content' => view('auth/register', ['validation' => $this->validator]),
                ]);
            }

            $data = [
                'username'   => trim($this->request->getPost('username')),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'des'        => trim($this->request->getPost('des')),
                'email'      => trim($this->request->getPost('email')),
                'no_telp'    => trim($this->request->getPost('no_telp')),
                'role'       => 'user',
                'photo'      => 'user.png',
                'is_active'  => 0,
                'created_at' => time(),
            ];

            if ($this->model->insertRow('user', $data)) {
                return redirect()->to(base_url('login'))->with('message', 'Registration successful! Please contact admin to activate your account.');
            }
            return redirect()->to(base_url('register'))->with('error', 'Something went wrong.');
        }

        return view('templates/auth', [
            'title'   => 'Create Account',
            'content' => view('auth/register'),
        ]);
    }
}
