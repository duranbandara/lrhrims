<?php

namespace App\Controllers;

class Unit extends BaseAppController
{
    private array $rules = ['des_unit' => 'required|trim'];

    public function index(): string
    {
        return $this->render('unit/index', [
            'title' => 'Goods Unit',
            'unit'  => $this->model->getAll('unit'),
        ]);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('unit/add', ['title' => 'Add Unit', 'validation' => $this->validator]);
            }
            $data = ['des_unit' => $this->request->getPost('des_unit')];
            if ($this->model->insertRow('unit', $data)) {
                return redirect()->to(base_url('unit'))->with('message', 'Data saved.');
            }
            return redirect()->to(base_url('unit/add'))->with('error', 'Data failed to save.');
        }
        return $this->render('unit/add', ['title' => 'Add Unit']);
    }

    public function edit(int $id)
    {
        if ($this->request->getMethod() === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('unit/edit', [
                    'title'      => 'Edit Unit',
                    'unit'       => $this->model->getRow('unit', ['id_unit' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = ['des_unit' => $this->request->getPost('des_unit')];
            if ($this->model->updateRow('unit', 'id_unit', $id, $data)) {
                return redirect()->to(base_url('unit'))->with('message', 'Data updated.');
            }
            return redirect()->to(base_url("unit/edit/{$id}"))->with('error', 'Something went wrong.');
        }
        return $this->render('unit/edit', [
            'title' => 'Edit Unit',
            'unit'  => $this->model->getRow('unit', ['id_unit' => $id]),
        ]);
    }

    public function delete(int $id)
    {
        if ($this->model->deleteRow('unit', 'id_unit', $id)) {
            return redirect()->to(base_url('unit'))->with('message', 'Data deleted.');
        }
        return redirect()->to(base_url('unit'))->with('error', 'Something went wrong.');
    }
}
