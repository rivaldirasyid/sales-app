<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">x</button> <b>Success!</b>
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
<?php endif; ?>

<?php
$currentYear = date('Y');
$startYear = $currentYear - 4; // 5 tahun terakhir
?>

<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Welcome <?= esc($fullName) ?></h3>
                <h6 class="font-weight-normal mb-0">All systems are running <span class="text-primary">smoothly!</span>
                </h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                            id="dropdownDataYear" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> <span id="selectedYear"><?= $currentYear ?> (Now)</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownDataYear">
                            <?php for ($year = $startYear; $year <= $currentYear; $year++): ?>
                                <a class="dropdown-item dropdown-year" href="#" data-year="<?= $year ?>">
                                    <?= $year ?>
                                </a>
                            <?php endfor; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card tale-bg">
            <div class="card-people mt-auto">
                <img src="<?= base_url('template/images/dashboard/people.svg') ?>" alt="people">
            </div>
        </div>
    </div>
    <div class="col-md-7 grid-margin transparent">
        <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Estimated Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2">
                            <?= number_format($dashboardSummary['total_estimated_revenue'], 0, ',', '.'); ?>
                        </p>
                        <p>from All Prospects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Actual Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2">
                            <?= number_format($dashboardSummary['total_actual_revenue'], 0, ',', '.'); ?>
                        </p>
                        <p>from All Projects</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Number of Prospects</p>
                        <p class="fs-30 mb-2">
                            <?= $dashboardSummary['total_prospects']; ?>
                        </p>
                        <p>from All Sales</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Converted Prospects</p>
                        <p class="fs-30 mb-2">
                            <?= $dashboardSummary['total_converted_prospects']; ?>
                        </p>
                        <p>from All Prospects</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div id="topSales" class="carousel slide detailed-report-carousel position-static pt-2"
                    data-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Carousel Item untuk Top Sales Reps -->
                        <div class="carousel-item active">
                            <div class="row mb-3">
                                <div class="col-md-6 border-right">
                                    <div class="ml-xl-3 mt-3">
                                        <p class="card-title">Top Sales of The Year</p>
                                        <?php
                                        // Ambil revenue dari sales rep teratas
                                        $topSalesRepRevenue = 0;
                                        $topSalesRepName = "No Data"; // Default nama jika tidak ada sales rep
                                        if (!empty($topSalesReps)) {
                                            $topSalesRepRevenue = $topSalesReps[0]['total_revenue'];
                                            $topSalesRepName = $topSalesReps[0]['full_name'];
                                        }
                                        ?>
                                        <h1 class="text-primary">
                                            Rp<?= number_format($topSalesRepRevenue, 0, ',', '.'); ?></h1>
                                        <!-- Nama Sales Rep Teratas -->
                                        <h3 class="font-weight-500 mb-xl-4 text-primary">Revenue from
                                            <?= esc($topSalesRepName); ?>
                                        </h3>
                                        <p class="mb-2 mb-xl-0">The top-performing sales rep based on actual revenue.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ml-xl-3 mt-3">
                                        <p class="card-title">Leaderboard</p>
                                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Sales Name</th>
                                                        <th>Total Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $index = 0;
                                                    foreach ($topSalesReps as $rep):
                                                        $index++;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php if ($index === 1): ?>
                                                                    <i class="ti-crown text-warning"></i>
                                                                <?php else: ?>
                                                                    <?= $index; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-muted"><?= esc($rep['full_name']); ?></td>
                                                            <td class="font-weight-bold mb-0 text-primary">Rp.
                                                                <?= number_format($rep['total_revenue'], 0, ',', '.'); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($topSalesReps)): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">No sales data
                                                                available</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carousel Item untuk Top Customers -->
                        <div class="carousel-item">
                            <div class="row mb-3">
                                <div class="col-md-5 border-right">
                                    <div class="ml-xl-3 mt-3">
                                        <p class="card-title">Top Customers of The Year</p>
                                        <?php
                                        // Ambil revenue dari customer teratas
                                        $topCustomerRevenue = 0;
                                        $topCustomerName = "No Data"; // Default nama jika tidak ada customer
                                        if (!empty($topCustomers)) {
                                            $topCustomerRevenue = $topCustomers[0]['total_revenue'];
                                            $topCustomerName = $topCustomers[0]['customer_name'];
                                        }
                                        ?>
                                        <h1 class="text-primary">
                                            Rp<?= number_format($topCustomerRevenue, 0, ',', '.'); ?></h1>
                                        <!-- Nama Customer Teratas -->
                                        <h3 class="font-weight-500 mb-xl-4 text-primary"> Revenue from
                                            <?= esc($topCustomerName); ?>
                                        </h3>
                                        <p class="mb-2 mb-xl-0">The top-performing customer based on actual revenue.</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="ml-xl-3 mt-3">
                                        <p class="card-title">Leaderboard</p>
                                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Customer Name</th>
                                                        <th>Total Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $index = 0;
                                                    foreach ($topCustomers as $customer):
                                                        $index++;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php if ($index === 1): ?>
                                                                    <i class="ti-crown text-warning"></i>
                                                                <?php else: ?>
                                                                    <?= $index; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-muted"><?= esc($customer['customer_name']); ?>
                                                            </td>
                                                            <td class="font-weight-bold mb-0 text-primary">Rp.
                                                                <?= number_format($customer['total_revenue'], 0, ',', '.'); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($topCustomers)): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">No customer data
                                                                available</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Carousel Controls -->
                    <a class="carousel-control-prev" href="#topSales" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#topSales" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Estimated Revenue by Customer Type</h4>
                <canvas id="estimatedRevenueByTypeChartAllUsers"></canvas>
                <h6 class="font-weight-normal mt-3">from All <span class="text-primary">Prospects.</span>
                </h6>
                <!-- Chart untuk estimated revenue berdasarkan tipe customer dari semua user -->
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Actual Revenue by Customer Type</h4>
                <canvas id="actualRevenueByTypeChartAllUsers"></canvas>
                <h6 class="font-weight-normal mb-0 mt-3">from All <span class="text-primary">Prospects.</span>
                </h6>
                <!-- Chart untuk actual revenue berdasarkan tipe customer dari semua user -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var currentYear = <?= $currentYear ?>;

        // Fungsi untuk memperbarui data dashboard berdasarkan tahun
        function updateDashboardData(year) {
            $.ajax({
                url: '/dashboard/data/' + year,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Cek apakah data berhasil diterima
                    console.log("Data diterima: ", response);

                    // Perbarui tampilan kartu ringkasan
                    $('#selectedYear').text(year);
                    $('.card-tale .fs-30').text(new Intl.NumberFormat('id-ID').format(response.dashboardSummary.total_estimated_revenue));
                    $('.card-dark-blue .fs-30').text(new Intl.NumberFormat('id-ID').format(response.dashboardSummary.total_actual_revenue));
                    $('.card-light-blue .fs-30').text(response.dashboardSummary.total_prospects);
                    $('.card-light-danger .fs-30').text(response.dashboardSummary.total_converted_prospects);

                },
                error: function () {
                    alert('Gagal memperbarui data dashboard untuk tahun yang dipilih.');
                }
            });
        }

        // Tangani klik pada dropdown year
        $(document).on('click', '.dropdown-year', function (event) {
            event.preventDefault(); // Cegah perilaku default dari <a>
            var selectedYear = $(this).data('year');
            console.log('Tahun yang dipilih: ', selectedYear); // Debugging
            updateDashboardData(selectedYear); // Perbarui data berdasarkan tahun yang dipilih
        });

        // Muat data awal untuk tahun saat ini
        updateDashboardData(currentYear);
    });

</script>

<?= $this->endSection() ?>