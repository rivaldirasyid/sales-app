<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <button class="close" data-dismiss="alert">x</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Load AutoNumeric -->
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.10.5/dist/autoNumeric.min.js"></script>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Prospect</h4>
                <p class="card-description">Tambah Prospek Baru</p>
                <form class="forms-sample" action="<?= base_url('prospect/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Customer</label>
                        <div class="input-group">
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Pilih Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>"><?= esc($customer['customer_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target="#addCustomerModal">
                                    <i class="ti-plus"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Atau tambahkan customer baru dengan tombol +.</small>
                    </div>

                    <div class="form-group">
                        <label for="prospect_scope">Prospect Scope</label>
                        <textarea class="form-control" id="prospect_scope" name="prospect_scope"
                            placeholder="Prospect Scope" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="estimated_revenue">Estimated Revenue</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="estimated_revenue" name="estimated_revenue"
                                placeholder="Estimated Revenue" required>
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="actual_revenue">Actual Revenue (Optional)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="actual_revenue" name="actual_revenue"
                                placeholder="Actual Revenue">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Boleh dikosongkan untuk prospect baru.</small>
                    </div>

                    <div class="form-group">
                        <label for="projected_quarter" class="form-label">Projected Quarter</label>
                        <select class="form-control" id="projected_quarter" name="projected_quarter" required>
                            <option value="">Pilih Quarter</option>
                            <option value="NEW">NEW</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="target_month_contract" class="form-label">Bulan Target</label>
                        <select class="form-control" id="target_month_contract" name="target_month_contract" required>
                            <option value="">Pilih Bulan</option>
                            <option value="JANUARI">JANUARI</option>
                            <option value="FEBRUARI">FEBRUARI</option>
                            <option value="MARET">MARET</option>
                            <option value="APRIL">APRIL</option>
                            <option value="MEI">MEI</option>
                            <option value="JUNI">JUNI</option>
                            <option value="JULI">JULI</option>
                            <option value="AGUSTUS">AGUSTUS</option>
                            <option value="SEPTEMBER">SEPTEMBER</option>
                            <option value="OKTOBER">OKTOBER</option>
                            <option value="NOVEMBER">NOVEMBER</option>
                            <option value="DESEMBER">DESEMBER</option>
                        </select>
                    </div>

                    <!-- Multi-Select for Pre-Sales Team -->
                    <div class="form-group">
                        <label for="pre_sales_id">Pre-Sales Team</label>
                        <select class="form-control js-example-basic-multiple" name="pre_sales_ids[]"
                            multiple="multiple">
                            <?php foreach ($pre_sales_team as $pre_sales): ?>
                                <option value="<?= esc($pre_sales['pre_sales_id']) ?>"><?= esc($pre_sales['status']) ?> -
                                    <?= esc($pre_sales['division_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Multi-Select for Divisions -->
                    <div class="form-group">
                        <label for="division_id">Divisions</label>
                        <select class="form-control js-example-basic-multiple" name="division_ids[]"
                            multiple="multiple">
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= esc($division['division_id']) ?>"><?= esc($division['division_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prospect_status">Prospect Status</label>
                        <select class="form-control" id="prospect_status" name="prospect_status" required>
                            <option value="CLOSED">CLOSED</option>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="HOLD">HOLD</option>
                            <option value="FAILED">FAILED</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Remarks"
                            required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="<?= base_url('prospect') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding a new customer -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Tambah Customer Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('customer/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_type">Tipe Customer</label>
                        <select class="form-control" id="customer_type" name="type" required>
                            <option value="">Pilih Tipe Customer</option>
                            <option value="KSG">KSG</option>
                            <option value="Non-KSG">Non-KSG</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customer_name">Nama Customer</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                            placeholder="Nama Customer" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Email Customer</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email"
                            placeholder="Email Customer" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">Telepon Customer</label>
                        <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                            placeholder="Telepon Customer" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_address">Alamat Customer</label>
                        <input type="text" class="form-control" id="customer_address" name="customer_address"
                            placeholder="Alamat Customer">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    new AutoNumeric('#actual_revenue', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2, // Jumlah desimal yang ingin ditampilkan
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });

    new AutoNumeric('#estimated_revenue', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });
</script>

<?= $this->endSection() ?>