<div class="row justify-content-center"><div class="col-md-7">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h4 class="h5 m-0 font-weight-bold text-primary">Add Reagent</h4>
            <a href="<?= base_url('goods') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('goods/add') ?>
            <input type="hidden" name="id_item" value="<?= esc($id_item) ?>">
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Reagent ID</label>
                <div class="col-md-4"><input type="text" class="form-control bg-light" value="<?= esc($id_item) ?>" readonly></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Reagent Name <span class="text-danger">*</span></label>
                <div class="col-md-8"><input type="text" name="des_item" class="form-control" value="<?= old('des_item') ?>"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Item Code</label>
                <div class="col-md-8"><input type="text" name="item_code" class="form-control" value="<?= old('item_code') ?>" placeholder="Manufacturer catalog code"></div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Category <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <select name="type_id" class="custom-select">
                        <option value="">— Select category —</option>
                        <?php foreach ($type as $t): ?>
                        <option value="<?= $t['id_type'] ?>" <?= old('type_id') == $t['id_type'] ? 'selected' : '' ?>><?= esc($t['des_type']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Unit <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <select name="unit_id" class="custom-select">
                        <option value="">— Select unit —</option>
                        <?php foreach ($unit as $u): ?>
                        <option value="<?= $u['id_unit'] ?>" <?= old('unit_id') == $u['id_unit'] ? 'selected' : '' ?>><?= esc($u['des_unit']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Min Stock <span class="text-danger">*</span></label>
                <div class="col-md-4"><input type="number" name="min_stock" class="form-control" min="1" value="<?= old('min_stock', 5) ?>"></div>
            </div>
            <div class="form-group row"><div class="col offset-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>
