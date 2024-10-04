<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Milestones Overview</h3>
                <h6 class="font-weight-normal mb-0">Berikut ini adalah perkembangan pencapaian setiap prospek.</h6>
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
                                    placeholder="Search customer or division" value="<?= esc($search) ?>">
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
                                <th class="text-center">Division</th>
                                <th class="text-center">Main Sales</th>
                                <th class="text-center">Activity %</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($milestones) && is_array($milestones)): ?>
                                <?php
                                // Nomor urut baris
                                $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                                foreach ($milestones as $milestone): ?>
                                    <?php
                                    // Tentukan kelas baris berdasarkan progress
                                    $rowClass = '';
                                    if ($milestone['progress'] == 100) {
                                        $rowClass = 'table-success'; // Jika progress 100%
                                    }

                                    // Tentukan warna progress bar berdasarkan persentase
                                    if ($milestone['progress'] >= 75) {
                                        $progressBarClass = 'bg-success';
                                    } elseif ($milestone['progress'] >= 50) {
                                        $progressBarClass = 'bg-info';
                                    } elseif ($milestone['progress'] >= 25) {
                                        $progressBarClass = 'bg-warning';
                                    } else {
                                        $progressBarClass = 'bg-danger';
                                    }
                                    ?>
                                    <tr class="<?= $rowClass ?>"
                                        data-url="<?= base_url('milestone/view/' . $milestone['prospect_id']) ?>"
                                        style="cursor: pointer;">
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($milestone['customer_name']) ?></td>
                                        <td class="text-center">
                                            <?= nl2br(esc($milestone['division_names'])) ?>
                                        </td>
                                        <td class="text-center"><?= esc($milestone['sales_utama']) ?></td>
                                        <td class="text-center">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar <?= $progressBarClass ?>" role="progressbar"
                                                    style="width: <?= esc($milestone['progress']) ?>%;"
                                                    aria-valuenow="<?= esc($milestone['progress']) ?>" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    <?= esc($milestone['progress']) ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('milestone/view/' . $milestone['prospect_id']) ?>"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No milestones found.</td>
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
                        <?= $pager->links('milestones', 'bootstrap_pagination') ?>
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