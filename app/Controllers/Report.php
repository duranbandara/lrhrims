<?php

namespace App\Controllers;

class Report extends BaseAppController
{
    public function index()
    {
        if ($this->request->getMethod() === 'post') {
            $transaction = $this->request->getPost('transaction');
            $rules       = ['transaction' => 'required|in_list[item_in,item_out,current_stock]'];
            if ($transaction !== 'current_stock') {
                $rules['date'] = 'required';
            }

            if (! $this->validate($rules)) {
                return $this->render('report/index', [
                    'title'      => 'Transaction Report',
                    'validation' => $this->validator,
                ]);
            }

            if ($transaction === 'current_stock') {
                $query = $this->model->getCurrentStockByLot();
                return $this->_printStock($query);
            }

            $date   = $this->request->getPost('date');
            $parts  = explode(' - ', $date);
            $start  = date('Y-m-d', strtotime($parts[0]));
            $end    = date('Y-m-d', strtotime(end($parts)));

            if ($transaction === 'item_in') {
                $query = $this->model->getIncomingItems(null, null, ['start' => $start, 'end' => $end]);
            } else {
                $query = $this->model->getOutgoingItemsDashboard(null, ['start' => $start, 'end' => $end]);
            }

            return $this->_printTransactions($query, $transaction, $date);
        }

        return $this->render('report/index', ['title' => 'Transaction Report']);
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
