<?php $report_scripts = true; ?>
<div class="row justify-content-center"><div class="col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3"><h4 class="h5 m-0 font-weight-bold text-primary"><i class="fas fa-print mr-2"></i>Transaction Report</h4></div>
        <div class="card-body">
            <?php if (isset($validation)): ?><div class="alert alert-danger"><?= $validation->listErrors() ?></div><?php endif; ?>
            <?= form_open('report') ?>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Report Type <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <select name="transaction" id="transaction_type" class="custom-select">
                        <option value="">— Select type —</option>
                        <option value="item_in" <?= old('transaction') === 'item_in' ? 'selected' : '' ?>>Incoming Reagents</option>
                        <option value="item_out" <?= old('transaction') === 'item_out' ? 'selected' : '' ?>>Issued Reagents</option>
                        <option value="current_stock" <?= old('transaction') === 'current_stock' ? 'selected' : '' ?>>Current Stock</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="date_field">
                <label class="col-md-4 col-form-label text-md-right">Date Range <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="date" id="date" class="form-control" placeholder="Select date range" value="<?= old('date') ?>" autocomplete="off">
                </div>
            </div>
            <div class="form-group row"><div class="col offset-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print mr-1"></i> Generate PDF</button>
            </div></div>
            <?= form_close() ?>
        </div>
    </div>
</div></div>

<script>
$(function() {
    var $dateField = $('#date_field');
    var $sel = $('#transaction_type');

    function toggleDate() {
        if ($sel.val() === 'current_stock') {
            $dateField.hide();
        } else {
            $dateField.show();
        }
    }

    $sel.on('change', toggleDate);
    toggleDate();
});
</script>
