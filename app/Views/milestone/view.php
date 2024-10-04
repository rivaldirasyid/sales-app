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
                <h3 class="font-weight-bold">Milestones Detail</h3>
                <h6 class="font-weight-normal mb-0">Berikut adalah rincian progres milestone untuk prospek ini.</h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <a href="<?= base_url('milestone') ?>" class="btn btn-outline-secondary custom-button-height mr-2">
                        <i class="ti-back-left"></i> Back to List
                    </a>
                    <a href="<?= base_url('prospect/view/' . $prospect_id) ?>"
                        class="btn btn-primary custom-button-height mr-2">
                        <i class="ti-menu"></i> Lihat Detail Prospect Ini
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

<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title">Overall Activity: <?= esc($progress) ?>%</h4>
        <div class="progress mb-3">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?= esc($progress) ?>%;"
                aria-valuenow="<?= esc($progress) ?>" aria-valuemin="0" aria-valuemax="100">
                <?= esc($progress) ?>%
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Milestone Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Progress %</th>
                        <th>Notes</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($milestones)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No milestones found.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        // Iterasi melalui milestone order dan cocokkan dengan milestone yang ada
                        foreach ($milestoneOrder as $milestoneName):
                            $foundMilestone = null;
                            foreach ($milestones as $milestone) {
                                if ($milestone['milestone_name'] === $milestoneName) {
                                    $foundMilestone = $milestone;
                                    break;
                                }
                            }
                            ?>
                            <tr>
                                <td><?= esc($milestoneName) ?></td>
                                <td>
                                    <?php if ($foundMilestone): ?>
                                        <?php if ($foundMilestone['milestone_status'] === 'Completed'): ?>
                                            <label class="badge badge-success">Completed</label>
                                        <?php else: ?>
                                            <label class="badge badge-warning">In progress</label>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <label class="badge badge-danger">Pending</label>
                                    <?php endif; ?>
                                </td>
                                <td><?= $foundMilestone ? esc($foundMilestone['milestone_date']) : '-' ?></td>
                                <td><?= $foundMilestone ? esc($foundMilestone['progress_percentage']) . '%' : '-' ?>
                                </td>
                                <td><?= $foundMilestone ? esc($foundMilestone['notes']) : '-' ?></td>
                                <td>
                                    <?php if ($foundMilestone): ?>
                                        <?php if ($foundMilestone['milestone_document']): ?>
                                            <a href="<?= base_url($foundMilestone['milestone_document']) ?>" target="_blank"
                                                class="btn btn-success btn-sm">View Document</a>
                                        <?php else: ?>
                                            <!-- Jika role bukan AM, izinkan tambah dokumen -->
                                            <?php if (session()->get('role') !== 'AM'): ?>
                                                <a href="<?= base_url('milestone/addDocument/' . $foundMilestone['milestone_id']) ?>"
                                                    class="btn btn-primary btn-sm">Add Document</a>
                                            <?php else: ?>
                                                <p>-</p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p>-</p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Jika role bukan AM, izinkan untuk mengedit milestone -->
                                    <?php if ($foundMilestone && session()->get('role') !== 'AM'): ?>
                                        <a href="<?= base_url('milestone/edit/' . $foundMilestone['milestone_id'] . '?origin=milestone') ?>"
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
        </div>

        <!-- Tampilkan tombol untuk menambah milestone hanya jika milestone sebelumnya sudah selesai 100% dan role bukan AM -->
        <?php if ($nextMilestone && $previousMilestoneCompleted && session()->get('role') !== 'AM'): ?>
            <a href="<?= base_url('milestone/create/' . $prospect_id . '/' . urlencode($nextMilestone) . '?origin=milestone') ?>"
                class="btn btn-primary mt-3">Add <?= esc(urldecode($nextMilestone)) ?></a>
        <?php elseif (!$nextMilestone): ?>
            <p class="mt-3">All milestones are completed.</p>
        <?php else: ?>
            <p class="mt-3">Previous milestone is not 100% completed.</p>
        <?php endif; ?>


    </div>
</div>

<?= $this->endSection() ?>