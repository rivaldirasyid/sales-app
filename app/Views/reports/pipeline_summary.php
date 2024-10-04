<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                <h3 class="font-weight-bold">Pipeline Summary</h3>
                <h6 class="font-weight-normal mb-0">Ringkasan prospek yang ada dalam pipeline.</h6>
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
                    <a href="<?= base_url('reports/exportPipelineSummary/' . $currentYear) ?>" id="exportBtn"
                        class="btn btn-outline-success custom-button-height ml-2">
                        <i class="ti-export"></i> Export Closed
                    </a>
                    <a href="<?= base_url('reports/exportPipelineSummary/' . $currentYear) ?>" id="exportAllBtn"
                        class="btn btn-outline-success custom-button-height ml-2">
                        <i class="ti-export"></i> Export All
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12 grid-margin transparent">
        <div class="row d-flex justify-content-between flex-wrap">
            <div class="col-md-2 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Total Prospects</p>
                        <p class="fs-30 mb-2" id="totalProspects"><?= esc($totalProspects) ?></p>
                        <p id="yearText">Tahun <?= $year ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Converted Prospects</p>
                        <p class="fs-30 mb-2" id="totalConverted"><?= esc($totalConvertedProspects) ?></p>
                        <p id="yearText1">Tahun <?= $year ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Estimated Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2" id="totalEstimatedRevenue">
                            <?= number_format($totalEstimatedRevenue, 0, ',', '.') ?>
                        </p>
                        <p id="yearText2">Tahun <?= $year ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Actual Revenue</p>
                        <p>Rp</p>
                        <p class="fs-30 mb-2" id="totalActualRevenue">
                            <?= number_format($totalActualRevenue, 0, ',', '.') ?>
                        </p>
                        <p id="yearText3">Tahun <?= $year ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Prospek</h4>
        <div class="row mb-3">
            <div class="col-md-6">
                <form id="searchForm" action="" method="get" class="form-inline">
                    <div class="input-group">
                        <input type="text" id="searchQuery" name="search" class="form-control custom-input-height"
                            placeholder="Search customer or scope" value="<?= esc($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-icon-text custom-input-height" type="submit">
                                <i class="ti-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="pipelineTable">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Scope</th>
                        <th class="text-center">Status</th>
                        <th>Estimated Revenue</th>
                        <th>Actual Revenue</th>
                        <th class="text-center">Sales Utama</th>
                        <th class="text-center">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($prospects)): ?>
                        <?php $no = 1; ?> <!-- Inisialisasi variabel nomor -->
                        <?php foreach ($prospects as $prospect): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($prospect['customer_name']) ?></td>
                                <td><?= esc($prospect['prospect_scope']) ?></td>
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
                                <td>Rp <?= number_format($prospect['estimated_revenue'], 2, ',', '.') ?></td>
                                <td>Rp <?= number_format($prospect['actual_revenue'], 2, ',', '.') ?></td>
                                <td class="text-center"><?= esc($prospect['sales_utama']) ?></td>
                                <td class="text-center"><?= esc($prospect['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada prospek ditemukan untuk tahun
                                <?= esc($year) ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<script>
    $(document).ready(function () {
        // Fungsi untuk memperbarui data pipeline berdasarkan tahun
        function updatePipelineData(year, searchQuery) {
            $.ajax({
                url: '/reports/pipeline_summary/data/' + year,
                type: 'GET',
                data: { search: searchQuery },
                dataType: 'json',
                success: function (response) {
                    $('#selectedYear').text(year);
                    $('#totalProspects').text(response.totalProspects);
                    $('#totalConverted').text(response.totalConvertedProspects);
                    $('#totalEstimatedRevenue').text(new Intl.NumberFormat('id-ID').format(response.totalEstimatedRevenue));
                    $('#totalActualRevenue').text(new Intl.NumberFormat('id-ID').format(response.totalActualRevenue));

                    $('#yearText').text('Tahun ' + year);
                    $('#yearText1').text('Tahun ' + year);
                    $('#yearText2').text('Tahun ' + year);
                    $('#yearText3').text('Tahun ' + year);

                    $('#exportBtn').attr('href', '/reports/exportPipelineSummary/' + year);
                    $('#exportAllBtn').attr('href', '/reports/exportAllProspectsByYear/' + year);

                    var $tbody = $('#pipelineTable tbody');
                    $tbody.empty();

                    if (response.prospects.length === 0) {
                        $tbody.append('<tr><td colspan="8" class="text-center">Tidak ada prospek ditemukan untuk tahun ' + year + '.</td></tr>');
                    } else {
                        $.each(response.prospects, function (index, prospect) {
                            // Tentukan warna badge berdasarkan status
                            var statusBadge = '';
                            switch (prospect.prospect_status) {
                                case 'ACTIVE':
                                    statusBadge = '<span class="badge badge-secondary">ACTIVE</span>';
                                    break;
                                case 'CLOSED':
                                    statusBadge = '<span class="badge badge-success">CLOSED</span>';
                                    break;
                                case 'HOLD':
                                    statusBadge = '<span class="badge badge-warning">HOLD</span>';
                                    break;
                                case 'FAILED':
                                    statusBadge = '<span class="badge badge-danger">FAILED</span>';
                                    break;
                                default:
                                    statusBadge = '<span class="badge badge-info">' + prospect.prospect_status + '</span>';
                                    break;
                            }

                            $tbody.append('<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + prospect.customer_name + '</td>' +
                                '<td>' + prospect.prospect_scope + '</td>' +
                                '<td class="text-center">' + statusBadge + '</td>' +  // Tampilkan badge status di sini
                                '<td>Rp ' + new Intl.NumberFormat('id-ID').format(prospect.estimated_revenue) + '</td>' +
                                '<td>Rp ' + new Intl.NumberFormat('id-ID').format(prospect.actual_revenue) + '</td>' +
                                '<td class="text-center">' + prospect.sales_utama + '</td>' +
                                '<td class="text-center">' + prospect.created_at + '</td>' +
                                '</tr>');
                        });
                    }

                },
                error: function () {
                    alert('Gagal memperbarui data pipeline.');
                }
            });
        }

        // Handler untuk memilih tahun di dropdown
        $(document).on('click', '.dropdown-year', function (event) {
            event.preventDefault();
            var selectedYear = $(this).data('year');
            var searchQuery = $('#searchQuery').val();
            updatePipelineData(selectedYear, searchQuery);
        });

        // Handler untuk pencarian
        $('#searchForm').on('submit', function (event) {
            event.preventDefault();
            var searchQuery = $('#searchQuery').val();
            var selectedYear = $('#selectedYear').text().split(' ')[0];
            updatePipelineData(selectedYear, searchQuery);
        });

        // Memuat data awal untuk tahun sekarang
        updatePipelineData(new Date().getFullYear(), $('#searchQuery').val());
    });
</script>


<?= $this->endSection() ?>