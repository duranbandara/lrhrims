
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h4 class="h5 m-0 font-weight-bold text-primary">
            <i class="fas fa-history mr-2"></i>Activity Log
            <small class="text-muted font-weight-normal ml-1">(Latest 50 records)</small>
        </h4>
    </div>
    <div class="card-body p-0">
        <?php if (empty($logs)): ?>
            <p class="p-3 text-muted mb-0">No activity records found.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0" id="dataTable">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Date / Time</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="text-nowrap"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
                        <td><?= esc($log['user_name']) ?></td>
                        <td>
                            <span class="badge badge-<?= $log['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                <?= esc($log['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                $actionColors = [
                                    'login'  => 'success',
                                    'logout' => 'secondary',
                                    'create' => 'primary',
                                    'update' => 'warning',
                                    'delete' => 'danger',
                                ];
                                $color = $actionColors[$log['action']] ?? 'dark';
                            ?>
                            <span class="badge badge-<?= $color ?>"><?= esc($log['action']) ?></span>
                        </td>
                        <td><?= esc($log['module']) ?></td>
                        <td><?= esc($log['description']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete by Date Range -->
<div class="card shadow-sm mb-3">
    <div class="card-header bg-white py-3">
        <h4 class="h5 m-0 font-weight-bold text-danger">
            <i class="fas fa-trash-alt mr-2"></i>Delete Old Records by Date Range
        </h4>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('activitylog/deleterange') ?>" onsubmit="return confirmDelete()">
            <?= csrf_field() ?>
            <div class="form-row align-items-end">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted">Date Range</label>
                    <input type="text" name="date" id="delete_date_range" class="form-control form-control-sm"
                           placeholder="Select date range" autocomplete="off" required>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Records
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
window.addEventListener('load', function () {
    $('#delete_date_range').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        opens: 'left',
        autoUpdateInput: false
    });
    $('#delete_date_range').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    $('#delete_date_range').on('cancel.daterangepicker', function () {
        $(this).val('');
    });
});

function confirmDelete() {
    var range = document.getElementById('delete_date_range').value;
    if (!range) return false;
    return confirm('Delete all activity log records from ' + range + '?\n\nThis action cannot be undone.');
}
</script>
