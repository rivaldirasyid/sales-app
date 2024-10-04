<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .badge {
        min-width: 65px;
        text-align: center;
    }

    .custom-input-height {
        height: 44px;
    }

    .custom-button-height {
        height: 48px;
        line-height: 48px;
        padding: 0 15px;
        white-space: nowrap;
    }

    .input-group .form-control {
        width: 224px;
    }
</style>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">RKAP Management</h3>
                <h6 class="font-weight-normal mb-0">Kelola data RKAP berdasarkan divisi, tahun, dan bulan.</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">

                    <div class="justify-content-end d-flex">
                        <a href="<?= base_url('rkap/exportRkap/' . $year) ?>"
                            class="btn btn-outline-success custom-button-height mr-2">
                            <i class="ti-export"></i> Export RKAP
                        </a>
                        <a href="<?= base_url('rkap/create') ?>" class="btn btn-primary custom-button-height">Add New
                            RKAP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi jika ada pesan sukses atau error -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">x</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <button class="close" data-dismiss="alert">x</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Filter berdasarkan tahun dan bulan -->
<div class="row mb-3">
    <div class="col-md-12">
        <form action="" method="get" class="form-inline">
            <div class="input-group">
                <input type="number" name="year" class="form-control custom-input-height" placeholder="Year"
                    value="<?= esc($year) ?>" required>
                <select name="month" class="form-control mx-2 custom-input-height">
                    <?php
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    foreach ($months as $m) {
                        echo '<option value="' . $m . '"' . ($month == $m ? ' selected' : '') . '>' . $m . '</option>';
                    }
                    ?>
                </select>
                <button class="btn btn-primary btn-icon-text custom-input-height" type="submit">
                    <i class="ti-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel data RKAP -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">RKAP Data for <?= $year ?> - <?= $month ?></h4>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Division</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Target Revenue</th>
                                <th>Actual Revenue</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rkapData)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($rkapData as $rkap): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($rkap['division_name']) ?></td>
                                        <!-- Ubah menjadi nama divisi jika diperlukan -->
                                        <td><?= esc($rkap['year']) ?></td>
                                        <td><?= esc($rkap['month']) ?></td>
                                        <td>Rp <?= number_format($rkap['target_revenue'], 2, ',', '.') ?></td>
                                        <td>Rp <?= number_format($rkap['actual_revenue'], 2, ',', '.') ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('rkap/edit/' . $rkap['id']) ?>"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?= base_url('rkap/delete/' . $rkap['id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No RKAP data found for the selected year and month.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>