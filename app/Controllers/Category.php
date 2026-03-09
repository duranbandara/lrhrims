<?php

namespace App\Controllers;

class Category extends BaseAppController
{
    private array $rules = ['des_type' => 'required|trim'];

    public function index(): string
    {
        return $this->render('category/index', [
            'title' => 'Reagent Categories',
            'type'  => $this->model->getAll('type'),
        ]);
    }

    public function add()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('category/add', ['title' => 'Add Category', 'validation' => $this->validator]);
            }
            $data = ['des_type' => $this->request->getPost('des_type')];
            if ($this->model->insertRow('type', $data)) {
                return redirect()->to(base_url('category'))->with('message', 'Data saved!');
            }
            return redirect()->to(base_url('category/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('category/add', ['title' => 'Add Category']);
    }

    public function edit(int $id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('category/edit', [
                    'title'      => 'Edit Category',
                    'type'       => $this->model->getRow('type', ['id_type' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = ['des_type' => $this->request->getPost('des_type')];
            if ($this->model->updateRow('type', 'id_type', $id, $data)) {
                return redirect()->to(base_url('category'))->with('message', 'Data saved!');
            }
            return redirect()->to(base_url("category/edit/{$id}"))->with('error', 'Something went wrong.');
        }
        return $this->render('category/edit', [
            'title' => 'Edit Category',
            'type'  => $this->model->getRow('type', ['id_type' => $id]),
        ]);
    }

    public function delete(int $id)
    {
        if ($this->model->deleteRow('type', 'id_type', $id)) {
            return redirect()->to(base_url('category'))->with('message', 'Data deleted.');
        }
        return redirect()->to(base_url('category'))->with('error', 'Something went wrong.');
    }
}
