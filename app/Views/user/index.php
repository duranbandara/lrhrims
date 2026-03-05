<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col"><h4 class="h5 m-0 font-weight-bold text-primary">User Management</h4></div>
            <div class="col-auto">
                <a href="<?= base_url('user/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon"><i class="fa fa-plus"></i></span><span class="text">Add User</span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover w-100 dt-responsive" id="dataTable">
            <thead><tr><th>#</th><th>Photo</th><th>Name</th><th>Username</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php if ($users): $no = 1; foreach ($users as $u): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><img src="<?= base_url('assets/img/avatar/' . esc($u['photo'])) ?>" class="rounded-circle" style="width:35px;height:35px;object-fit:cover;"></td>
                    <td><?= esc($u['des']) ?></td>
                    <td><?= esc($u['username']) ?></td>
                    <td><?= esc($u['email']) ?></td>
                    <td><?= esc($u['no_telp']) ?></td>
                    <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : 'primary' ?>"><?= ucfirst($u['role']) ?></span></td>
                    <td><span class="badge badge-<?= $u['is_active'] ? 'success' : 'secondary' ?>"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                    <td>
                        <a href="<?= base_url('user/edit/' . $u['id_user']) ?>" class="btn btn-info btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                        <a onclick="return confirm('Delete user?')" href="<?= base_url('user/delete/' . $u['id_user']) ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" class="text-center">No Data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
