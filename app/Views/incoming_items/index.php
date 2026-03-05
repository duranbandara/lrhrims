<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">Receive Reagents History</h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('incomingitems/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span class="text">Receive Reagent</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>#</th><th>T-ID</th><th>Date</th><th>Supplier</th><th>Reagent</th>
                    <th>Item Code</th><th>Lot Number</th><th>Expiry Date</th><th>Qty</th><th>User</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; if ($incomingitems): foreach ($incomingitems as $ii):
                    $expiry = $ii['expiry_date'] ?? null;
                    $days   = $expiry ? (int)(( strtotime($expiry) - time()) / 86400) : null;
                    $rowClass = '';
                    if ($days !== null) {
                        if ($days < 0) $rowClass = 'table-danger';
                        elseif ($days <= 30) $rowClass = 'table-warning';
                    }
                ?>
                <tr class="<?= $rowClass ?>">
                    <td><?= $no++ ?></td>
                    <td><?= esc($ii['id_item_in']) ?></td>
                    <td><?= esc($ii['date_in']) ?></td>
                    <td><?= esc($ii['des_supplier'] ?? '—') ?></td>
                    <td><?= esc($ii['des_item']) ?></td>
                    <td><code><?= esc($ii['item_code'] ?? '—') ?></code></td>
                    <td><?= esc($ii['lot_number'] ?? '—') ?></td>
                    <td>
                        <?= $expiry ?? '—' ?>
                        <?php if ($days !== null): ?>
                            <span class="badge badge-<?= $days < 0 ? 'danger' : ($days <= 30 ? 'warning' : 'success') ?>">
                                <?= $days < 0 ? 'EXPIRED' : $days . 'd' ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= (int)$ii['amount_in'] . ' ' . esc($ii['des_unit']) ?></td>
                    <td><?= esc($ii['user_name'] ?? '') ?></td>
                    <td>
                        <a onclick="return confirm('Delete this receive record?')"
                           href="<?= base_url('incomingitems/delete/' . $ii['id_item_in']) ?>"
                           class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="11" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
