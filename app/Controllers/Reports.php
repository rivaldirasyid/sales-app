<?php

namespace App\Controllers;

use App\Models\ProspectModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends BaseController
{
    protected $prospectModel;

    public function __construct()
    {
        $this->prospectModel = new ProspectModel();
    }

    // Menampilkan halaman Pipeline Summary
    public function pipeline_summary()
    {
        // Ambil tahun dari request, atau gunakan tahun saat ini jika tidak ada
        $year = $this->request->getVar('year') ?? date('Y');
        $search = $this->request->getVar('search');

        // Ambil data prospek berdasarkan tahun yang dipilih
        $prospects = $this->prospectModel->getAllProspectsWithDetailsByYear($year, $search);
        $totalProspects = count($prospects);
        $totalEstimatedRevenue = $totalProspects > 0 ? array_sum(array_column($prospects, 'estimated_revenue')) : 0;
        $totalActualRevenue = 0;

        foreach ($prospects as $prospect) {
            if ($prospect['conversion'] == 1) {
                $totalActualRevenue += $prospect['actual_revenue'];
            }
        }

        // Hitung total converted prospects
        $totalConvertedProspects = $this->prospectModel->where('YEAR(prospects.created_at)', $year)
            ->where('conversion', 1)
            ->countAllResults(false);

        $data = [
            'prospects' => $prospects,
            'totalProspects' => $totalProspects,
            'totalEstimatedRevenue' => $totalEstimatedRevenue,
            'totalActualRevenue' => $totalActualRevenue,
            'totalConvertedProspects' => $totalConvertedProspects,
            'search' => $search,
            'year' => $year,
        ];

        return view('reports/pipeline_summary', $data);
    }

    // Method untuk mengirim data berdasarkan Ajax Request
    public function getPipelineSummaryData($year)
    {
        $search = $this->request->getVar('search');

        // Ambil data prospek berdasarkan tahun yang dipilih
        $prospects = $this->prospectModel->getAllProspectsWithDetailsByYear($year, $search);
        $totalProspects = count($prospects);
        $totalEstimatedRevenue = $totalProspects > 0 ? array_sum(array_column($prospects, 'estimated_revenue')) : 0;
        $totalActualRevenue = 0;

        foreach ($prospects as $prospect) {
            if ($prospect['conversion'] == 1) {
                $totalActualRevenue += $prospect['actual_revenue'];
            }
        }

        // Hitung total converted prospects
        $totalConvertedProspects = $this->prospectModel->where('YEAR(prospects.created_at)', $year)
            ->where('conversion', 1)
            ->countAllResults(false);

        return $this->response->setJSON([
            'prospects' => $prospects,
            'totalProspects' => $totalProspects,
            'totalEstimatedRevenue' => $totalEstimatedRevenue,
            'totalActualRevenue' => $totalActualRevenue,
            'totalConvertedProspects' => $totalConvertedProspects,
        ]);
    }

    public function exportPipelineSummary($year)
    {
        $prospects = $this->prospectModel->select('
        prospects.*, 
        customers.customer_name, 
        users.full_name AS sales_utama,
        financial_details.hpp,
        financial_details.plan_budget_sales,
        financial_details.margin
    ')
            ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
            ->join('users', 'users.user_id = prospects.user_id', 'left')
            ->join('financial_details', 'prospects.prospect_id = financial_details.prospect_id', 'left')
            ->where('YEAR(prospects.created_at)', $year)
            ->where('prospects.conversion', 1) // Hanya mengekspor data yang sudah dikonversi
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pipeline Summary ' . $year);

        // Set header untuk baris pertama dan kedua
        $sheet->setCellValue('A1', 'Customer');
        $sheet->setCellValue('B1', 'Scope');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Divisi');
        $sheet->setCellValue('E1', 'Sales');
        $sheet->setCellValue('F1', 'Quarter');
        $sheet->setCellValue('G1', 'Target');
        $sheet->setCellValue('G2', 'Perolehan');
        $sheet->setCellValue('H1', 'No.');
        $sheet->setCellValue('H2', 'Kontrak');
        $sheet->setCellValue('I1', 'Kontrak');
        $sheet->setCellValue('I2', 'Date');
        $sheet->setCellValue('J1', 'Nilai');
        $sheet->setCellValue('J2', 'Kontrak');
        $sheet->setCellValue('K1', 'Keterangan');
        $sheet->setCellValue('L1', 'Bulan');

        // Menggabungkan header yang memerlukan penggabungan
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('K1:K2');
        $sheet->mergeCells('L1:L2');

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'ff073763']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A1:L2')->applyFromArray($headerStyle);

        // Style untuk border pada seluruh sel
        $cellBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Format angka dengan titik
        $numberFormat = '#,##0';

        // Style untuk background baris genap
        $evenRowStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'E3F2FD']
            ],
        ];

        $centerAlignmentStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];

        $row = 3; // Data dimulai dari baris ke-3
        foreach ($prospects as $prospect) {
            $sheet->setCellValue('A' . $row, $prospect['customer_name']);
            $sheet->setCellValue('B' . $row, $prospect['prospect_scope']);
            $sheet->setCellValue('C' . $row, $prospect['prospect_status']);

            // Ambil divisi yang terkait dengan project ini
            $divisions = $this->prospectModel->select('GROUP_CONCAT(divisions.division_name ORDER BY divisions.division_name SEPARATOR ", ") as division_names')
                ->join('prospect_divisions', 'prospect_divisions.prospect_id = prospects.prospect_id', 'left')
                ->join('divisions', 'divisions.division_id = prospect_divisions.division_id', 'left')
                ->where('prospects.prospect_id', $prospect['prospect_id'])
                ->first();

            $sheet->setCellValue('D' . $row, $divisions['division_names']);
            $sheet->setCellValue('E' . $row, $prospect['sales_utama']);
            $sheet->setCellValue('F' . $row, $prospect['projected_quarter']);
            $sheet->setCellValue('G' . $row, $prospect['estimated_revenue']);
            $sheet->setCellValue('H' . $row, $prospect['remarks']);
            $sheet->setCellValue('I' . $row, $prospect['conversion_date']); // Date of contract
            $sheet->setCellValue('J' . $row, $prospect['actual_revenue']);
            $sheet->setCellValue('K' . $row, null); // Bisa diubah sesuai kebutuhan
            $sheet->setCellValue('L' . $row, date('F', strtotime($prospect['conversion_date']))); // Mengambil bulan dari tanggal

            // Terapkan format angka pada kolom estimasi dan actual revenue
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode($numberFormat);
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode($numberFormat);

            $centeredColumns = ['C', 'E', 'F', 'I', 'L'];
            foreach ($centeredColumns as $col) {
                $sheet->getStyle($col . '2:' . $col . $row)->applyFromArray($centerAlignmentStyle);
            }

            // Terapkan border untuk semua sel yang terisi
            $sheet->getStyle('A' . $row . ':' . 'L' . $row)->applyFromArray($cellBorderStyle);

            // Terapkan background strip biru muda pada baris genap
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':' . 'L' . $row)->applyFromArray($evenRowStyle);
            }

            $row++;
        }

        // Set auto width for all columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set HTTP headers for file download
        $filename = 'Pipeline_Summary_' . $year . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function exportAllProspectsByYear($year)
    {
        // Mengambil semua prospek berdasarkan tahun
        $prospects = $this->prospectModel->getAllProspectsWithDetailsByYear($year);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Prospects ' . $year);

        // Set header
        $headers = [
            'Customer',
            'Judul/Scope',
            'Target Kontrak',
            'Proyeksi/Quarter',
            'Target Month Contract',
            'STATUS',
            'Divisi Terkait',
            'Sales Utama',
            'Pre-Sales',
            'HPP',
            'Plan Budget Sales',
            'Margin',
            'Activity'
        ];

        // Milestone headers
        $milestoneOrder = [
            'Scooping',
            'Presentasi',
            'PreSales Sourcing',
            'Draft Proposal',
            'Sent Proposal',
            'Sent Komersial',
            'Clarification',
            'Tender dan Nego',
            'Contract Draft',
            'Contract Signed'
        ];

        // Gabungkan header dan milestone
        $headers = array_merge($headers, $milestoneOrder, ['Remarks', 'Actual Revenue']);

        // Atur header pada baris pertama
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style untuk header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'ff073763']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Terapkan style header
        $sheet->getStyle('A1:' . $col . '1')->applyFromArray($headerStyle);

        // Style untuk border pada seluruh sel
        $cellBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Style untuk background baris genap
        $evenRowStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'E3F2FD']
            ],
        ];

        $centerAlignmentStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];

        $numberFormat = '#,##0';

        $totalEstimatedRevenue = 0;
        $totalHPP = 0;
        $totalPlanBudgetSales = 0;
        $totalMargin = 0;
        $totalActualRevenue = 0;

        // Populate data rows
        $row = 2;
        foreach ($prospects as $prospect) {
            // Ambil relasi pivot untuk divisions dan pre-sales
            $divisions = $this->prospectModel->getDivisionsForProspect($prospect['prospect_id']);
            $pre_sales = $this->prospectModel->getPreSalesForProspect($prospect['prospect_id']);

            $division_names = implode(', ', array_column($divisions, 'division_name'));
            $pre_sales_names = implode(', ', array_map(function ($ps) {
                return $ps['pre_sales_status'] . ' - ' . $ps['division_name'];
            }, $pre_sales));

            $sheet->setCellValue('A' . $row, $prospect['customer_name']);
            $sheet->setCellValue('B' . $row, $prospect['prospect_scope']);
            $sheet->setCellValue('C' . $row, $prospect['estimated_revenue']);
            $sheet->setCellValue('D' . $row, $prospect['projected_quarter']);
            $sheet->setCellValue('E' . $row, $prospect['target_month_contract']);
            $sheet->setCellValue('F' . $row, $prospect['prospect_status']);
            $sheet->setCellValue('G' . $row, $division_names);
            $sheet->setCellValue('H' . $row, $prospect['sales_utama']);
            $sheet->setCellValue('I' . $row, $pre_sales_names);
            $sheet->setCellValue('J' . $row, $prospect['hpp']);
            $sheet->setCellValue('K' . $row, $prospect['plan_budget_sales']);
            $sheet->setCellValue('L' . $row, $prospect['margin']);
            $sheet->setCellValue('M' . $row, isset($prospect['progress']) ? $prospect['progress'] : '0%');

            // Milestone completion status menggunakan nilai progress_percentage
            $colIndex = 'N';
            foreach ($milestoneOrder as $milestoneName) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestoneName));
                $sheet->setCellValue($colIndex . $row, isset($prospect[$milestoneKey]) ? $prospect[$milestoneKey] : '0%');
                $colIndex++;
            }

            // Remarks
            $sheet->setCellValue($colIndex . $row, $prospect['remarks']);

            // Actual Revenue
            $sheet->setCellValue('Y' . $row, $prospect['actual_revenue']);

            // Apply styles
            $sheet->getStyle('A' . $row . ':' . 'Y' . $row)->applyFromArray($cellBorderStyle);
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':' . 'Y' . $row)->applyFromArray($evenRowStyle);
            }

            $status = strtoupper($prospect['prospect_status']);
            switch ($status) {
                case 'CLOSED':
                    $sheet->getStyle('F' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF92D050'); // Warna hijau
                    break;
                case 'HOLD':
                    $sheet->getStyle('F' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFFFF00'); // Warna kuning
                    break;
                case 'FAILED':
                    $sheet->getStyle('F' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF0000'); // Warna merah
                    break;
            }

            $row++;
        }

        // Total di bagian akhir
        $sheet->setCellValue('A100', 'TOTAL');
        $sheet->setCellValue('C100', $totalEstimatedRevenue);
        $sheet->setCellValue('J100', $totalHPP);
        $sheet->setCellValue('K100', $totalPlanBudgetSales);
        $sheet->setCellValue('L100', $totalMargin);
        $sheet->setCellValue('Y100', $totalActualRevenue);

        // Apply styles to totals
        $sheet->getStyle('C100')->getNumberFormat()->setFormatCode($numberFormat);
        $sheet->getStyle('J100')->getNumberFormat()->setFormatCode($numberFormat);
        $sheet->getStyle('K100')->getNumberFormat()->setFormatCode($numberFormat);
        $sheet->getStyle('L100')->getNumberFormat()->setFormatCode($numberFormat);
        $sheet->getStyle('Y100')->getNumberFormat()->setFormatCode($numberFormat);

        $centeredColumns = ['D', 'E', 'F', 'G', 'H', 'I', 'N', 'M', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'];
        foreach ($centeredColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $row)->applyFromArray($centerAlignmentStyle);
        }
        // Set auto width for all columns
        foreach (range('A', 'Y') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set HTTP headers for file download
        $filename = 'Prospects_' . $year . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Prepare file for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }


}
