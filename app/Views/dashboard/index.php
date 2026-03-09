<!-- KPI Cards -->
<div class="row mb-2">
    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Reagents</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $items ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-flask fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Low Stock Alerts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($low_stock) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Expiring &le;30 Days</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($expiring_soon) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-calendar-times fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Lab Sections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sections ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-microscope fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reagent Expiry Table -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-sort-amount-up mr-2"></i>Reagents by Nearest Expiry Lot</h6>
        <div>
            <span class="badge badge-danger mr-1">Expired</span>
            <span class="badge badge-warning mr-1">&le;30d</span>
            <span class="badge badge-info mr-1">&le;60d</span>
            <span class="badge badge-success">&gt;60d</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0" id="expiryTable">
            <thead class="thead-light">
                <tr>
                    <th>#</th><th>Reagent Name</th><th>Item Code</th><th>Stock</th>
                    <th>Nearest Lot #</th><th>Lot Qty</th><th>Days Left</th><th>Stock Level</th>
                    <?php if (is_admin()): ?><th>Action</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($expiry_list): $no = 1; foreach ($expiry_list as $r):
                    $statusClass = expiry_class($r['days_left']);
                    $dLabel      = expiry_label($r['days_left']);
                    $pct = ($r['min_stock'] > 0) ? min(100, round($r['stock'] / $r['min_stock'] * 100)) : 100;
                    $barClass = ($r['stock'] <= $r['min_stock']) ? 'bg-danger' : (($pct < 150) ? 'bg-warning' : 'bg-success');
                    $rowClass = '';
                    if ($r['days_left'] !== null && $r['days_left'] < 0) $rowClass = 'table-danger';
                    elseif ($r['days_left'] !== null && $r['days_left'] <= 30) $rowClass = 'table-warning';
                ?>
                <tr class="<?= $rowClass ?>">
                    <td><?= $no++ ?></td>
                    <td><strong><?= esc($r['des_item']) ?></strong></td>
                    <td><code><?= esc($r['item_code'] ?: '—') ?></code></td>
                    <td><?= (int)$r['stock'] ?></td>
                    <td><code><?= !empty($r['nearest_lot']) ? esc($r['nearest_lot']) : '<em class="text-muted">—</em>' ?></code></td>
                    <td><?= isset($r['nearest_lot_qty']) ? (int)$r['nearest_lot_qty'] : '<em class="text-muted">—</em>' ?></td>
                    <td><span class="badge badge-<?= $statusClass ?>"><?= $dLabel ?></span></td>
                    <td style="min-width:110px;">
                        <div class="progress progress-sm mb-1">
                            <div class="progress-bar <?= $barClass ?>" style="width:<?= $pct ?>%"></div>
                        </div>
                        <small class="text-muted"><?= $r['stock'] ?> / <?= $r['min_stock'] ?></small>
                    </td>
                    <?php if (is_admin()): ?>
                    <td>
                        <a href="<?= base_url('incomingitems/add') ?>" class="btn btn-xs btn-success" title="Receive"><i class="fa fa-plus"></i></a>
                        <a href="<?= base_url('goods/edit/' . $r['id_item']) ?>" class="btn btn-xs btn-secondary" title="Edit"><i class="fa fa-edit"></i></a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="<?= is_admin() ? 9 : 8 ?>" class="text-center text-muted py-3">No reagents found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bottom Panels -->
<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-danger py-3">
                <h6 class="m-0 font-weight-bold text-white text-center"><i class="fas fa-exclamation-circle mr-1"></i> Low Stock Alerts</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 text-center table-striped table-hover table-sm">
                    <thead><tr><th>Reagent</th><th>Stock</th><th>Min</th><?php if (is_admin()): ?><th></th><?php endif; ?></tr></thead>
                    <tbody>
                        <?php if ($low_stock): foreach ($low_stock as $b): ?>
                        <tr>
                            <td class="text-left small"><?= esc($b['des_item']) ?></td>
                            <td><span class="badge badge-danger"><?= (int)$b['stock'] ?></span></td>
                            <td><?= (int)$b['min_stock'] ?></td>
                            <?php if (is_admin()): ?>
                            <td><a href="<?= base_url('incomingitems/add') ?>" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i></a></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-muted small py-2">All stock levels OK</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success py-3">
                <h6 class="m-0 font-weight-bold text-white text-center"><i class="fas fa-truck-loading mr-1"></i> Recent Incoming (5)</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-striped table-hover text-center">
                    <thead><tr><th>Date</th><th>Reagent</th><th>Lot</th><th>Qty</th></tr></thead>
                    <tbody>
                        <?php if ($transaction['in_items']): foreach ($transaction['in_items'] as $tii): ?>
                        <tr>
                            <td class="text-nowrap"><?= date('d/m/y', strtotime($tii['date_in'])) ?></td>
                            <td class="text-left small"><?= esc(mb_strimwidth($tii['des_item'], 0, 22, '…')) ?></td>
                            <td><small class="text-muted"><?= esc($tii['lot_number'] ?: '—') ?></small></td>
                            <td><span class="badge badge-success"><?= $tii['amount_in'] ?></span></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-muted">No data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-info py-3">
                <h6 class="m-0 font-weight-bold text-white text-center"><i class="fas fa-people-carry mr-1"></i> Recently Issued (5)</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-striped table-hover text-center">
                    <thead><tr><th>Date</th><th>Reagent</th><th>Section</th><th>Qty</th></tr></thead>
                    <tbody>
                        <?php if ($transaction['out_items']): foreach ($transaction['out_items'] as $toi): ?>
                        <tr>
                            <td class="text-nowrap"><?= date('d/m/y', strtotime($toi['date_out'])) ?></td>
                            <td class="text-left small"><?= esc(mb_strimwidth($toi['des_item'], 0, 22, '…')) ?></td>
                            <td><small><?= esc($toi['section_name'] ?: '—') ?></small></td>
                            <td><span class="badge badge-info"><?= $toi['amount_out'] ?></span></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-muted">No data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary py-3">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-chart-line mr-2"></i>Monthly Reagent Transactions — <?= date('Y') ?></h6>
    </div>
    <div class="card-body">
        <div class="chart-area"><canvas id="myAreaChart" height="100"></canvas></div>
        <div class="mt-2 text-center small">
            <span class="mr-3"><i class="fas fa-circle text-primary"></i> Received</span>
            <span><i class="fas fa-circle text-danger"></i> Issued</span>
        </div>
    </div>
</div>
