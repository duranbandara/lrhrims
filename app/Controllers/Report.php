<?php

namespace App\Controllers;

class Report extends BaseAppController
{
    public function index()
    {
        $transaction = $this->request->getGet('transaction');
        $date        = $this->request->getGet('date') ?? '';

        if (! $transaction) {
            return $this->render('report/index', ['title' => 'Transaction Report', 'report_scripts' => true]);
        }

        $allowed = ['item_in', 'item_out', 'current_stock', 'lot_deletion_logs', 'activity_logs'];
        if (! in_array($transaction, $allowed)) {
            return $this->render('report/index', [
                'title'          => 'Transaction Report',
                'report_scripts' => true,
                'transaction'    => $transaction,
                'date'           => $date,
                'error'          => 'Invalid report type.',
            ]);
        }

        if ($transaction !== 'current_stock' && trim($date) === '') {
            return $this->render('report/index', [
                'title'          => 'Transaction Report',
                'report_scripts' => true,
                'transaction'    => $transaction,
                'date'           => $date,
                'error'          => 'Please select a date range.',
            ]);
        }

        if ($transaction === 'current_stock') {
            return $this->_printStock($this->model->getCurrentStockByLot());
        }

        $parts = explode(' - ', $date);
        $start = date('Y-m-d', strtotime($parts[0]));
        $end   = date('Y-m-d', strtotime(end($parts)));
        $range = ['start' => $start, 'end' => $end];

        if ($transaction === 'activity_logs') {
            return $this->_printActivityLogs($this->model->getActivityLogs($range), $date);
        }

        if ($transaction === 'lot_deletion_logs') {
            return $this->_printLotDeletionLogs($this->model->getLotDeletionLogs($range), $date);
        }

        if ($transaction === 'item_in') {
            $query = $this->model->getIncomingItems(null, null, $range);
        } else {
            $query = $this->model->getOutgoingItemsDashboard(null, $range);
        }

        return $this->_printTransactions($query, $transaction, $date);
    }

    private function _printActivityLogs(array $data, string $date): \CodeIgniter\HTTP\Response
    {
        require_once APPPATH . 'Libraries/fpdf/fpdf/fpdf.php';

        $actionColors = [
            'login'  => [198, 239, 206],
            'logout' => [220, 220, 220],
            'create' => [198, 224, 240],
            'update' => [255, 235, 156],
            'delete' => [255, 199, 206],
        ];

        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 7, 'Activity Log Report', 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(277, 4, 'Period: ' . $date, 0, 1, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(8,   7, 'No.',         1, 0, 'C', true);
        $pdf->Cell(35,  7, 'Date / Time', 1, 0, 'C', true);
        $pdf->Cell(45,  7, 'User',        1, 0, 'C', true);
        $pdf->Cell(20,  7, 'Role',        1, 0, 'C', true);
        $pdf->Cell(20,  7, 'Action',      1, 0, 'C', true);
        $pdf->Cell(35,  7, 'Module',      1, 0, 'C', true);
        $pdf->Cell(114, 7, 'Description', 1, 1, 'C', true);

        $no = 1;
        foreach ($data as $d) {
            $rgb = $actionColors[$d['action']] ?? [255, 255, 255];
            $pdf->SetFillColor($rgb[0], $rgb[1], $rgb[2]);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(8,   6, $no++ . '.',                                      1, 0, 'C', true);
            $pdf->Cell(35,  6, date('d-m-Y H:i', strtotime($d['created_at'])),  1, 0, 'C', true);
            $pdf->Cell(45,  6, $d['user_name'],                                  1, 0, 'L', true);
            $pdf->Cell(20,  6, $d['role'],                                       1, 0, 'C', true);
            $pdf->Cell(20,  6, strtoupper($d['action']),                         1, 0, 'C', true);
            $pdf->Cell(35,  6, $d['module'],                                     1, 0, 'L', true);
            $pdf->Cell(114, 6, $d['description'],                                1, 1, 'L', true);
        }

        $pdf->Output('I', 'Activity_Log_' . date('Y-m-d') . '.pdf');
        exit;
    }

    private function _printLotDeletionLogs(array $data, string $date): \CodeIgniter\HTTP\Response
    {
        require_once APPPATH . 'Libraries/fpdf/fpdf/fpdf.php';

        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(277, 7, 'Lot Deletion Audit Log', 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(277, 4, 'Period: ' . $date, 0, 1, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(8,  7, 'No.',         1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Deleted At',  1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Reagent',     1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Lot Number',  1, 0, 'C', true);
        $pdf->Cell(22, 7, 'Expiry Date', 1, 0, 'C', true);
        $pdf->Cell(18, 7, 'Qty',         1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Deleted By',  1, 0, 'C', true);
        $pdf->Cell(59, 7, 'Reason',      1, 1, 'C', true);

        $no = 1;
        foreach ($data as $d) {
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(8,  6, $no++ . '.', 1, 0, 'C');
            $pdf->Cell(35, 6, date('d-m-Y H:i', strtotime($d['deleted_at'])), 1, 0, 'C');
            $pdf->Cell(60, 6, $d['reagent_name'], 1, 0, 'L');
            $pdf->Cell(35, 6, $d['lot_number'], 1, 0, 'C');
            $pdf->Cell(22, 6, $d['expiry_date'] ? date('d-m-Y', strtotime($d['expiry_date'])) : '-', 1, 0, 'C');
            $pdf->Cell(18, 6, (float)$d['quantity_deleted'], 1, 0, 'C');
            $pdf->Cell(40, 6, $d['deleted_by_name'], 1, 0, 'L');
            $pdf->Cell(59, 6, $d['reason'], 1, 1, 'L');
        }

        $pdf->Output('I', 'Lot_Deletion_Log_' . date('Y-m-d') . '.pdf');
        exit;
    }

    private function _printTransactions(array $data, string $type, string $date): \CodeIgniter\HTTP\Response
    {
        require_once APPPATH . 'Libraries/fpdf/fpdf/fpdf.php';

        $label = ($type === 'item_in') ? 'Incoming Reagents' : 'Issued Reagents';
        $pdf   = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Report ' . $label, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(190, 4, 'Date: ' . $date, 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 9);

        if ($type === 'item_in') {
            $pdf->Cell(10, 7, 'No.',          1, 0, 'C');
            $pdf->Cell(25, 7, 'Entry Date',   1, 0, 'C');
            $pdf->Cell(70, 7, 'Reagent Name', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Lot Number',   1, 0, 'C');
            $pdf->Cell(35, 7, 'Quantity',     1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 9);
                $y = $pdf->GetY();
                $pdf->SetXY(10 + 35, $y);
                $pdf->MultiCell(70, 5, $d['des_item'], 0, 'L');
                $rowH = max(7, $pdf->GetY() - $y);
                $pdf->SetXY(10, $y);
                $pdf->Cell(10, $rowH, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, $rowH, date('d-m-Y', strtotime($d['date_in'])), 1, 0, 'C');
                $pdf->MultiCell(70, 5, $d['des_item'], 1, 'L');
                $pdf->SetXY(10 + 105, $y);
                $pdf->Cell(50, $rowH, $d['lot_number'] ?: '-', 1, 0, 'C');
                $pdf->Cell(35, $rowH, $d['amount_in'] . ' ' . $d['des_unit'], 1, 0, 'C');
                $pdf->SetY($y + $rowH);
            }
        } else {
            $pdf->Cell(10, 7, 'No.',        1, 0, 'C');
            $pdf->Cell(25, 7, 'Date',       1, 0, 'C');
            $pdf->Cell(75, 7, 'Reagent',    1, 0, 'C');
            $pdf->Cell(50, 7, 'Lot Number', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Qty',        1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 9);
                $y = $pdf->GetY();
                $pdf->SetXY(10 + 35, $y);
                $pdf->MultiCell(75, 5, $d['des_item'], 0, 'L');
                $rowH = max(7, $pdf->GetY() - $y);
                $pdf->SetXY(10, $y);
                $pdf->Cell(10, $rowH, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, $rowH, date('d-m-Y', strtotime($d['date_out'])), 1, 0, 'C');
                $pdf->MultiCell(75, 5, $d['des_item'], 1, 'L');
                $pdf->SetXY(10 + 110, $y);
                $pdf->Cell(50, $rowH, $d['lot_number'] ?? '-', 1, 0, 'C');
                $pdf->Cell(30, $rowH, $d['amount_out'] . ' ' . $d['des_unit'], 1, 0, 'C');
                $pdf->SetY($y + $rowH);
            }
        }

        $fileName = $label . ' ' . $date . '.pdf';
        $pdf->Output('I', $fileName);
        exit;
    }

    private function _printStock(array $data): \CodeIgniter\HTTP\Response
    {
        require_once APPPATH . 'Libraries/fpdf/fpdf/fpdf.php';

        $grouped = [];
        foreach ($data as $d) {
            $grouped[$d['id_item']][] = $d;
        }

        $pdf = new \FPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Current Stock Report', 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(190, 4, 'As of: ' . date('d F Y'), 0, 1, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 7, 'No.',        1, 0, 'C');
        $pdf->Cell(70, 7, 'Reagent',    1, 0, 'C');
        $pdf->Cell(45, 7, 'Lot Number', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Quantity',   1, 0, 'C');
        $pdf->Cell(35, 7, 'Sub Total',  1, 1, 'C');

        $no = 1;
        foreach ($grouped as $rows) {
            $subtotal = 0;
            $unit     = '';
            foreach ($rows as $d) {
                $unit     = $d['des_unit'];
                $lotQty   = isset($d['lot_qty']) ? (int)$d['lot_qty'] : 0;
                $subtotal += $lotQty;

                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(10, 6, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(70, 6, $d['des_item'], 1, 0, 'L');
                $pdf->Cell(45, 6, $d['lot_number'] ?: '-', 1, 0, 'C');
                $pdf->Cell(30, 6, $lotQty . ' ' . $unit, 1, 0, 'C');
                $pdf->Cell(35, 6, '', 1, 1, 'C');
            }
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(10, 6, '', 1, 0, 'C');
            $pdf->Cell(70, 6, '', 1, 0, 'C');
            $pdf->Cell(45, 6, '', 1, 0, 'C');
            $pdf->Cell(30, 6, '', 1, 0, 'C');
            $pdf->Cell(35, 6, $subtotal . ' ' . $unit, 1, 1, 'C');
        }

        $pdf->Output('I', 'Current Stock Report ' . date('Y-m-d') . '.pdf');
        exit;
    }
}
