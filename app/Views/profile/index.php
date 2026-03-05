<div class="row justify-content-center"><div class="col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3"><h4 class="h5 m-0 font-weight-bold text-primary">My Profile</h4></div>
        <div class="card-body text-center">
            <img src="<?= base_url('assets/img/avatar/' . esc($user['photo'])) ?>" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
            <h5><?= esc($user['des']) ?></h5>
            <p class="text-muted mb-1">@<?= esc($user['username']) ?></p>
            <p class="text-muted mb-1"><?= esc($user['email']) ?></p>
            <p class="text-muted mb-3"><?= esc($user['no_telp']) ?></p>
            <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?> mb-3"><?= ucfirst($user['role']) ?></span>
            <div class="mt-3">
                <a href="<?= base_url('profile/setting') ?>" class="btn btn-primary btn-sm"><i class="fas fa-cogs mr-1"></i> Edit Profile</a>
                <a href="<?= base_url('profile/changepassword') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-lock mr-1"></i> Change Password</a>
            </div>
        </div>
    </div>
</div></div>
