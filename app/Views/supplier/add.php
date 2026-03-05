<div class="row justify-content-center">
<div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="h5 m-0 font-weight-bold text-primary">Add Supplier</h4>
            <a href="<?= base_url('supplier') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('supplier/add') ?>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Supplier Name <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="des_supplier" class="form-control" value="<?= old('des_supplier') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Phone Number <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="no_telp" class="form-control" value="<?= old('no_telp') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Address <span class="text-danger">*</span></label>
                <div class="col-md-8"><textarea name="address" class="form-control" rows="3"><?= old('address') ?></textarea></div>
            </div>
            <div class="form-group row">
                <div class="col offset-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
</div>
