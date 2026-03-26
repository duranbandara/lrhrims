<?php

namespace App\Controllers;

class Goods extends BaseAppController
{
    private array $rules = [
        'des_item'  => 'required|trim',
        'type_id'   => 'required',
        'unit_id'   => 'required',
        'min_stock' => 'required|integer|greater_than[0]',
    ];

    public function index(): string
    {
        return $this->render('goods/index', [
            'title' => 'Reagent List',
            'items' => $this->model->getGoods(),
        ]);
    }

    public function add()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('goods/add', $this->_formData() + [
                    'title'      => 'Add Reagent',
                    'validation' => $this->validator,
                ]);
            }
            $data = [
                'id_item'   => $this->request->getPost('id_item'),
                'des_item'  => $this->request->getPost('des_item'),
                'item_code' => $this->request->getPost('item_code'),
                'type_id'   => $this->request->getPost('type_id'),
                'unit_id'   => $this->request->getPost('unit_id'),
                'min_stock' => $this->request->getPost('min_stock'),
                'stock'     => 0,
                'price'     => 0,
            ];
            if ($this->model->insertRow('items', $data)) {
                $this->log('create', 'Reagents', 'Added reagent: ' . $data['des_item'] . ' (' . $data['item_code'] . ')');
                return redirect()->to(base_url('goods'))->with('message', 'Reagent saved successfully!');
            }
            return redirect()->to(base_url('goods/add'))->with('error', 'Something went wrong.');
        }
        return $this->render('goods/add', ['title' => 'Add Reagent'] + $this->_formData());
    }

    public function edit(string $id)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return $this->render('goods/edit', $this->_formData() + [
                    'title'      => 'Edit Reagent',
                    'item'       => $this->model->getRow('items', ['id_item' => $id]),
                    'validation' => $this->validator,
                ]);
            }
            $data = [
                'des_item'  => $this->request->getPost('des_item'),
                'item_code' => $this->request->getPost('item_code'),
                'type_id'   => $this->request->getPost('type_id'),
                'unit_id'   => $this->request->getPost('unit_id'),
                'min_stock' => $this->request->getPost('min_stock'),
            ];
            if ($this->model->updateRow('items', 'id_item', $id, $data)) {
                $this->log('update', 'Reagents', 'Updated reagent ID: ' . $id . ' — ' . $data['des_item']);
                return redirect()->to(base_url('goods'))->with('message', 'Data saved successfully!');
            }
            return redirect()->to(base_url("goods/edit/{$id}"))->with('error', 'Something went wrong.');
        }
        return $this->render('goods/edit', [
            'title' => 'Edit Reagent',
            'item'  => $this->model->getRow('items', ['id_item' => $id]),
        ] + $this->_formData());
    }

    public function delete(string $id)
    {
        if ($this->model->deleteRow('items', 'id_item', $id)) {
            $this->log('delete', 'Reagents', 'Deleted reagent ID: ' . $id);
            return redirect()->to(base_url('goods'))->with('message', 'Data deleted!');
        }
        return redirect()->to(base_url('goods'))->with('error', 'Something went wrong.');
    }

    public function checkstock(string $id)
    {
        return $this->response->setJSON($this->model->checkStock($id));
    }

    private function _formData(): array
    {
        $lastCode   = $this->model->getMax('items', 'id_item');
        $codeIncr   = (int)substr((string)$lastCode, -4) + 1;
        $number     = str_pad((string)$codeIncr, 4, '0', STR_PAD_LEFT);

        return [
            'type'    => $this->model->getAll('type'),
            'unit'    => $this->model->getAll('unit'),
            'id_item' => 'RG' . $number,
        ];
    }
}
