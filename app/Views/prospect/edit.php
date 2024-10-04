<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.10.5/dist/autoNumeric.min.js"></script>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Prospect</h4>
                <p class="card-description">Ubah data prospek</p>
                <form class="forms-sample" action="<?= base_url('prospect/update/' . $prospect['prospect_id']) ?>"
                    method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="customer_id">Customer</label>
                        <div class="input-group">
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Pilih Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>"
                                        <?= $prospect['customer_id'] == $customer['customer_id'] ? 'selected' : '' ?>>
                                        <?= esc($customer['customer_name']) ?>
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
                    </div>

                    <div class="form-group">
                        <label for="prospect_scope">Prospect Scope</label>
                        <textarea class="form-control" id="prospect_scope" name="prospect_scope"
                            required><?= esc($prospect['prospect_scope']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="estimated_revenue">Estimated Revenue</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="estimated_revenue" name="estimated_revenue"
                                value="<?= intval($prospect['estimated_revenue']) ?>" required>
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
                                value="<?= intval($prospect['actual_revenue']) ?>">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="projected_quarter" class="form-label">Projected Quarter</label>
                        <select class="form-control" id="projected_quarter" name="projected_quarter" required>
                            <option value="">Pilih Quarter</option>
                            <option value="NEW" <?= $prospect['projected_quarter'] == 'NEW' ? 'selected' : '' ?>>NEW
                            </option>
                            <option value="Q1" <?= $prospect['projected_quarter'] == 'Q1' ? 'selected' : '' ?>>Q1</option>
                            <option value="Q2" <?= $prospect['projected_quarter'] == 'Q2' ? 'selected' : '' ?>>Q2</option>
                            <option value="Q3" <?= $prospect['projected_quarter'] == 'Q3' ? 'selected' : '' ?>>Q3</option>
                            <option value="Q4" <?= $prospect['projected_quarter'] == 'Q4' ? 'selected' : '' ?>>Q4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="target_month_contract" class="form-label">Bulan Target</label>
                        <select class="form-control" id="target_month_contract" name="target_month_contract" required>
                            <option value="">Pilih Bulan</option>
                            <option value="JANUARI" <?= $prospect['target_month_contract'] == 'JANUARI' ? 'selected' : '' ?>>JANUARI</option>
                            <option value="FEBRUARI" <?= $prospect['target_month_contract'] == 'FEBRUARI' ? 'selected' : '' ?>>FEBRUARI</option>
                            <option value="MARET" <?= $prospect['target_month_contract'] == 'MARET' ? 'selected' : '' ?>>
                                MARET</option>
                            <option value="APRIL" <?= $prospect['target_month_contract'] == 'APRIL' ? 'selected' : '' ?>>
                                APRIL</option>
                            <option value="MEI" <?= $prospect['target_month_contract'] == 'MEI' ? 'selected' : '' ?>>MEI
                            </option>
                            <option value="JUNI" <?= $prospect['target_month_contract'] == 'JUNI' ? 'selected' : '' ?>>JUNI
                            </option>
                            <option value="JULI" <?= $prospect['target_month_contract'] == 'JULI' ? 'selected' : '' ?>>JULI
                            </option>
                            <option value="AGUSTUS" <?= $prospect['target_month_contract'] == 'AGUSTUS' ? 'selected' : '' ?>>AGUSTUS</option>
                            <option value="SEPTEMBER" <?= $prospect['target_month_contract'] == 'SEPTEMBER' ? 'selected' : '' ?>>SEPTEMBER</option>
                            <option value="OKTOBER" <?= $prospect['target_month_contract'] == 'OKTOBER' ? 'selected' : '' ?>>OKTOBER</option>
                            <option value="NOVEMBER" <?= $prospect['target_month_contract'] == 'NOVEMBER' ? 'selected' : '' ?>>NOVEMBER</option>
                            <option value="DESEMBER" <?= $prospect['target_month_contract'] == 'DESEMBER' ? 'selected' : '' ?>>DESEMBER</option>
                        </select>
                    </div>


                    <!-- Pre-Sales Team Multi-Select -->
                    <div class="form-group">
                        <label for="pre_sales_id">Pre-Sales Team</label>
                        <select class="form-control js-example-basic-multiple" name="pre_sales_ids[]"
                            multiple="multiple">
                            <?php foreach ($pre_sales_team as $pre_sales): ?>
                                <option value="<?= esc($pre_sales['pre_sales_id']) ?>"
                                    <?= in_array($pre_sales['pre_sales_id'], $selectedPreSales) ? 'selected' : '' ?>>
                                    <?= esc($pre_sales['status']) ?> - <?= esc($pre_sales['division_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Divisions Multi-Select -->
                    <div class="form-group">
                        <label for="division_id">Divisions</label>
                        <select class="form-control js-example-basic-multiple" name="division_ids[]"
                            multiple="multiple">
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= esc($division['division_id']) ?>" <?= in_array($division['division_id'], $selectedDivisions) ? 'selected' : '' ?>>
                                    <?= esc($division['division_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prospect_status">Prospect Status</label>
                        <select class="form-control" id="prospect_status" name="prospect_status" required>
                            <option value="CLOSED" <?= $prospect['prospect_status'] == 'CLOSED' ? 'selected' : '' ?>>CLOSED
                            </option>
                            <option value="ACTIVE" <?= $prospect['prospect_status'] == 'ACTIVE' ? 'selected' : '' ?>>ACTIVE
                            </option>
                            <option value="HOLD" <?= $prospect['prospect_status'] == 'HOLD' ? 'selected' : '' ?>>HOLD
                            </option>
                            <option value="FAILED" <?= $prospect['prospect_status'] == 'FAILED' ? 'selected' : '' ?>>
                                FAILED</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks"
                            name="remarks"><?= esc($prospect['remarks']) ?></textarea>
                    </div>

                    <?php if ($financial): ?>
                        <h4 class="card-title">Edit Financial Details</h4>
                        <div class="form-group">
                            <label for="hpp">HPP (Harga Pokok Penjualan)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="hpp" name="hpp"
                                    value="<?= intval($financial['hpp']) ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plan_budget_sales">Plan Budget Sales</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="plan_budget_sales" name="plan_budget_sales"
                                    value="<?= intval($financial['plan_budget_sales']) ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="margin">Margin</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="margin" name="margin"
                                    value="<?= intval($financial['margin']) ?>" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="<?= base_url('prospect/view/' . $prospect['prospect_id']) ?>"
                        class="btn btn-light">Cancel</a>
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

                    <!-- Hidden input untuk origin dan prospect_id -->
                    <input type="hidden" name="origin" value="edit"> <!-- Set origin dari mana form ini diakses -->
                    <input type="hidden" name="prospect_id" value="<?= $prospect['prospect_id'] ?>">
                    <!-- Mengirim prospect_id -->

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

    new AutoNumeric('#hpp', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2, // Jumlah desimal yang ingin ditampilkan
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });

    new AutoNumeric('#margin', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });

    new AutoNumeric('#plan_budget_sales', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
        unformatOnSubmit: true // Menjadikan input nilai bersih ketika dikirimkan
    });
</script>


<?= $this->endSection() ?>