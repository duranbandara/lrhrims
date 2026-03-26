<div class="row justify-content-center"><div class="col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3"><h4 class="h5 m-0 font-weight-bold text-primary"><i class="fas fa-print mr-2"></i>Transaction Report</h4></div>
        <div class="card-body">
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= esc($error) ?></div><?php endif; ?>
            <form method="get" action="<?= base_url('report') ?>" target="_blank">
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Report Type <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <select name="transaction" id="transaction_type" class="custom-select">
                        <option value="">— Select type —</option>
                        <option value="item_in"           <?= ($transaction ?? '') === 'item_in'           ? 'selected' : '' ?>>Incoming Reagents</option>
                        <option value="item_out"          <?= ($transaction ?? '') === 'item_out'          ? 'selected' : '' ?>>Issued Reagents</option>
                        <option value="current_stock"     <?= ($transaction ?? '') === 'current_stock'     ? 'selected' : '' ?>>Current Stock</option>
                        <option value="lot_deletion_logs" <?= ($transaction ?? '') === 'lot_deletion_logs' ? 'selected' : '' ?>>Lot Deletion Audit Log</option>
                        <option value="activity_logs"     <?= ($transaction ?? '') === 'activity_logs'     ? 'selected' : '' ?>>Activity Log (All Actions)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="date_field">
                <label class="col-md-4 col-form-label text-md-right">
                    Date Range <span class="text-danger">*</span>
                </label>
                <div class="col-md-7">
                    <input type="text" name="date" id="date" class="form-control" placeholder="Select date range" value="<?= esc($date ?? '') ?>" autocomplete="off">
                </div>
            </div>
            <div class="form-group row"><div class="col offset-md-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print mr-1"></i> Generate PDF</button>
            </div></div>
            </form>
        </div>
    </div>
</div></div>

<script>
window.addEventListener('load', function() {
    var $dateField = $('#date_field');
    var $sel       = $('#transaction_type');

    function toggleDate() {
        if ($sel.val() === 'current_stock') {
            $dateField.hide();
        } else {
            $dateField.show();
        }
    }

    $sel.on('change', toggleDate);
    toggleDate();

    $('#date').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        opens: 'left',
        autoUpdateInput: false
    });
    $('#date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    $('#date').on('cancel.daterangepicker', function() {
        $(this).val('');
    });
});
</script>
