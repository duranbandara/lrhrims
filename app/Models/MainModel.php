<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;

class MainModel
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAll(string $table): array
    {
        return $this->db->table($table)->get()->getResultArray();
    }

    public function getRow(string $table, array $where): array
    {
        $result = $this->db->table($table)->where($where)->get()->getRowArray();
        return $result ?? [];
    }

    public function insertRow(string $table, array $data): bool
    {
        return $this->db->table($table)->insert($data);
    }

    public function insertBatch(string $table, array $data): bool
    {
        return $this->db->table($table)->insertBatch($data) !== false;
    }

    public function updateRow(string $table, string $pk, $id, array $data): bool
    {
        return $this->db->table($table)->where($pk, $id)->update($data);
    }

    public function deleteRow(string $table, string $pk, $id): bool
    {
        return $this->db->table($table)->where($pk, $id)->delete();
    }

    public function countAll(string $table): int
    {
        return $this->db->table($table)->countAllResults();
    }

    public function getMax(string $table, string $field, ?string $like = null)
    {
        $builder = $this->db->table($table)->selectMax($field);
        if ($like !== null) {
            $builder->like($field, $like, 'after');
        }
        $row = $builder->get()->getRowArray();
        return $row[$field] ?? null;
    }

    public function getGoods(): array
    {
        return $this->db->table('items b')
            ->select('b.id_item, b.des_item, b.item_code, b.stock, b.min_stock, b.price, b.type_id, b.unit_id, j.des_type, s.des_unit')
            ->join('type j', 'b.type_id = j.id_type')
            ->join('unit s', 'b.unit_id = s.id_unit')
            ->orderBy('b.id_item')
            ->get()->getResultArray();
    }

    public function getIncomingItems(?int $limit = null, ?string $itemId = null, ?array $range = null): array
    {
        $builder = $this->db->table('item_in bm')
            ->select('bm.id_item_in, bm.item_id, bm.amount_in, bm.date_in, bm.lot_number, bm.expiry_date, bm.supplier_id, bm.user_id, u.des as user_name, sp.des_supplier, b.des_item, b.item_code, st.des_unit')
            ->join('user u',      'bm.user_id = u.id_user', 'left')
            ->join('supplier sp', 'bm.supplier_id = sp.id_supplier', 'left')
            ->join('items b',     'bm.item_id = b.id_item', 'left')
            ->join('unit st',     'b.unit_id = st.id_unit', 'left');

        if ($limit !== null) {
            $builder->limit($limit);
        }
        if ($itemId !== null) {
            $builder->where('bm.item_id', $itemId);
        }
        if ($range !== null) {
            $builder->where('date_in >=', $range['start']);
            $builder->where('date_in <=', $range['end']);
        }
        return $builder->orderBy('bm.id_item_in', 'DESC')->get()->getResultArray();
    }

    public function getOutgoingItemsDashboard(?int $limit = null, ?array $range = null): array
    {
        $builder = $this->db->table('item_out_dtl bkd')
            ->select('bkd.id_detail, bkd.id_item_out, bkd.item_id, bkd.amount_out, bkd.lot_number, bk.date_out, bk.section_id, u.des as user_name, b.des_item, st.des_unit, ls.section_name')
            ->join('item_out bk',     'bk.id_item_out = bkd.id_item_out')
            ->join('user u',          'bk.user_id = u.id_user')
            ->join('items b',         'bkd.item_id = b.id_item')
            ->join('unit st',         'b.unit_id = st.id_unit')
            ->join('lab_sections ls', 'bk.section_id = ls.id_section', 'left');

        if ($limit !== null) {
            $builder->limit($limit);
        }
        if ($range !== null) {
            $builder->where('bk.date_out >=', $range['start']);
            $builder->where('bk.date_out <=', $range['end']);
        }
        return $builder->orderBy('bkd.id_detail', 'DESC')->get()->getResultArray();
    }

    public function getOutgoingItems(?int $limit = null, ?array $range = null): array
    {
        $builder = $this->db->table('item_out bk')
            ->select('bk.id_item_out, bk.date_out, bk.section_id, bk.user_id, ls.section_name')
            ->join('lab_sections ls', 'bk.section_id = ls.id_section', 'left');

        if ($limit !== null) {
            $builder->limit($limit);
        }
        if ($range !== null) {
            $builder->where('date_out >=', $range['start']);
            $builder->where('date_out <=', $range['end']);
        }
        return $builder->orderBy('bk.id_item_out', 'DESC')->get()->getResultArray();
    }

    public function getOutgoingItemDetails(string $id): array
    {
        return $this->db->table('item_out_dtl bkd')
            ->select('bkd.id_detail, bkd.id_item_out, bkd.item_id, bkd.amount_out, bkd.lot_number, bk.date_out, bk.section_id, ls.section_name, u.des as user_name, b.des_item, b.item_code, j.des_type, st.des_unit')
            ->join('item_out bk',     'bk.id_item_out = bkd.id_item_out')
            ->join('user u',          'bk.user_id = u.id_user')
            ->join('items b',         'bkd.item_id = b.id_item')
            ->join('type j',          'b.type_id = j.id_type')
            ->join('unit st',         'b.unit_id = st.id_unit')
            ->join('lab_sections ls', 'bk.section_id = ls.id_section', 'left')
            ->where('bkd.id_item_out', $id)
            ->get()->getResultArray();
    }

    public function getLowStockReagents(): array
    {
        return $this->db->query(
            'SELECT b.id_item, b.des_item, b.item_code, b.stock, b.min_stock, j.des_type, s.des_unit
             FROM items b
             LEFT JOIN type j ON b.type_id = j.id_type
             LEFT JOIN unit s ON b.unit_id = s.id_unit
             WHERE b.stock <= b.min_stock
             ORDER BY b.stock ASC'
        )->getResultArray();
    }

    public function getExpiringReagents(int $days = 30): array
    {
        $cutoff = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->table('reagent_lots rl')
            ->select('rl.id_lot, rl.reagent_id, rl.lot_number, rl.expiry_date, rl.quantity, b.des_item, b.item_code, b.stock, b.min_stock, s.des_unit')
            ->join('items b', 'rl.reagent_id = b.id_item')
            ->join('unit s',  'b.unit_id = s.id_unit', 'left')
            ->where('rl.expiry_date <=', $cutoff)
            ->where('rl.quantity >', 0)
            ->orderBy('rl.expiry_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getReagentsByExpiryOrder(): array
    {
        $sql = 'SELECT b.id_item, b.des_item, b.item_code, b.stock, b.min_stock, j.des_type,
                       (SELECT rl2.lot_number FROM reagent_lots rl2
                        WHERE rl2.reagent_id = b.id_item AND rl2.quantity > 0
                        ORDER BY rl2.expiry_date ASC LIMIT 1) AS nearest_lot,
                       (SELECT rl2.quantity FROM reagent_lots rl2
                        WHERE rl2.reagent_id = b.id_item AND rl2.quantity > 0
                        ORDER BY rl2.expiry_date ASC LIMIT 1) AS nearest_lot_qty,
                       DATEDIFF(
                           (SELECT MIN(rl3.expiry_date) FROM reagent_lots rl3
                            WHERE rl3.reagent_id = b.id_item AND rl3.quantity > 0),
                           CURDATE()
                       ) AS days_left
                FROM items b
                LEFT JOIN type j ON b.type_id = j.id_type
                ORDER BY
                    (SELECT MIN(rl4.expiry_date) FROM reagent_lots rl4
                     WHERE rl4.reagent_id = b.id_item AND rl4.quantity > 0) IS NULL ASC,
                    (SELECT MIN(rl4.expiry_date) FROM reagent_lots rl4
                     WHERE rl4.reagent_id = b.id_item AND rl4.quantity > 0) ASC,
                    b.stock ASC';
        return $this->db->query($sql)->getResultArray();
    }

    public function chartIncoming(string $month): int
    {
        $like = 'I' . date('y') . $month;
        return $this->db->table('item_in')->like('id_item_in', $like, 'after')->countAllResults();
    }

    public function chartOutgoing(string $month): int
    {
        $like = 'S' . date('y') . $month;
        return $this->db->table('item_out')->like('id_item_out', $like, 'after')->countAllResults();
    }

    public function checkStock(string $id): array
    {
        $row = $this->db->table('items b')
            ->join('unit s', 'b.unit_id = s.id_unit')
            ->where('id_item', $id)
            ->get()->getRowArray();
        return $row ?? [];
    }

    public function getReagentByItemCode(string $itemCode): array
    {
        $row = $this->db->table('items b')
            ->select('b.id_item, b.des_item, b.item_code, b.stock, b.min_stock, j.des_type, s.des_unit')
            ->join('type j', 'b.type_id = j.id_type', 'left')
            ->join('unit s', 'b.unit_id = s.id_unit', 'left')
            ->where('b.item_code', $itemCode)
            ->get()->getRowArray();
        return $row ?? [];
    }

    public function getLotByNumber(string $reagentId, string $lotNumber): array
    {
        $row = $this->db->table('reagent_lots')
            ->where('reagent_id', $reagentId)
            ->where('lot_number', $lotNumber)
            ->get()->getRowArray();
        return $row ?? [];
    }

    public function getLotsByReagent(string $reagentId): array
    {
        return $this->db->table('reagent_lots')
            ->where('reagent_id', $reagentId)
            ->where('quantity >', 0)
            ->orderBy('expiry_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getFEFOLots(string $reagentId, int $quantityNeeded): array
    {
        $lots      = $this->getLotsByReagent($reagentId);
        $result    = [];
        $remaining = $quantityNeeded;

        foreach ($lots as $lot) {
            if ($remaining <= 0) break;
            $deduct   = min((int)$lot['quantity'], $remaining);
            $result[] = [
                'lot_number'  => $lot['lot_number'],
                'expiry_date' => $lot['expiry_date'],
                'available'   => (int)$lot['quantity'],
                'deduct'      => $deduct,
            ];
            $remaining -= $deduct;
        }
        return $result;
    }

    public function getLabSections(): array
    {
        return $this->db->table('lab_sections')->orderBy('section_name', 'ASC')->get()->getResultArray();
    }

    public function getCurrentStockByLot(): array
    {
        return $this->db->table('items b')
            ->select('b.id_item, b.des_item, b.item_code, b.stock, s.des_unit, rl.lot_number, rl.expiry_date, rl.quantity as lot_qty')
            ->join('unit s', 'b.unit_id = s.id_unit', 'left')
            ->join('reagent_lots rl', 'rl.reagent_id = b.id_item AND rl.quantity > 0', 'left')
            ->where('b.stock >', 0)
            ->orderBy('b.des_item ASC, rl.expiry_date ASC')
            ->get()->getResultArray();
    }

    public function getUsers(int $excludeId): array
    {
        return $this->db->table('user')->where('id_user !=', $excludeId)->get()->getResultArray();
    }

    public function getOutgoingItemById(string $id): array
    {
        $row = $this->db->table('item_out bk')
            ->select('bk.*, ls.section_name, u.des as user_name')
            ->join('user u', 'bk.user_id = u.id_user')
            ->join('lab_sections ls', 'bk.section_id = ls.id_section', 'left')
            ->where('bk.id_item_out', $id)
            ->get()->getRowArray();
        return $row ?? [];
    }
}
