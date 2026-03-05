<div class="row justify-content-center"><div class="col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3"><h4 class="h5 m-0 font-weight-bold text-primary">Change Password</h4></div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('profile/changepassword') ?>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Current Password <span class="text-danger">*</span></label>
                <div class="col-md-7"><input type="password" name="old_password" class="form-control"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">New Password <span class="text-danger">*</span></label>
                <div class="col-md-7"><input type="password" name="new_password" class="form-control"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Confirm Password <span class="text-danger">*</span></label>
                <div class="col-md-7"><input type="password" name="confirm_password" class="form-control"></div>
            </div>
            <div class="form-group row"><div class="col offset-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-lock mr-1"></i> Update Password</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
