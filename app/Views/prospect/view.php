<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    <style>.badge {
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

    .progress {
        height: 20px;
        /* Sesuaikan tinggi sesuai kebutuhan */
    }

    .progress-bar {
        line-height: 20px;
        /* Pastikan teks berada di tengah-tengah */
        font-size: 16px;
        /* Ukuran teks pada progress bar */
    }
</style>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold"><?= esc($customer['customer_name']) ?></h3>
                <h6 class="font-weight-normal mb-0"><?= esc($prospect['prospect_scope']) ?></h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="<?= base_url($origin) ?>" class="btn btn-outline-secondary custom-button-height mr-2">
                        <i class="ti-back-left"></i> Back to List
                    </a>
                    <?php if (session()->get('role') == 'Admin' || session()->get('user_id') == $prospect['user_id']): ?>
                        <a href="<?= base_url('prospect/edit/' . $prospect['prospect_id']) ?>?origin=<?= $origin ?>"
                            class="btn btn-outline-primary custom-button-height mr-2">
                            <i class="ti-marker-alt"></i>Edit Prospect
                        </a>
                        <a href="<?= base_url('prospect/delete/' . $prospect['prospect_id']) ?>?origin=<?= $origin ?>"
                            class="btn btn-danger custom-button-height mr-2">
                            <i class="ti-trash"></i>Delete Prospect
                        </a>
                    <?php endif; ?>
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

<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title">Prospect Details</h4>
        <table class="table table-bordered table-striped">
            <tr>
                <th>Customer</th>
                <td><?= esc($customer['customer_name']) ?></td>
            </tr>
            <tr>
                <th>Prospect Scope</th>
                <td><?= esc($prospect['prospect_scope']) ?></td>
            </tr>
            <tr>
                <th>Estimated Revenue</th>
                <td>Rp <?= number_format($prospect['estimated_revenue'], 2, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Actual Revenue</th>
                <td>
                    <?php if ($prospect['actual_revenue']): ?>
                        Rp <?= number_format($prospect['actual_revenue'], 2, ',', '.') ?>
                    <?php else: ?>
                        Not Available
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Projected Quarter</th>
                <td><?= esc($prospect['projected_quarter']) ?></td>
            </tr>
            <tr>
                <th>Target Month Contract</th>
                <td><?= esc($prospect['target_month_contract']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= esc($prospect['prospect_status']) ?></td>
            </tr>
            <tr>
                <th>Divisions</th>
                <td>
                    <?php if (!empty($divisions)): ?>
                        <?= implode('<br><br>', array_map(fn($d) => esc($d['division_name']), $divisions)) ?>
                    <?php else: ?>
                        Not Assigned
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Main Sales</th>
                <td><?= esc($sales['full_name']) ?></td>
            </tr>
            <tr>
                <th>Pre-Sales Team</th>
                <td>
                    <?php if (!empty($pre_sales)): ?>
                        <?= implode('<br><br>', array_map(fn($ps) => esc($ps['pre_sales_status']) . ' - ' . esc($ps['division_name']), $pre_sales)) ?>
                    <?php else: ?>
                        No Pre-Sales Assigned
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td><?= esc($prospect['remarks']) ?></td>
            </tr>
        </table>
    </div>
</div>



<!-- Financial Details -->
<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title">Financial Details</h4>
        <?php if ($financial): ?>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>HPP</th>
                    <td>Rp <?= number_format($financial['hpp'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Plan Budget Sales</th>
                    <td>Rp <?= number_format($financial['plan_budget_sales'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Margin</th>
                    <td>Rp <?= number_format($financial['margin'], 2, ',', '.') ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No financial details available for this prospect.</p>
            <?php if (session()->get('role') == 'Admin' || session()->get('role') == 'Sales'): ?>
                <a href="<?= base_url('financial/create/' . $prospect['prospect_id']) ?>" class="btn btn-primary mt-3">Add
                    Financial</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Progress Milestones -->
<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title">Progress Milestones</h4>
        <h5>Overall Activity: <?= esc($progress) ?>%</h5>
        <div class="progress mb-3">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?= esc($progress) ?>%;"
                aria-valuenow="<?= esc($progress) ?>" aria-valuemin="0" aria-valuemax="100">
                <?= esc($progress) ?>%
            </div>
        </div>

        <!-- Milestone Table -->
        <h4>Milestones</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Milestone Name</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Progress %</th>
                    <th>Notes</th>
                    <th class="text-center">Document</th> <!-- Kolom baru untuk dokumen -->
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($milestones)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No progress data available</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($milestones as $milestone): ?>
                        <tr>
                            <td><?= esc($milestone['milestone_name']) ?></td>
                            <td class="text-center">
                                <?php if ($milestone['milestone_status'] == 'Pending'): ?>
                                    <label class="badge badge-danger">Pending</label>
                                <?php elseif ($milestone['milestone_status'] == 'In Progress'): ?>
                                    <label class="badge badge-warning">In Progress</label>
                                <?php elseif ($milestone['milestone_status'] == 'Completed'): ?>
                                    <label class="badge badge-success">Completed</label>
                                <?php else: ?>
                                    <label class="badge badge-secondary"><?= esc($milestone['milestone_status']) ?></label>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= esc($milestone['milestone_date']) ?></td>
                            <td class="text-center"><?= esc($milestone['progress_percentage']) ?>%</td>
                            <td><?= esc($milestone['notes']) ?></td>
                            <td class="text-center">
                                <?php if ($milestone['milestone_document']): ?>
                                    <a href="<?= base_url($milestone['milestone_document']) ?>" class="btn btn-success btn-sm"
                                        target="_blank">View Document</a>
                                <?php elseif ($milestone['progress_percentage'] && (session()->get('role') == 'Admin' || session()->get('role') == 'Sales')): ?>
                                    <a href="<?= base_url('milestone/addDocument/' . $milestone['milestone_id']) ?>"
                                        class="btn btn-primary btn-sm">Add Document</a>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($milestone['progress_percentage'] && (session()->get('role') == 'Admin' || session()->get('role') == 'Sales')): ?>
                                    <a href="<?= base_url('milestone/edit/' . $milestone['milestone_id'] . '?origin=prospect') ?>"
                                        class="btn btn-primary btn-sm">Edit</a>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol untuk menambah milestone hanya muncul jika milestone sebelumnya sudah completed -->
        <?php if (empty($milestones)): ?>
            <!-- Jika milestone masih kosong, tambahkan milestone pertama -->
            <a href="<?= base_url('milestone/create/' . $prospect['prospect_id'] . '/Scooping?origin=prospect') ?>"
                class="btn btn-primary mt-3">Add Scooping</a>
        <?php elseif ($nextMilestone && $previousMilestoneCompleted): ?>
            <!-- Jika milestone sebelumnya sudah completed, tambahkan milestone berikutnya -->
            <a href="<?= base_url('milestone/create/' . $prospect['prospect_id'] . '/' . urlencode($nextMilestone) . '?origin=prospect') ?>"
                class="btn btn-primary mt-3">Add <?= esc(urldecode($nextMilestone)) ?></a>
        <?php else: ?>
            <!-- Jika milestone belum complete atau tidak ada milestone berikutnya -->
            <p class="mt-3">Previous milestone is not 100% completed or no next milestone available.</p>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>