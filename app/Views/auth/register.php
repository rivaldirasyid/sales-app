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
    <link rel="stylesheet" href="<?= base_url('template/css/vertical-layout-light/style.css') ?>">
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
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>
                            <h4>New here?</h4>
                            <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                            <form class="pt-3" action="<?= base_url('auth/registerAction') ?>" method="post">
                                <?= csrf_field() ?>

                                <!-- Tampilkan error validasi dari flashdata -->
                                <?php $validationErrors = session()->getFlashdata('validation'); ?>

                                <!-- Full Name -->
                                <div class="form-group">
                                    <input type="text"
                                        class="form-control form-control-lg <?= isset($validationErrors['full_name']) ? 'is-invalid' : '' ?>"
                                        name="full_name" placeholder="Full Name" value="<?= old('full_name') ?>">
                                    <!-- Tampilkan pesan error full_name jika ada -->
                                    <?php if (isset($validationErrors['full_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= $validationErrors['full_name'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <input type="text"
                                        class="form-control form-control-lg <?= isset($validationErrors['username']) ? 'is-invalid' : '' ?>"
                                        name="username" placeholder="Username" value="<?= old('username') ?>">
                                    <!-- Tampilkan pesan error username jika ada -->
                                    <?php if (isset($validationErrors['username'])): ?>
                                        <div class="invalid-feedback">
                                            <?= $validationErrors['username'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control form-control-lg <?= isset($validationErrors['email']) ? 'is-invalid' : '' ?>"
                                        name="email" placeholder="Email" value="<?= old('email') ?>">
                                    <!-- Tampilkan pesan error email jika ada -->
                                    <?php if (isset($validationErrors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?= $validationErrors['email'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <input type="password"
                                        class="form-control form-control-lg <?= isset($validationErrors['password']) ? 'is-invalid' : '' ?>"
                                        name="password" placeholder="Password">
                                    <!-- Tampilkan pesan error password jika ada -->
                                    <?php if (isset($validationErrors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?= $validationErrors['password'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="terms">
                                            I agree to all Terms & Conditions
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        UP</button>
                                </div>

                                <div class="text-center mt-4 font-weight-light">
                                    Already have an account? <a href="<?= base_url('auth/login') ?>"
                                        class="text-primary">Login</a>
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