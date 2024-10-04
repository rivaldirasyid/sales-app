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
                <h4 class="card-title">Sales Management</h4>
                <p class="card-description">Daftar Sales yang terdaftar</p>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="<?= base_url('sales') ?>" method="get" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control custom-input-height"
                                    placeholder="Search sales" value="<?= esc($search) ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-icon-text custom-input-height" type="submit">
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?= base_url('sales/create') ?>" class="btn btn-primary">Add Sales</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sales)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No sales available</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sales as $index => $sale): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($sale['full_name']) ?></td>
                                        <td><?= esc($sale['email']) ?></td>
                                        <td><?= esc($sale['username']) ?></td>
                                        <td class="text-center"><?= esc($sale['role']) ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('sales/edit/' . $sale['user_id']) ?>"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?= base_url('sales/delete/' . $sale['user_id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this sales?')">Delete</a>
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