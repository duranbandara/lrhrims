<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col"><h4 class="h5 m-0 font-weight-bold text-primary">Goods Unit</h4></div>
            <div class="col-auto">
                <a href="<?= base_url('unit/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span><span class="text">Add Unit</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive" id="dataTable">
            <thead><tr><th>#</th><th>Unit Name</th><th>Action</th></tr></thead>
            <tbody>
                <?php if ($unit): $no = 1; foreach ($unit as $u): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($u['des_unit']) ?></td>
                    <td>
                        <a href="<?= base_url('unit/edit/' . $u['id_unit']) ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Delete unit?')" href="<?= base_url('unit/delete/' . $u['id_unit']) ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="3" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
