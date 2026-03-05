<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col"><h4 class="h5 m-0 font-weight-bold text-primary">Supplier's Data</h4></div>
            <div class="col-auto">
                <a href="<?= base_url('supplier/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span><span class="text">Add Supplier</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive nowrap" id="dataTable">
            <thead><tr><th>#</th><th>Name</th><th>Contact</th><th>Address</th><th>Action</th></tr></thead>
            <tbody>
                <?php if ($supplier): $no = 1; foreach ($supplier as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($s['des_supplier']) ?></td>
                    <td><?= esc($s['no_telp']) ?></td>
                    <td><?= esc($s['address']) ?></td>
                    <td>
                        <a href="<?= base_url('supplier/edit/' . $s['id_supplier']) ?>" class="btn btn-circle btn-info btn-sm"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Delete this supplier?')" href="<?= base_url('supplier/delete/' . $s['id_supplier']) ?>" class="btn btn-circle btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
