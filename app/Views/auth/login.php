<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? $title : 'Default Title' ?></title>

    <link rel="shortcut icon" href="<?= base_url('template/images/logo-fix-mini.svg') ?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/feather/feather.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/ti-icons/css/themify-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/vendors/css/vendor.bundle.base.css') ?>">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url('template/css/vertical-layout-light/style.css') ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= base_url('template/images/krakatau-logo.png') ?>">
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="<?= base_url('template/images/krakatau-logo-full.svg') ?>" alt="logo">
                            </div>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">x</button>
                                        <b>Error !</b>
                                        <?= session()->getFlashdata('error') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">x</button>
                                        <b>Success !</b>
                                        <?= session()->getFlashdata('success') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" action="<?= base_url('login/action') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="username"
                                        id="exampleInputUsername" placeholder="Username">
                                    <?php if (isset($validation) && $validation->hasError('username')): ?>
                                        <div class="text-danger"><?= $validation->getError('username') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" name="password"
                                        id="exampleInputPassword1" placeholder="Password">
                                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                                        <div class="text-danger"><?= $validation->getError('password') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        IN</button>
                                </div>
                                <?php if (isset($error)): ?>
                                    <div class="text-danger mt-2"><?= $error ?></div>
                                <?php endif; ?>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="remember" value="1">
                                            Keep me signed in
                                        </label>
                                    </div>
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
                                </div>

                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="<?= base_url('auth/register') ?>"
                                        class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script src="<?= base_url('template/vendors/js/vendor.bundle.base.js') ?>"></script>
    <script src="<?= base_url('template/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('template/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('template/js/template.js') ?>"></script>
    <script src="<?= base_url('template/js/settings.js') ?>"></script>
    <script src="<?= base_url('template/js/todolist.js') ?>"></script>
</body>

</html>