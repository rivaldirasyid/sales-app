<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="<?= base_url('dashboard') ?>">
            <img src="<?= base_url('template/images/krakatau-logo-full.svg') ?>" class="mr-2" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="<?= base_url('dashboard') ?>">
            <img src="<?= base_url('template/images/logo-fix-mini.svg') ?>" alt="logo" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <!-- Notifications & Profile -->
        <!-- Notifications & Profile -->
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown d-flex align-items-center">
                <?php
                $role = session()->get('role');
                $fullName = session()->get('full_name');

                if ($role == 'Admin'): ?>
                    <span class="badge badge-primary mr-2">Admin</span>
                <?php elseif ($role == 'AM'): ?>
                    <span class="badge badge-primary mr-2">Account Manager</span>
                <?php elseif ($role == 'Sales' && !empty($fullName)): ?>
                    <span class="badge badge-primary mr-2"><?= esc($fullName) ?></span>
                <?php endif; ?>

                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="<?= base_url('template/images/faces/pp.jpg') ?>" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                        <i class="ti-power-off text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>