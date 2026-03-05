<div class="row justify-content-center mt-5 pt-lg-5">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-lg-5 p-0">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900">LRHRIMS</h1>
                                <span class="text-muted">Lab Reagent Inventory System</span>
                            </div>
                            <?php if (session()->getFlashdata('message')): ?>
                                <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                            <?php endif; ?>
                            <?= form_open('login', ['class' => 'user']) ?>
                            <div class="form-group">
                                <input autofocus autocomplete="off" type="text" name="username"
                                    class="form-control form-control-user <?= (isset($validation) && $validation->hasError('username')) ? 'is-invalid' : '' ?>"
                                    placeholder="Username" value="<?= old('username') ?>">
                                <?php if (isset($validation) && $validation->hasError('username')): ?>
                                    <small class="text-danger"><?= $validation->getError('username') ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password"
                                    class="form-control form-control-user <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                    placeholder="Password">
                                <?php if (isset($validation) && $validation->hasError('password')): ?>
                                    <small class="text-danger"><?= $validation->getError('password') ?></small>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                            <div class="text-center mt-4">
                                <a class="small" href="<?= base_url('register') ?>">Create an Account</a>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
