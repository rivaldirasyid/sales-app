<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.10.5/dist/autoNumeric.min.js"></script>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Create New RKAP</h3>
                <h6 class="font-weight-normal mb-0">Tambahkan data RKAP baru untuk divisi, tahun, dan bulan tertentu.
                </h6>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form RKAP</h4>
                <form action="<?= base_url('rkap/save') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="division_id">Division</label>
                        <select name="division_id" id="division_id" class="form-control custom-input-height" required>
                            <option value="" disabled selected>Select Division</option>
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= esc($division['division_id']) ?>"><?= esc($division['division_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="number" name="year" id="year" class="form-control custom-input-height"
                            value="<?= date('Y') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-control custom-input-height" required>
                            <?php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            foreach ($months as $month) {
                                echo '<option value="' . $month . '">' . $month . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="target_revenue">Target Revenue</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="target_revenue" name="target_revenue"
                                placeholder="Target Revenue" required>
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary custom-button-height">Submit</button>
                    <a href="<?= base_url('rkap') ?>" class="btn btn-secondary custom-button-height">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    new AutoNumeric('#target_revenue', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2, // Jumlah desimal yang ingin ditampilkan
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });
</script>

<?= $this->endSection() ?>