<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<script src="<?= base_url('template/js/settings.js') ?>"></script>

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
                <h3 class="font-weight-bold">Prospects List</h3>
                <h6 class="font-weight-normal mb-0">Daftar prospek yang terdaftar di sistem.</h6>

            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="<?= base_url('prospect/export') ?>"
                        class="btn btn-outline-success custom-button-height mr-2">
                        <i class="ti-export"></i> Export
                    </a>
                    <?php if (session()->get('role') == 'Admin' || session()->get('role') == 'Sales'): ?>
                        <a href="<?= base_url('prospect/create') ?>" class="btn btn-primary custom-button-height">Add New
                            Prospect</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload File Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('prospect/import') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileUpload">Pilih File Excel:</label>
                        <input type="file" name="file" id="fileUpload" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
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
                <h4 class="card-title">Total Estimated Revenue: Rp
                    <?= number_format($totalEstimatedRevenue, 2, ',', '.') ?>
                </h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="" method="get" class="form-inline">
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
                                <th>Main Sales</th>
                                <th>Estimated Revenue</th>
                                <th class="text-center">Quarter</th>
                                <th>Target Month</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($prospects): ?>
                                <?php $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage()); ?>
                                <?php foreach ($prospects as $prospect): ?>
                                    <tr data-url="<?= base_url('prospect/view/' . $prospect['prospect_id'] . '?origin=prospect') ?>"
                                        style="cursor: pointer;">
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($prospect['customer_name']) ?></td>
                                        <td><?= esc($prospect['prospect_scope']) ?></td>
                                        <td><?= esc($prospect['sales_utama']) ?></td>
                                        <td>Rp <?= number_format($prospect['estimated_revenue'], 2, ',', '.') ?></td>
                                        <td class="text-center"><?= esc($prospect['projected_quarter']) ?></td>
                                        <td><?= esc($prospect['target_month_contract']) ?></td>
                                        <td class="text-center">
                                            <?php if ($prospect['prospect_status'] == 'CLOSED'): ?>
                                                <label class="badge badge-pill badge-success">CLOSED</label>
                                            <?php elseif ($prospect['prospect_status'] == 'ACTIVE'): ?>
                                                <label class="badge badge-pill badge-secondary">ACTIVE</label>
                                            <?php elseif ($prospect['prospect_status'] == 'HOLD'): ?>
                                                <label class="badge badge-pill badge-warning">HOLD</label>
                                            <?php elseif ($prospect['prospect_status'] == 'FAILED'): ?>
                                                <label class="badge badge-pill badge-danger">FAILED</label>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= base_url('prospect/view/' . $prospect['prospect_id'] . '?origin=prospect') ?>"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No prospects found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Showing
                            <?= ($pager->getCurrentPage('prospects') - 1) * $pager->getPerPage('prospects') + 1 ?>
                            to
                            <?= min($pager->getCurrentPage('prospects') * $pager->getPerPage('prospects'), $pager->getTotal('prospects')) ?>
                            of <?= $pager->getTotal('prospects') ?> entries
                        </p>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <?= $pager->links('prospects', 'bootstrap_pagination') ?>
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