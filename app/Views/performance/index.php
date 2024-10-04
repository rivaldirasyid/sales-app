<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<Style>
    .icon-background {
        position: absolute;
        top: 10px;
        right: 10px;
        bottom: 10px;
        font-size: 100px;
        color: rgba(0, 0, 0, 0.1);
        pointer-events: none;
        z-index: 0;
    }
</Style>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Monthly Performance Summary</h3>
                <h6 class="font-weight-normal mb-0">Ringkasan kinerja Anda bulan ini dan keseluruhan.</h6>
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
    <div class="col-md-12 grid-margin transparent">
        <div class="row d-flex justify-content-between flex-wrap">
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Total Prospects</p>
                        <p class="fs-30 mb-2"><?= $overallSummary['total_prospects']; ?></p>
                        <p>Secara Keseluruhan</p>
                        <i class="mdi mdi-account icon-background"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Converted Prospects</p>
                        <p class="fs-30 mb-2"><?= $overallSummary['converted_prospects']; ?></p>
                        <p>Secara Keseluruhan</p>
                        <i class="mdi mdi-account-check icon-background"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Estimated Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2"><?= number_format($overallSummary['estimated_revenue'], 0, ',', '.'); ?>
                        </p>
                        <p>Secara Keseluruhan</p>
                        <i class="mdi mdi-timer icon-background"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Actual Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2"><?= number_format($overallSummary['actual_revenue'], 0, ',', '.'); ?>
                        </p>
                        <p>Secara Keseluruhan</p>
                        <i class="mdi mdi-checkbox-marked icon-background"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Bagian Kiri: Chart -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Prospect Bulan ini By Type (Pie Chart)</h4>
                <canvas id="totalProspectsChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Bagian Kanan: Informasi Lain -->
    <div class="col-md-6 grid-margin transparent">
        <div class="row">
            <!-- Total Prospects bulan ini-->
            <div class="col-md-6 stretch-card transparent">
                <div class="card-body d-flex flex-column">
                    <h5 class="mb-1">Total Prospects</h5>
                    <p>Bulan ini</p>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-account text-primary icon-lg mr-3"></i>
                        <h3 class="mb-0 "><?= $monthlySummary['total_prospects']; ?></h3>
                    </div>
                </div>
            </div>
            <!-- Converted Prospects bulan ini-->
            <div class="col-md-6 stretch-card transparent">
                <div class="card-body d-flex flex-column">
                    <h5 class="mb-1">Converted Prospects</h5>
                    <p>Bulan ini</p>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-account-check text-primary icon-lg mr-3"></i>
                        <h3 class="mb-0 "><?= $monthlySummary['converted_prospects']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Estimated revenue bulan ini -->
            <div class="col-md-6 stretch-card transparent">
                <div class="card-body d-flex flex-column">
                    <h5 class="mb-1">Estimated Revenue</h5>
                    <p>Bulan ini</p>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-finance text-primary icon-lg mr-3"></i>
                        <h3 class="mb-0 ">Rp<?= number_format($monthlySummary['estimated_revenue'], 0, ',', '.'); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <!-- Actual revenue bulan ini -->
            <div class="col-md-6 stretch-card transparent">
                <div class="card-body d-flex flex-column">
                    <h5 class="mb-1">Actual Revenue</h5>
                    <p>Bulan ini</p>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-cash text-primary icon-lg mr-3"></i>
                        <h3 class="mb-0 ">Rp<?= number_format($monthlySummary['actual_revenue'], 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Estimated Revenue (Line Chart)</h4>
                <canvas id="salesChart"></canvas> <!-- Ganti ID ke lineChart -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Actual Revenue (Bar Chart)</h4>
                <canvas id="financialChart"></canvas> <!-- Ganti ID ke barChart -->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Estimated Revenue by Customer Type (Bar Chart)</h4>
                <canvas id="estimatedRevenueByTypeChart"></canvas>
                <!-- Chart untuk estimated revenue berdasarkan tipe customer -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Actual Revenue by Customer Type (Bar Chart)</h4>
                <canvas id="actualRevenueByTypeChart"></canvas>
                <!-- Chart untuk actual revenue berdasarkan tipe customer -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>