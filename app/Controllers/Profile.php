<?php

namespace App\Controllers;

class Profile extends BaseAppController
{
    public function index(): string
    {
        $userId = session()->get('user_id');
        return $this->render('profile/index', [
            'title' => 'Profile',
            'user'  => $this->model->getRow('user', ['id_user' => $userId]),
        ]);
    }

    public function setting()
    {
        $userId = session()->get('user_id');
        $user   = $this->model->getRow('user', ['id_user' => $userId]);

        if ($thisstrtolower(->request->getMethod()) === 'post') {
            $username     = $this->request->getPost('username');
            $email        = $this->request->getPost('email');
            $uniqUsername = ($user['username'] === $username) ? '' : '|is_unique[user.username]';
            $uniqEmail    = ($user['email'] === $email)       ? '' : '|is_unique[user.email]';

            $rules = [
                'username' => 'required|trim|alpha_numeric' . $uniqUsername,
                'email'    => 'required|trim|valid_email' . $uniqEmail,
                'des'      => 'required|trim',
                'no_telp'  => 'required|trim|numeric',
            ];

            if (! $this->validate($rules)) {
                return $this->render('profile/setting', [
                    'title'      => 'Profile Settings',
                    'user'       => $user,
                    'validation' => $this->validator,
                ]);
            }

            $data = [
                'username' => $username,
                'email'    => $email,
                'des'      => $this->request->getPost('des'),
                'no_telp'  => $this->request->getPost('no_telp'),
            ];

            $photo = $this->request->getFile('photo');
            if ($photo && $photo->isValid() && ! $photo->hasMoved()) {
                $newName = $photo->getRandomName();
                $photo->move(FCPATH . 'assets/img/avatar', $newName);

                $oldPhoto = $user['photo'];
                if ($oldPhoto !== 'user.png' && file_exists(FCPATH . 'assets/img/avatar/' . $oldPhoto)) {
                    unlink(FCPATH . 'assets/img/avatar/' . $oldPhoto);
                }
                $data['photo'] = $newName;
                session()->set('photo', $newName);
            }

            if ($this->model->updateRow('user', 'id_user', $userId, $data)) {
                session()->set([
                    'des'      => $data['des'],
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'no_telp'  => $data['no_telp'],
                ]);
                return redirect()->to(base_url('profile/setting'))->with('message', 'Changes saved.');
            }
            return redirect()->to(base_url('profile/setting'))->with('error', 'Changes not saved.');
        }

        return $this->render('profile/setting', [
            'title' => 'Profile Settings',
            'user'  => $user,
        ]);
    }

    public function changePassword()
    {
        if ($thisstrtolower(->request->getMethod()) === 'post') {
            $rules = [
                'old_password'     => 'required|trim',
                'new_password'     => 'required|trim|min_length[3]|differs[old_password]',
                'confirm_password' => 'matches[new_password]',
            ];

            if (! $this->validate($rules)) {
                return $this->render('profile/changepassword', [
                    'title'      => 'Change Password',
                    'validation' => $this->validator,
                ]);
            }

            $oldPassword = $this->request->getPost('old_password');
            $currentHash = session()->get('password');

            if (! password_verify($oldPassword, $currentHash)) {
                return redirect()->to(base_url('profile/changepassword'))->with('error', 'Your current password is incorrect!');
            }

            $newHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
            $userId  = session()->get('user_id');

            if ($this->model->updateRow('user', 'id_user', $userId, ['password' => $newHash])) {
                session()->set('password', $newHash);
                return redirect()->to(base_url('profile/changepassword'))->with('message', 'Password updated successfully!');
            }
            return redirect()->to(base_url('profile/changepassword'))->with('error', 'Something went wrong.');
        }

        return $this->render('profile/changepassword', ['title' => 'Change Password']);
    }
}
