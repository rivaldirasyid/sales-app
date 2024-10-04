<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

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

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pre-Sales Management</h4>
                <p class="card-description">Daftar Pre-Sales yang terdaftar</p>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="<?= base_url('pre-sales') ?>" method="get" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control custom-input-height"
                                    placeholder="Search pre-sales" value="<?= esc($search) ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-icon-text custom-input-height" type="submit">
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?= base_url('pre-sales/create') ?>" class="btn btn-primary">Add Pre-Sales</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pre_sales)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No pre-sales available</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pre_sales as $index => $ps): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($ps['name']) ?></td>
                                        <td class="text-center">
                                            <?= isset($ps['division_name']) ? esc($ps['division_name']) : '-' ?></td>
                                        <td class="text-center"><?= esc($ps['status']) ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('pre-sales/edit/' . $ps['pre_sales_id']) ?>"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?= base_url('pre-sales/delete/' . $ps['pre_sales_id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this pre-sales?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>