<?php

namespace App\Controllers;

class User extends BaseAppController
{
    public function index(): string
    {
        $userId = session()->get('user_id');
        return $this->render('user/index', [
            'title' => 'User Management',
            'users' => $this->model->getUsers($userId),
        ]);
    }

    public function add()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username'  => 'required|trim|alpha_numeric|is_unique[user.username]',
                'password'  => 'required|min_length[3]|trim',
                'des'       => 'required|trim',
                'email'     => 'required|trim|valid_email|is_unique[user.email]',
                'no_telp'   => 'required|trim',
                'role'      => 'required|in_list[user,admin]',
            ];

            if (! $this->validate($rules)) {
                return $this->render('user/add', ['title' => 'Add User', 'validation' => $this->validator]);
            }

            $data = [
                'username'   => $this->request->getPost('username'),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'des'        => $this->request->getPost('des'),
                'email'      => $this->request->getPost('email'),
                'no_telp'    => $this->request->getPost('no_telp'),
                'role'       => $this->request->getPost('role'),
                'photo'      => 'user.png',
                'is_active'  => 1,
                'created_at' => time(),
            ];

            if ($this->model->insertRow('user', $data)) {
                $this->log('create', 'User Management', 'Added user: ' . $data['des'] . ' (' . $data['username'] . ') role: ' . $data['role']);
                return redirect()->to(base_url('user'))->with('message', 'User added successfully!');
            }
            return redirect()->to(base_url('user/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('user/add', ['title' => 'Add User']);
    }

    public function edit(int $id)
    {
        $userRecord = $this->model->getRow('user', ['id_user' => $id]);

        if (strtolower($this->request->getMethod()) === 'post') {
            $username     = $this->request->getPost('username');
            $email        = $this->request->getPost('email');
            $uniqUsername = ($userRecord['username'] === $username) ? '' : '|is_unique[user.username]';
            $uniqEmail    = ($userRecord['email'] === $email) ? '' : '|is_unique[user.email]';

            $rules = [
                'username' => 'required|trim|alpha_numeric' . $uniqUsername,
                'email'    => 'required|trim|valid_email' . $uniqEmail,
                'des'      => 'required|trim',
                'no_telp'  => 'required|trim',
                'role'     => 'required|in_list[user,admin]',
                'is_active'=> 'required|in_list[0,1]',
            ];

            if (! $this->validate($rules)) {
                return $this->render('user/edit', [
                    'title'      => 'Edit User',
                    'user'       => $userRecord,
                    'validation' => $this->validator,
                ]);
            }

            $data = [
                'username'  => $username,
                'email'     => $email,
                'des'       => $this->request->getPost('des'),
                'no_telp'   => $this->request->getPost('no_telp'),
                'role'      => $this->request->getPost('role'),
                'is_active' => $this->request->getPost('is_active'),
            ];

            if ($this->model->updateRow('user', 'id_user', $id, $data)) {
                $this->log('update', 'User Management', 'Updated user ID: ' . $id . ' (' . $data['username'] . ') active: ' . $data['is_active'] . ' role: ' . $data['role']);
                return redirect()->to(base_url('user'))->with('message', 'User updated!');
            }
            return redirect()->to(base_url("user/edit/{$id}"))->with('error', 'Something went wrong.');
        }

        return $this->render('user/edit', [
            'title' => 'Edit User',
            'user'  => $userRecord,
        ]);
    }

    public function delete(int $id)
    {
        $target = $this->model->getRow('user', ['id_user' => $id]);
        if ($this->model->deleteRow('user', 'id_user', $id)) {
            $this->log('delete', 'User Management', 'Deleted user: ' . ($target['des'] ?? '') . ' (' . ($target['username'] ?? $id) . ')');
            return redirect()->to(base_url('user'))->with('message', 'User deleted.');
        }
        return redirect()->to(base_url('user'))->with('error', 'Something went wrong.');
    }
}
