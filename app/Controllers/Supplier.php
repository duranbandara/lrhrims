<?php

namespace App\Controllers;

class Supplier extends BaseAppController
{
    private array $rules = [
        'des_supplier' => 'required|trim',
        'no_telp'      => 'required|trim|numeric',
        'address'      => 'required|trim',
    ];

    public function index(): string
    {
        return $this->render('supplier/index', [
            'title'    => 'Supplier',
            'supplier' => $this->model->getAll('supplier'),
        ]);
    }

    public function add()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('supplier/add', [
                    'title'      => 'Add Supplier',
                    'validation' => $this->validator,
                ]);
            }
            $data = $this->request->getPost(['des_supplier', 'no_telp', 'address']);
            if ($this->model->insertRow('supplier', $data)) {
                $this->log('create', 'Supplier', 'Added supplier: ' . $data['des_supplier']);
                return redirect()->to(base_url('supplier'))->with('message', 'Data saved.');
            }
            return redirect()->to(base_url('supplier/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('supplier/add', ['title' => 'Add Supplier']);
    }

    public function edit(int $id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('supplier/edit', [
                    'title'      => 'Edit Supplier',
                    'supplier'   => $this->model->getRow('supplier', ['id_supplier' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = $this->request->getPost(['des_supplier', 'no_telp', 'address']);
            if ($this->model->updateRow('supplier', 'id_supplier', $id, $data)) {
                $this->log('update', 'Supplier', 'Updated supplier ID: ' . $id . ' — ' . $data['des_supplier']);
                return redirect()->to(base_url('supplier'))->with('message', 'Data updated.');
            }
            return redirect()->to(base_url("supplier/edit/{$id}"))->with('error', 'Something went wrong.');
        }
        return $this->render('supplier/edit', [
            'title'    => 'Edit Supplier',
            'supplier' => $this->model->getRow('supplier', ['id_supplier' => $id]),
        ]);
    }

    public function delete(int $id)
    {
        $target = $this->model->getRow('supplier', ['id_supplier' => $id]);
        if ($this->model->deleteRow('supplier', 'id_supplier', $id)) {
            $this->log('delete', 'Supplier', 'Deleted supplier: ' . ($target['des_supplier'] ?? $id));
            return redirect()->to(base_url('supplier'))->with('message', 'Data deleted.');
        }
        return redirect()->to(base_url('supplier'))->with('error', 'Something went wrong.');
    }
}
