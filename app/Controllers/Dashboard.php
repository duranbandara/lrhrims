<?php

namespace App\Controllers;

class Dashboard extends BaseAppController
{
    public function index(): string
    {
        $data['title'] = 'Dashboard';

        $data['items']    = $this->model->countAll('items');
        $data['in_items'] = $this->model->countAll('item_in');
        $data['out_items']= $this->model->countAll('item_out');
        $data['sections'] = $this->model->countAll('lab_sections');

        $data['low_stock']     = $this->model->getLowStockReagents();
        $data['expiring_soon'] = $this->model->getExpiringReagents(30);
        $data['expiry_list']   = $this->model->getReagentsByExpiryOrder();

        $data['transaction'] = [
            'in_items'  => $this->model->getIncomingItems(5),
            'out_items' => $this->model->getOutgoingItemsDashboard(5),
        ];

        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $data['cii'] = [];
        $data['coi'] = [];
        foreach ($months as $m) {
            $data['cii'][] = $this->model->chartIncoming($m);
            $data['coi'][] = $this->model->chartOutgoing($m);
        }

        return $this->render('dashboard/index', $data);
    }
}
