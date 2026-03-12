<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title) ?> | Lab Reagent System</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/fonts.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/daterangepicker/daterangepicker.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/buttons/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/datatables/responsive/css/responsive.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/gijgo/css/gijgo.min.css') ?>" rel="stylesheet">
    <style>
        #accordionSidebar, .topbar { z-index: 1; }
        .btn-xs { padding: .15rem .35rem; font-size: .75rem; }
    </style>
</head>
<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-white sidebar sidebar-light accordion shadow-sm" id="accordionSidebar">
        <a class="sidebar-brand d-flex text-white align-items-center bg-primary justify-content-center" href="<?= base_url('dashboard') ?>">
            <div class="sidebar-brand-icon"><i class="fas fa-flask"></i></div>
            <div class="sidebar-brand-text mx-3">LRHRIMS</div>
        </a>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Transactions</div>

        <li class="nav-item">
            <a class="nav-link pb-0" href="<?= base_url(is_admin() ? 'incomingitems' : 'incomingitems/add') ?>">
                <i class="fas fa-fw fa-truck-loading"></i><span>Receive Reagents</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url(is_admin() ? 'outgoingitems' : 'outgoingitems/add') ?>">
                <i class="fas fa-fw fa-people-carry"></i><span>Issue to Section</span>
            </a>
        </li>

        <?php if (is_admin()): ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Data Master</div>

        <li class="nav-item">
            <a class="nav-link pb-0" href="<?= base_url('supplier') ?>">
                <i class="fas fa-fw fa-industry"></i><span>Supplier / Manufacturer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link pb-0" href="<?= base_url('labsection') ?>">
                <i class="fas fa-fw fa-microscope"></i><span>Lab Sections</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false">
                <i class="fas fa-fw fa-flask"></i><span>Reagents</span>
            </a>
            <div id="collapseMaster" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-light py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reagent Management:</h6>
                    <a class="collapse-item" href="<?= base_url('unit') ?>">Units</a>
                    <a class="collapse-item" href="<?= base_url('category') ?>">Categories</a>
                    <a class="collapse-item" href="<?= base_url('goods') ?>">Reagent List</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Report</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('report') ?>">
                <i class="fas fa-fw fa-print"></i><span>Reports</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Settings</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user') ?>">
                <i class="fas fa-fw fa-user-cog"></i><span>User Management</span>
            </a>
        </li>
        <?php endif; ?>

        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-dark bg-primary topbar mb-4 static-top shadow-sm">
                <button id="sidebarToggleTop" class="btn btn-link bg-transparent d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars text-white"></i>
                </button>
                <span class="navbar-text text-white font-weight-bold d-none d-md-inline"><?= esc($title) ?></span>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline small text-capitalize">
                                <?= esc(userdata('des')) ?>
                            </span>
                            <img class="img-profile rounded-circle" src="<?= base_url('assets/img/avatar/' . userdata('photo')) ?>">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="<?= base_url('profile') ?>">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                            </a>
                            <a class="dropdown-item" href="<?= base_url('profile/setting') ?>">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings
                            </a>
                            <a class="dropdown-item" href="<?= base_url('profile/changepassword') ?>">
                                <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i> Change Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-power-off fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End Topbar -->

            <div class="container-fluid">
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('message')) ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="sticky-footer bg-light">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>&copy; Lab Reagent Inventory <?= date('Y') ?> &bull; CodeIgniter 4</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you want to log out?</h5>
                <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">Click "Logout" to exit the system.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/daterangepicker/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/daterangepicker/daterangepicker.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/buttons/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/gijgo/js/gijgo.min.js') ?>"></script>

<script>
$(function() {
    $('.date').datepicker({ uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });
});
$(document).ready(function() {
    if ($('#dataTable').length) {
        var table = $('#dataTable').DataTable({
            buttons: ['copy', 'csv', 'print', 'excel', 'pdf'],
            dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'B><'col-md-4'f>>" +
                 "<'row'<'col-md-12'tr>>" +
                 "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
            lengthMenu: [[5,10,25,50,100,-1],[5,10,25,50,100,'All']],
            columnDefs: [{ targets: -1, orderable: false, searchable: false }]
        });
        table.buttons().container().appendTo('#dataTable_wrapper .col-md-5:eq(0)');
    }
});
</script>

<?php if (isset($dashboard_scripts) && $dashboard_scripts): ?>
<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>
<script>
Chart.defaults.global.defaultFontFamily = 'Nunito';
Chart.defaults.global.defaultFontColor = '#858796';

var ctx = document.getElementById('myAreaChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Received',
                lineTension: 0.3,
                backgroundColor: 'rgba(78,115,223,0.05)',
                borderColor: 'rgba(78,115,223,1)',
                pointRadius: 3, pointBackgroundColor: 'rgba(78,115,223,1)',
                pointBorderColor: 'rgba(78,115,223,1)', pointHoverRadius: 3,
                pointHitRadius: 10, pointBorderWidth: 2,
                data: <?= json_encode(array_map('intval', $cii ?? [])) ?>
            },{
                label: 'Issued',
                lineTension: 0.3,
                backgroundColor: 'rgba(231,74,59,0.05)',
                borderColor: '#e74a3b',
                pointRadius: 3, pointBackgroundColor: '#e74a3b',
                pointBorderColor: '#e74a3b', pointHoverRadius: 3,
                pointHitRadius: 10, pointBorderWidth: 2,
                data: <?= json_encode(array_map('intval', $coi ?? [])) ?>
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }],
                yAxes: [{ ticks: { maxTicksLimit: 5, padding: 10 }, gridLines: { color: 'rgb(234,236,244)', drawBorder: false, borderDash: [2] } }]
            },
            legend: { display: true, position: 'bottom' }
        }
    });
}

$('#expiryTable').DataTable({ pageLength: 10, lengthChange: false, searching: false, info: false, order: [] });
</script>
<?php endif; ?>

<?php if (isset($report_scripts) && $report_scripts): ?>
<script>
$(function() {
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
<?php endif; ?>
</body>
</html>
