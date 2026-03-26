<div class="row">
    <div class="col-12">

        <!-- Reagent selector card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="h5 m-0 font-weight-bold text-primary">
                    <i class="fas fa-vials mr-2"></i>Lot Management
                </h4>
            </div>
            <div class="card-body">
                <form method="get" action="<?= base_url('lotmanagement') ?>">
                <div class="form-row align-items-end">
                    <div class="col-md-6">
                        <label class="font-weight-bold">Select Reagent</label>
                        <select name="reagent_id" class="custom-select" required>
                            <option value="">— Choose a reagent —</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?= esc($item['id_item']) ?>"
                                    <?= ($reagentId === $item['id_item']) ? 'selected' : '' ?>>
                                    <?= esc($item['des_item']) ?>
                                    (<?= esc($item['item_code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> View Lots
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Lots table -->
        <?php if ($reagentId && !empty($selectedReagent)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-dark">
                    Lots for: <span class="text-primary"><?= esc($selectedReagent['des_item']) ?></span>
                    <small class="text-muted">(<?= esc($selectedReagent['item_code']) ?>)</small>
                </h5>
                <span class="badge badge-secondary"><?= count($lots) ?> lot(s) found</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($lots)): ?>
                    <p class="p-3 text-muted mb-0">No lots found for this reagent.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Lot Number</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Remaining Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($lots as $lot): ?>
                            <?php
                                $daysLeft = $lot['days_left'];
                                $isExpired = $daysLeft !== null && (int)$daysLeft < 0;
                            ?>
                            <tr class="<?= $isExpired ? 'table-danger' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td class="font-weight-bold"><?= esc($lot['lot_number']) ?></td>
                                <td>
                                    <?= $lot['expiry_date'] ? date('d M Y', strtotime($lot['expiry_date'])) : '-' ?>
                                </td>
                                <td>
                                    <?php if ($daysLeft === null || $lot['expiry_date'] === null): ?>
                                        <span class="badge badge-secondary">No expiry</span>
                                    <?php elseif ((int)$daysLeft < 0): ?>
                                        <span class="badge badge-danger">EXPIRED (<?= abs((int)$daysLeft) ?>d ago)</span>
                                    <?php elseif ((int)$daysLeft === 0): ?>
                                        <span class="badge badge-danger">Expires TODAY</span>
                                    <?php elseif ((int)$daysLeft <= 30): ?>
                                        <span class="badge badge-warning">Expires in <?= (int)$daysLeft ?>d</span>
                                    <?php elseif ((int)$daysLeft <= 60): ?>
                                        <span class="badge badge-info">Expires in <?= (int)$daysLeft ?>d</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Valid (<?= (int)$daysLeft ?>d)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= (float)$lot['quantity'] ?> <?= esc($lot['des_unit'] ?? '') ?></td>
                                <td>
                                    <a href="#"
                                        class="btn btn-danger btn-xs"
                                        onclick="return deleteLot(<?= $lot['id_lot'] ?>, '<?= esc($lot['lot_number'], 'js') ?>', <?= (float)$lot['quantity'] ?>, '<?= esc($lot['des_unit'] ?? '', 'js') ?>')">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php elseif ($reagentId): ?>
            <div class="alert alert-warning">Reagent not found.</div>
        <?php endif; ?>

    </div>
</div>

<script>
function deleteLot(lotId, lotNumber, qty, unit) {
    var reason = prompt(
        'Delete lot: ' + lotNumber + '\nRemaining qty: ' + qty + ' ' + unit +
        '\n\nEnter reason for deletion (required):'
    );
    if (reason === null) return false;
    reason = reason.trim();
    if (reason === '') {
        alert('A reason is required to delete a lot.');
        return false;
    }
    window.location.href = '<?= base_url('lotmanagement/deletelot') ?>/' + lotId +
        '?reason=' + encodeURIComponent(reason);
    return false;
}
</script>
