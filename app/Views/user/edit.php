<div class="row justify-content-center"><div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h4 class="h5 m-0 font-weight-bold text-primary">Edit User</h4>
            <a href="<?= base_url('user') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('user/edit/' . $user['id_user']) ?>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Full Name <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="des" class="form-control" value="<?= old('des', $user['des']) ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Username <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Email <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="email" name="email" class="form-control" value="<?= old('email', $user['email']) ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Phone <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="no_telp" class="form-control" value="<?= old('no_telp', $user['no_telp']) ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Role <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <select name="role" class="custom-select">
                        <option value="user" <?= old('role', $user['role']) === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Status <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <select name="is_active" class="custom-select">
                        <option value="1" <?= old('is_active', $user['is_active']) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= old('is_active', $user['is_active']) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-group row"><div class="col offset-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
