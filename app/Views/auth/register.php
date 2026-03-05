<div class="row justify-content-center mt-5">
    <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h1 class="h4 text-gray-900">Create Account</h1>
                            </div>
                            <?php if (isset($validation)): ?>
                                <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                            <?php endif; ?>
                            <?= form_open('register', ['class' => 'user']) ?>
                            <div class="form-group">
                                <input type="text" name="des" class="form-control form-control-user" placeholder="Full Name" value="<?= old('des') ?>">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" name="username" class="form-control form-control-user" placeholder="Username" value="<?= old('username') ?>">
                                </div>
                                <div class="col-sm-6">
                                    <input type="email" name="email" class="form-control form-control-user" placeholder="Email" value="<?= old('email') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="no_telp" class="form-control form-control-user" placeholder="Phone Number" value="<?= old('no_telp') ?>">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="password2" class="form-control form-control-user" placeholder="Repeat Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                            <div class="text-center mt-3">
                                <a class="small" href="<?= base_url('login') ?>">Already have an account? Login</a>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
