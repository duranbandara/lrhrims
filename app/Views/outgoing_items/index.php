<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">Issue History</h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('outgoingitems/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span class="text">Issue Reagent</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr><th>#</th><th>T-ID</th><th>Issue Date</th><th>Lab Section</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php $no = 1; if ($outgoingitems): foreach ($outgoingitems as $bk): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($bk['id_item_out']) ?></td>
                    <td><?= esc($bk['date_out']) ?></td>
                    <td><?= esc($bk['section_name'] ?? '—') ?></td>
                    <td>
                        <a onclick="return confirm('Delete this issue record? Stock will be restored.')"
                           href="<?= base_url('outgoingitems/delete/' . $bk['id_item_out']) ?>"
                           class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
