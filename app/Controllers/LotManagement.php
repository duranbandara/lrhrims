<?php

namespace App\Controllers;

class LotManagement extends BaseAppController
{
    public function index()
    {
        if (! is_admin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        $reagentId       = $this->request->getGet('reagent_id');
        $lots            = [];
        $selectedReagent = [];

        if ($reagentId) {
            $lots            = $this->model->getAllLotsForReagent($reagentId);
            $selectedReagent = $this->model->getRow('items', ['id_item' => $reagentId]);
        }

        return $this->render('lot_management/index', [
            'title'           => 'Lot Management',
            'items'           => $this->model->getGoods(),
            'lots'            => $lots,
            'reagentId'       => $reagentId,
            'selectedReagent' => $selectedReagent,
        ]);
    }

    public function deleteLot(string $lotId)
    {
        if (! is_admin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        $lot = $this->model->getRow('reagent_lots', ['id_lot' => $lotId]);
        if (empty($lot)) {
            return redirect()->to(base_url('lotmanagement'))->with('error', 'Lot not found.');
        }

        $reagent   = $this->model->getRow('items', ['id_item' => $lot['reagent_id']]);
        $reason    = trim($this->request->getGet('reason') ?? '');
        $redirectTo = base_url('lotmanagement?reagent_id=' . $lot['reagent_id']);

        // Log the deletion first
        $this->model->insertRow('lot_deletion_logs', [
            'lot_id'          => (int) $lot['id_lot'],
            'lot_number'      => $lot['lot_number'],
            'reagent_id'      => $lot['reagent_id'],
            'reagent_name'    => $reagent['des_item'] ?? 'Unknown',
            'expiry_date'     => $lot['expiry_date'] ?: null,
            'quantity_deleted'=> (float) $lot['quantity'],
            'deleted_by_id'   => session()->get('user_id'),
            'deleted_by_name' => session()->get('des'),
            'reason'          => $reason !== '' ? $reason : 'No reason provided',
            'deleted_at'      => date('Y-m-d H:i:s'),
        ]);

        // Deduct remaining quantity from master stock
        if ((float) $lot['quantity'] > 0) {
            $newStock = max(0, (int)($reagent['stock'] ?? 0) - (int) $lot['quantity']);
            $this->model->updateRow('items', 'id_item', $lot['reagent_id'], ['stock' => $newStock]);
        }

        // Delete the lot
        if ($this->model->deleteRow('reagent_lots', 'id_lot', $lotId)) {
            $this->log('delete', 'Lot Management', 'Deleted lot: ' . $lot['lot_number'] . ' for ' . ($reagent['des_item'] ?? $lot['reagent_id']) . ', qty: ' . $lot['quantity'] . ', reason: ' . $reason);
            return redirect()->to($redirectTo)->with('message', 'Lot ' . esc($lot['lot_number']) . ' deleted and logged successfully.');
        }

        return redirect()->to($redirectTo)->with('error', 'Something went wrong while deleting the lot.');
    }
}
