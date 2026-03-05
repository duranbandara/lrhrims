<div class="row justify-content-center"><div class="col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h4 class="h5 m-0 font-weight-bold text-primary">Add Unit</h4>
            <a href="<?= base_url('unit') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('unit/add') ?>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Unit Name <span class="text-danger">*</span></label>
                <div class="col-md-7"><input type="text" name="des_unit" class="form-control" value="<?= old('des_unit') ?>"></div>
            </div>
            <div class="form-group row"><div class="col offset-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
