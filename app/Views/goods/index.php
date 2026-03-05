<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col"><h4 class="h5 m-0 font-weight-bold text-primary">Reagent List</h4></div>
            <div class="col-auto">
                <a href="<?= base_url('goods/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span><span class="text">Add Reagent</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr><th>#</th><th>ID</th><th>Reagent Name</th><th>Item Code</th><th>Category</th><th>Unit</th><th>Stock</th><th>Min Stock</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if ($items): $no = 1; foreach ($items as $b):
                    $stockClass = ($b['stock'] <= $b['min_stock']) ? 'badge-danger' : (($b['stock'] < $b['min_stock'] * 1.5) ? 'badge-warning' : 'badge-success');
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><code><?= esc($b['id_item']) ?></code></td>
                    <td><?= esc($b['des_item']) ?></td>
                    <td><code><?= esc($b['item_code'] ?? '—') ?></code></td>
                    <td><?= esc($b['des_type']) ?></td>
                    <td><?= esc($b['des_unit']) ?></td>
                    <td><span class="badge <?= $stockClass ?>"><?= (int)$b['stock'] ?></span></td>
                    <td><?= (int)$b['min_stock'] ?></td>
                    <td>
                        <a href="<?= base_url('goods/edit/' . $b['id_item']) ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Delete this reagent?')" href="<?= base_url('goods/delete/' . $b['id_item']) ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
