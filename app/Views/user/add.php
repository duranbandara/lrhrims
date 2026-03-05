<div class="row justify-content-center"><div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h4 class="h5 m-0 font-weight-bold text-primary">Add User</h4>
            <a href="<?= base_url('user') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('user/add') ?>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Full Name <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="des" class="form-control" value="<?= old('des') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Username <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="username" class="form-control" value="<?= old('username') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Email <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="email" name="email" class="form-control" value="<?= old('email') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Phone <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="no_telp" class="form-control" value="<?= old('no_telp') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Password <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="password" name="password" class="form-control"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Role <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <select name="role" class="custom-select">
                        <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row"><div class="col offset-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
