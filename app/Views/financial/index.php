<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Financial Details</h3>
                <h6 class="font-weight-normal mb-0">Daftar keuangan terkait prospek yang terdaftar.</h6>
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
                                <th>Estimated Revenue</th>
                                <th>HPP</th>
                                <th>Plan Budget Sales</th>
                                <th>Margin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($financials) && is_array($financials)): ?>
                                <?php
                                $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                                foreach ($financials as $financial): ?>
                                    <tr data-url="<?= base_url('prospect/view/' . $financial['prospect_id'] . '?origin=financial') ?>"
                                        style="cursor: pointer;">
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($financial['customer_name']) ?></td>
                                        <td><?= esc($financial['prospect_scope']) ?></td>
                                        <td>Rp <?= number_format($financial['estimated_revenue'], 2, ',', '.') ?></td>
                                        <td>Rp <?= number_format($financial['hpp'], 2, ',', '.') ?></td>
                                        <td>Rp <?= number_format($financial['plan_budget_sales'], 2, ',', '.') ?></td>
                                        <td>Rp <?= number_format($financial['margin'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('prospect/view/' . $financial['prospect_id'] . '?origin=financial') ?>"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No financial details found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Showing
                            <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?>
                            to
                            <?= min($pager->getCurrentPage() * $pager->getPerPage(), $total) ?>
                            of <?= $total ?> entries
                        </p>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <?= $pager->links('financials', 'bootstrap_pagination') ?>
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