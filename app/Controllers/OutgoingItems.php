<?php

namespace App\Controllers;

class OutgoingItems extends BaseAppController
{
    public function index(): string
    {
        return $this->render('outgoing_items/index', [
            'title'         => 'Issue to Lab Section',
            'outgoingitems' => $this->model->getOutgoingItems(),
        ]);
    }

    public function add()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $itemId    = $this->request->getPost('item_id');
            $lotNumber = $this->request->getPost('lot_number');

            $rules = [
                'section_id' => 'required|integer',
                'date_out'   => 'required|trim',
                'item_id'    => 'required',
                'lot_number' => 'required|trim',
                'amount_out' => 'required|trim|integer|greater_than[0]',
            ];

            if ($itemId && $lotNumber) {
                $lot    = $this->model->getLotByNumber($itemId, $lotNumber);
                $lotQty = $lot ? (int)$lot['quantity'] : 0;
                $max    = $lotQty + 1;
                $rules['amount_out'] = "required|trim|integer|greater_than[0]|less_than[{$max}]";
            }

            if (! $this->validate($rules)) {
                return $this->render('outgoing_items/add', $this->_addData($itemId) + [
                    'title'      => 'Issue Reagent to Section',
                    'validation' => $this->validator,
                ]);
            }

            $idItemOut = $this->request->getPost('id_item_out');
            $amountOut = (int)$this->request->getPost('amount_out');

            $header = [
                'id_item_out' => $idItemOut,
                'user_id'     => session()->get('user_id'),
                'date_out'    => $this->request->getPost('date_out'),
                'section_id'  => $this->request->getPost('section_id'),
            ];

            if (! $this->model->insertRow('item_out', $header)) {
                return redirect()->to(base_url('outgoingitems/add'))->with('error', 'Something went wrong saving the issue record!');
            }

            $detail = [
                'id_item_out' => $idItemOut,
                'item_id'     => $itemId,
                'amount_out'  => $amountOut,
                'lot_number'  => $lotNumber,
            ];
            $this->model->insertRow('item_out_dtl', $detail);

            $this->log('create', 'Outgoing Items', 'Issued reagent: ' . $amountOut . ' units, Lot: ' . $lotNumber . ', Item ID: ' . $itemId . ', Ref: ' . $idItemOut);
            return redirect()->to(base_url('outgoingitems'))->with('message', 'Reagent issued successfully!');
        }

        return $this->render('outgoing_items/add', ['title' => 'Issue Reagent to Section'] + $this->_addData());
    }

    private function _addData(?string $postedItemId = null): array
    {
        $code       = 'S' . date('ymd');
        $lastCode   = $this->model->getMax('item_out', 'id_item_out', $code);
        $codeIncr   = (int)substr((string)$lastCode, -4) + 1;
        $number     = str_pad((string)$codeIncr, 4, '0', STR_PAD_LEFT);

        $defaultSection = $this->model->getRow('lab_sections', ['d_status' => 1]);

        return [
            'sections'           => $this->model->getLabSections(),
            'default_section_id' => $defaultSection ? $defaultSection['id_section'] : null,
            'items'              => $this->model->getGoods(),
            'id_item_out'        => $code . $number,
            'selected_item'      => $postedItemId ? $this->model->getRow('items', ['id_item' => $postedItemId]) : null,
            'selected_lots'      => $postedItemId ? $this->model->getLotsByReagent($postedItemId) : [],
        ];
    }

    public function delete(string $id)
    {
        $details = $this->model->getOutgoingItemDetails($id);
        foreach ($details as $d) {
            $this->model->deleteRow('item_out_dtl', 'id_detail', $d['id_detail']);
        }

        if ($this->model->deleteRow('item_out', 'id_item_out', $id)) {
            $this->log('delete', 'Outgoing Items', 'Deleted issue record ID: ' . $id);
            return redirect()->to(base_url('outgoingitems'))->with('message', 'Issue record deleted and stock restored!');
        }
        return redirect()->to(base_url('outgoingitems'))->with('error', 'Something went wrong.');
    }

    public function scan()
    {
        $qr = $this->request->getPost('qr');
        if (! $qr) {
            return $this->response->setJSON(['success' => false, 'message' => 'No QR data']);
        }

        $parts    = explode('|', $qr);
        $itemCode = trim($parts[0]);

        $reagent = $this->model->getReagentByItemCode($itemCode);
        if (empty($reagent)) {
            $reagent = $this->model->getRow('items', ['id_item' => $itemCode]);
            if (empty($reagent)) {
                return $this->response->setJSON(['success' => false, 'message' => "Reagent not found for item code: {$itemCode}"]);
            }
        }

        $lots = $this->model->getLotsByReagent($reagent['id_item']);

        return $this->response->setJSON([
            'success'       => true,
            'reagent_id'    => $reagent['id_item'],
            'reagent_name'  => $reagent['des_item'],
            'item_code'     => $reagent['item_code'],
            'current_stock' => (int)$reagent['stock'],
            'min_stock'     => (int)$reagent['min_stock'],
            'unit'          => $reagent['des_unit'] ?? '',
            'lots'          => $lots,
        ]);
    }

    public function fefoPreview()
    {
        $itemId   = $this->request->getPost('item_id');
        $quantity = (int)$this->request->getPost('quantity');

        if (! $itemId || $quantity <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid parameters']);
        }

        $lots = $this->model->getFEFOLots($itemId, $quantity);
        return $this->response->setJSON(['success' => true, 'lots' => $lots]);
    }
}
