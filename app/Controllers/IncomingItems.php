<?php

namespace App\Controllers;

class IncomingItems extends BaseAppController
{
    public function index(): string
    {
        return $this->render('incoming_items/index', [
            'title'        => 'Receive Reagents',
            'incomingitems'=> $this->model->getIncomingItems(),
        ]);
    }

    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'date_in'    => 'required|trim',
                'item_id'    => 'required',
                'amount_in'  => 'required|trim|integer|greater_than[0]',
                'lot_number' => 'required|trim',
                'expiry_date'=> 'required|trim',
            ];

            if (! $this->validate($rules)) {
                return $this->render('incoming_items/add', $this->_addData() + [
                    'title'      => 'Receive Reagent',
                    'validation' => $this->validator,
                ]);
            }

            $data = [
                'id_item_in'  => $this->request->getPost('id_item_in'),
                'user_id'     => session()->get('user_id'),
                'date_in'     => $this->request->getPost('date_in'),
                'supplier_id' => $this->request->getPost('supplier_id') ?: null,
                'item_id'     => $this->request->getPost('item_id'),
                'amount_in'   => $this->request->getPost('amount_in'),
                'lot_number'  => $this->request->getPost('lot_number'),
                'expiry_date' => $this->request->getPost('expiry_date'),
            ];

            if ($this->model->insertRow('item_in', $data)) {
                return redirect()->to(base_url('incomingitems'))->with('message', 'Reagent received successfully!');
            }
            return redirect()->to(base_url('incomingitems/add'))->with('error', 'Oops, something went wrong!');
        }

        return $this->render('incoming_items/add', ['title' => 'Receive Reagent'] + $this->_addData());
    }

    private function _addData(): array
    {
        $code     = 'I' . date('ymd');
        $codeLast = $this->model->getMax('item_in', 'id_item_in', $code);
        $codeAdd  = (int)substr((string)$codeLast, -4) + 1;
        $number   = str_pad((string)$codeAdd, 4, '0', STR_PAD_LEFT);

        return [
            'supplier'   => $this->model->getAll('supplier'),
            'items'      => $this->model->getGoods(),
            'id_item_in' => $code . $number,
        ];
    }

    public function delete(string $id)
    {
        if ($this->model->deleteRow('item_in', 'id_item_in', $id)) {
            return redirect()->to(base_url('incomingitems'))->with('message', 'Record deleted.');
        }
        return redirect()->to(base_url('incomingitems'))->with('error', 'Something went wrong.');
    }

    public function scan()
    {
        $qr = $this->request->getPost('qr');
        if (! $qr) {
            return $this->response->setJSON(['success' => false, 'message' => 'No QR data']);
        }

        $parts      = explode('|', $qr);
        $itemCode   = trim($parts[0]);
        $lotNumber  = isset($parts[1]) ? trim($parts[1]) : '';
        $expiryDate = isset($parts[2]) ? trim($parts[2]) : '';

        if (! $itemCode) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item code is empty.']);
        }

        $reagent = $this->model->getReagentByItemCode($itemCode);
        if (empty($reagent)) {
            return $this->response->setJSON(['success' => false, 'message' => "Item code \"{$itemCode}\" not found."]);
        }

        return $this->response->setJSON([
            'success'       => true,
            'reagent_id'    => $reagent['id_item'],
            'reagent_name'  => $reagent['des_item'],
            'item_code'     => $reagent['item_code'],
            'lot_number'    => $lotNumber,
            'expiry_date'   => $expiryDate,
            'current_stock' => $reagent['stock'],
            'unit'          => $reagent['des_unit'],
        ]);
    }
}
