<div class="row justify-content-center"><div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3"><h4 class="h5 m-0 font-weight-bold text-primary">Profile Settings</h4></div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open_multipart('profile/setting') ?>
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
                <label class="col-md-3 col-form-label text-md-right">Phone Number <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="no_telp" class="form-control" value="<?= old('no_telp', $user['no_telp']) ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Profile Photo</label>
                <div class="col-md-8">
                    <input type="file" name="photo" class="form-control-file" accept="image/*">
                    <small class="text-muted">Leave empty to keep current photo</small>
                </div>
            </div>
            <div class="form-group row"><div class="col offset-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Changes</button>
                <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Cancel</a>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
