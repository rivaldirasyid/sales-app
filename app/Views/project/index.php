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
                <h3 class="font-weight-bold">Projects List</h3>
                <h6 class="font-weight-normal mb-0">Daftar proyek yang telah dikonversi dari prospek </h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="<?= base_url('project/export') ?>"
                        class="btn btn-outline-success custom-button-height mr-2">
                        <i class="ti-export"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <h4 class="card-title">Total Actual Revenue: Rp
                    <?= number_format($totalActualRevenue, 2, ',', '.') ?>
                </h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="<?= base_url('project') ?>" method="get" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control custom-input-height"
                                    placeholder="Search customer or scope" value="<?= esc($search) ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-icon-text custom-input-height" type="submit">
                                        <i class="ti-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 text-right">
                        <form action="" method="get" class="form-inline ml-auto" style="justify-content: flex-end;">
                            <label for="limit">Show</label>
                            <select name="limit" id="limit" class="form-control mx-2 custom-input-height"
                                onchange="this.form.submit()">
                                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            </select>
                            <label for="limit">entries</label>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Scope</th>
                                <th class="text-center">Main Sales</th>
                                <th>Estimated Revenue</th>
                                <th>Actual Revenue</th>
                                <th>Remark</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No projects available</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage()); ?>
                                <?php foreach ($projects as $project): ?>
                                    <tr data-url="<?= base_url('prospect/view/' . $project['prospect_id'] . '?origin=project') ?>"
                                        style="cursor: pointer;">
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($project['customer_name']) ?></td>
                                        <td><?= esc($project['prospect_scope']) ?></td>
                                        <td class="text-center"><?= esc($project['sales_utama']) ?></td>
                                        <td>Rp <?= number_format($project['estimated_revenue'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php if (!empty($project['actual_revenue'])): ?>
                                                Rp <?= number_format($project['actual_revenue'], 2, ',', '.') ?>
                                            <?php else: ?>
                                                <span class="text-muted">Not Available</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($project['remarks']) ?></td>
                                        <td class="text-center">
                                            <?php if ($project['prospect_status'] == 'CLOSED'): ?>
                                                <label class="badge badge-pill badge-success">CLOSED</label>
                                            <?php elseif ($project['prospect_status'] == 'ACTIVE'): ?>
                                                <label class="badge badge-pill badge-secondary">ACTIVE</label>
                                            <?php elseif ($project['prospect_status'] == 'HOLD'): ?>
                                                <label class="badge badge-pill badge-warning">HOLD</label>
                                            <?php elseif ($project['prospect_status'] == 'FAILED'): ?>
                                                <label class="badge badge-pill badge-danger">FAILED</label>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= base_url('prospect/view/' . $project['prospect_id'] . '?origin=project') ?>"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Showing
                            <?= ($pager->getCurrentPage('projects') - 1) * $pager->getPerPage('projects') + 1 ?>
                            to
                            <?= min($pager->getCurrentPage('projects') * $pager->getPerPage('projects'), $pager->getTotal('projects')) ?>
                            of <?= $pager->getTotal('projects') ?> entries
                        </p>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <?= $pager->links('projects', 'bootstrap_pagination') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('tr[data-url]').forEach(row => {
        row.addEventListener('click', function () {
            window.location.href = this.dataset.url;
        });
    });
</script>

<?= $this->endSection() ?>