<?php

namespace App\Controllers;

use App\Models\ProspectModel;
use App\Models\FinancialModel;
use App\Models\MilestoneProspectModel;
use App\Models\PreSalesModel;
use App\Models\DivisionModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\ProspectDivisionModel;
use App\Models\ProspectPreSalesModel;
use App\Models\RkapModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Prospect extends BaseController
{
    protected $prospectModel;
    protected $financialModel;
    protected $milestoneProspectModel;
    protected $preSalesModel;
    protected $divisionModel;
    protected $customerModel;
    protected $userModel;
    protected $prospectDivisionModel;
    protected $prospectPreSalesModel;
    protected $rkapModel;

    public function __construct()
    {
        $this->prospectModel = new ProspectModel();
        $this->financialModel = new FinancialModel();
        $this->milestoneProspectModel = new MilestoneProspectModel();
        $this->preSalesModel = new PreSalesModel();
        $this->divisionModel = new DivisionModel();
        $this->customerModel = new CustomerModel();
        $this->userModel = new UserModel();
        $this->prospectDivisionModel = new ProspectDivisionModel();
        $this->prospectPreSalesModel = new ProspectPreSalesModel();
        $this->rkapModel = new RkapModel();
    }

    // Menampilkan daftar prospek
    public function index()
    {
        $session = session();
        $role = $session->get('role');
        $user_id = $session->get('user_id');
        $currentYear = date('Y');

        // Dapatkan parameter untuk pencarian, pagination, dan jumlah entri
        $search = $this->request->getVar('search');
        $limit = $this->request->getVar('limit') ?? 10; // Default 10 entri
        $page = $this->request->getVar('page') ?? 1;

        $totalEstimatedRevenue = $this->prospectModel->selectSum('estimated_revenue')
            ->where('YEAR(created_at)', $currentYear) // Hanya untuk tahun ini
            ->first();

        if ($role == 'Admin' || $role == 'AM') {
            $query = $this->prospectModel->select('prospects.*, customers.customer_name, users.full_name AS sales_utama')
                ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
                ->join('users', 'users.user_id = prospects.user_id', 'left'); // Pastikan join dengan users
        } else {
            // Tambahkan 'prospects.' sebelum user_id untuk menghindari ambiguitas
            $query = $this->prospectModel->select('prospects.*, customers.customer_name, users.full_name AS sales_utama')
                ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
                ->join('users', 'users.user_id = prospects.user_id', 'left')
                ->where('prospects.user_id', $user_id); // Perbaiki di sini
        }

        // Implementasi pencarian
        if ($search) {
            $query = $query->like('customer_name', $search)
                ->orLike('prospect_scope', $search);
        }

        $data['prospects'] = $query->paginate($limit, 'prospects');
        $data['pager'] = $this->prospectModel->pager;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['totalEstimatedRevenue'] = $totalEstimatedRevenue['estimated_revenue'] ?? 0;

        return view('prospect/index', $data);
    }

    // Menampilkan detail prospek beserta financial, milestone, divisions, dan pre-sales
    public function view($prospect_id)
    {
        $origin = $this->request->getVar('origin') ?? 'prospect';
        $prospect = $this->prospectModel->find($prospect_id);
        $customer = $this->customerModel->find($prospect['customer_id']);
        $financial = $this->financialModel->where('prospect_id', $prospect_id)->first();
        $milestones = $this->milestoneProspectModel->getMilestonesByProspect($prospect_id);
        $progress = $this->milestoneProspectModel->calculateProgress($prospect_id);
        $sales = $this->userModel->find($prospect['user_id']);
        $pre_sales = $this->prospectPreSalesModel->select('pre_sales_team.pre_sales_id, pre_sales_team.name AS pre_sales_name, pre_sales_team.status AS pre_sales_status, divisions.division_name')
            ->join('pre_sales_team', 'prospect_pre_sales.pre_sales_id = pre_sales_team.pre_sales_id')
            ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
            ->where('prospect_pre_sales.prospect_id', $prospect_id)
            ->findAll();
        $divisions = $this->prospectDivisionModel->select('divisions.division_name')
            ->join('divisions', 'prospect_divisions.division_id = divisions.division_id')
            ->where('prospect_divisions.prospect_id', $prospect_id)
            ->findAll();

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

        $nextMilestone = $this->milestoneProspectModel->getNextMilestone($prospect_id, $milestoneOrder);

        // Menentukan apakah milestone sebelumnya telah selesai 100%
        $previousMilestoneCompleted = true;
        foreach ($milestones as $milestone) {
            if ($milestone['progress_percentage'] < 100) {
                $previousMilestoneCompleted = false;
                break;
            }
        }

        $data = [
            'prospect' => $prospect,
            'customer' => $customer,
            'financial' => $financial,
            'milestones' => $milestones,
            'progress' => $progress,
            'nextMilestone' => urlencode($nextMilestone),
            'sales' => $sales,
            'pre_sales' => $pre_sales,
            'divisions' => $divisions,
            'previousMilestoneCompleted' => $previousMilestoneCompleted,
            'origin' => $origin
        ];

        return view('prospect/view', $data);
    }

    // Menampilkan form untuk menambah prospek baru
    public function create()
    {
        $data['customers'] = $this->customerModel->findAll();
        $data['pre_sales_team'] = $this->preSalesModel->getPreSalesWithDivision();
        $data['divisions'] = $this->divisionModel->findAll();
        return view('prospect/create', $data);
    }

    // Menyimpan prospek baru beserta divisi dan pre-sales
    public function store()
    {
        // Ambil user_id dari session
        $user_id = session()->get('user_id');
        $customer_id = $this->request->getPost('customer_id');
        $prospect_scope = $this->request->getPost('prospect_scope');

        // Cek apakah scope ini sudah ada untuk customer yang sama
        $existingProspect = $this->prospectModel->where([
            'customer_id' => $customer_id,
            'prospect_scope' => $prospect_scope
        ])->first();

        if ($existingProspect) {
            // Jika sudah ada, kembali ke halaman create dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Prospect scope for this customer already exists. Please use a different scope.');
        }

        // Ambil nilai estimated revenue dan actual revenue
        $estimated_revenue = $this->request->getPost('estimated_revenue');
        $actual_revenue = $this->request->getPost('actual_revenue') ?? null; // Bisa null jika belum ada

        // Perhitungan HPP, Plan Budget Sales, dan Margin
        $hpp = 0.65 * $estimated_revenue;
        $plan_budget_sales = 0.02 * $estimated_revenue;
        $margin = $estimated_revenue - $hpp - $plan_budget_sales;

        // Simpan data prospek baru
        $prospectData = [
            'customer_id' => $customer_id,
            'prospect_scope' => $prospect_scope,
            'estimated_revenue' => $estimated_revenue,
            'actual_revenue' => $actual_revenue,
            'projected_quarter' => $this->request->getPost('projected_quarter'),
            'target_month_contract' => $this->request->getPost('target_month_contract'),
            'user_id' => $user_id,
            'prospect_status' => $this->request->getPost('prospect_status'),
            'conversion' => $this->request->getPost('conversion') ?? 0, // Default 0 jika tidak ada
            'remarks' => $this->request->getPost('remarks')
        ];

        // Simpan prospect
        $this->prospectModel->save($prospectData);
        $prospect_id = $this->prospectModel->getInsertID();

        // Simpan relasi ke divisions dan pre-sales
        $division_ids = $this->request->getPost('division_ids'); // Array of selected divisions
        $pre_sales_ids = $this->request->getPost('pre_sales_ids'); // Array of selected pre-sales

        foreach ($division_ids as $division_id) {
            $this->prospectDivisionModel->insert([
                'prospect_id' => $prospect_id,
                'division_id' => $division_id
            ]);
        }

        foreach ($pre_sales_ids as $pre_sales_id) {
            $this->prospectPreSalesModel->insert([
                'prospect_id' => $prospect_id,
                'pre_sales_id' => $pre_sales_id
            ]);
        }

        // Simpan data financial secara otomatis
        $this->financialModel->insert([
            'prospect_id' => $prospect_id,
            'hpp' => $hpp,
            'plan_budget_sales' => $plan_budget_sales,
            'margin' => $margin
        ]);

        return redirect()->to('/prospect')->with('success', 'Prospect added successfully.');
    }


    // Menampilkan form untuk mengedit prospek
    public function edit($prospect_id)
    {
        $prospect = $this->prospectModel->find($prospect_id);
        $financial = $this->financialModel->where('prospect_id', $prospect_id)->first();
        $customers = $this->customerModel->findAll();
        $selectedDivisions = $this->prospectDivisionModel->where('prospect_id', $prospect_id)->findAll();
        $selectedPreSales = $this->prospectPreSalesModel->where('prospect_id', $prospect_id)->findAll();

        $data['customers'] = $customers;
        $data['pre_sales_team'] = $this->preSalesModel->getPreSalesWithDivision();
        $data['divisions'] = $this->divisionModel->findAll();
        $data['prospect'] = $prospect;
        $data['financial'] = $financial;
        $data['selectedDivisions'] = array_column($selectedDivisions, 'division_id');
        $data['selectedPreSales'] = array_column($selectedPreSales, 'pre_sales_id');

        return view('prospect/edit', $data);
    }

    // Menyimpan perubahan setelah prospek diubah beserta divisi dan pre-sales
    public function update($prospect_id)
    {
        // Ambil divisi dan pre-sales yang terkait dari form
        $division_ids = $this->request->getPost('division_ids');
        $pre_sales_ids = $this->request->getPost('pre_sales_ids');

        // Ambil data prospect dari database untuk mengetahui nilai conversion saat ini
        $existingProspect = $this->prospectModel->find($prospect_id);
        if (!$existingProspect) {
            return redirect()->back()->with('error', 'Prospect not found.');
        }

        // Data prospek yang diperbarui
        $prospectData = [
            'customer_id' => $this->request->getPost('customer_id'),
            'prospect_scope' => $this->request->getPost('prospect_scope'),
            'estimated_revenue' => $this->request->getPost('estimated_revenue'),
            'actual_revenue' => $this->request->getPost('actual_revenue'), // Update actual revenue jika tersedia
            'projected_quarter' => $this->request->getPost('projected_quarter'),
            'target_month_contract' => $this->request->getPost('target_month_contract'),
            'prospect_status' => $this->request->getPost('prospect_status'),
            'conversion' => $existingProspect['conversion'], // Pertahankan nilai conversion dari database
            'remarks' => $this->request->getPost('remarks')
        ];

        // Update data prospect dan relasi divisi serta pre-sales
        $this->prospectModel->update($prospect_id, $prospectData);
        $this->prospectDivisionModel->where('prospect_id', $prospect_id)->delete();
        $this->prospectPreSalesModel->where('prospect_id', $prospect_id)->delete();


        // Simpan relasi baru dengan divisi dan pre-sales
        foreach ($division_ids as $division_id) {
            $this->prospectDivisionModel->insert([
                'prospect_id' => $prospect_id,
                'division_id' => $division_id
            ]);
        }

        foreach ($pre_sales_ids as $pre_sales_id) {
            $this->prospectPreSalesModel->insert([
                'prospect_id' => $prospect_id,
                'pre_sales_id' => $pre_sales_id
            ]);
        }

        // Update financial details untuk prospect ini
        $this->financialModel->where('prospect_id', $prospect_id)->set([
            'hpp' => $this->request->getPost('hpp'),
            'plan_budget_sales' => $this->request->getPost('plan_budget_sales'),
            'margin' => $this->request->getPost('margin')
        ])->update();

        //Hitung Ulang RKAP disini
        // Hitung ulang actual revenue pada RKAP setelah prospect diperbarui
        $this->updateRkapActualRevenue($division_ids, $existingProspect['conversion_date']);


        return redirect()->to('/prospect/view/' . $prospect_id)->with('success', 'Prospect updated successfully.');
    }

    private function updateRkapActualRevenue($division_ids, $conversion_date)
    {
        $rkapModel = new \App\Models\RkapModel(); // Gunakan RkapModel di sini

        $year = date('Y', strtotime($conversion_date));
        $month = date('F', strtotime($conversion_date));

        foreach ($division_ids as $division_id) {
            // Hitung ulang actual revenue dari semua prospects yang dikonversi pada divisi, tahun, dan bulan terkait
            $totalActualRevenue = $rkapModel->getTotalActualRevenueForDivision($division_id, $year, $month);

            // Update actual revenue pada RKAP
            $existingRkap = $rkapModel
                ->where('division_id', $division_id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existingRkap) {
                $rkapModel->update($existingRkap['id'], [
                    'actual_revenue' => $totalActualRevenue
                ]);
            }
        }
    }


    // Menghapus prospek
    public function delete($prospect_id)
    {
        // Hapus financial, milestones, divisions, dan pre-sales yang terkait dengan prospect ini
        $this->financialModel->where('prospect_id', $prospect_id)->delete();
        $this->milestoneProspectModel->where('prospect_id', $prospect_id)->delete();
        $this->prospectDivisionModel->where('prospect_id', $prospect_id)->delete();
        $this->prospectPreSalesModel->where('prospect_id', $prospect_id)->delete();

        // Hapus prospect itu sendiri
        $this->prospectModel->delete($prospect_id);

        return redirect()->to('/prospect')->with('success', 'Prospect deleted successfully.');
    }

    // Fungsi export dan import tetap tidak berubah

    public function export()
    {
        $prospects = $this->prospectModel->getAllProspectsWithDetails();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Prospect Data');

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
        $headers = array_merge($headers, $milestoneOrder, ['Remarks', 'Actual Revenue']); // Tambahkan 'Actual Revenue' di ujung

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
        $sheet->getStyle('A100:' . $col . '100')->applyFromArray($headerStyle);

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
            $divisions = $this->prospectDivisionModel->select('divisions.division_name')
                ->join('divisions', 'prospect_divisions.division_id = divisions.division_id')
                ->where('prospect_divisions.prospect_id', $prospect['prospect_id'])
                ->findAll();
            $pre_sales = $this->prospectPreSalesModel->select('pre_sales_team.pre_sales_id, pre_sales_team.name AS pre_sales_name, pre_sales_team.status AS pre_sales_status, divisions.division_name')
                ->join('pre_sales_team', 'prospect_pre_sales.pre_sales_id = pre_sales_team.pre_sales_id')
                ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
                ->where('prospect_pre_sales.prospect_id', $prospect['prospect_id'])
                ->findAll();

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
            $sheet->setCellValue('M' . $row, isset($prospect['progress']) ? $prospect['progress'] : '0%'); // Mengambil nilai progress yang sudah dihitung


            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode($numberFormat);
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode($numberFormat);
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode($numberFormat);
            $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode($numberFormat);
            $sheet->getStyle('Y' . $row)->getNumberFormat()->setFormatCode($numberFormat);

            $totalEstimatedRevenue += $prospect['estimated_revenue'];
            $totalHPP += $prospect['hpp'];
            $totalPlanBudgetSales += $prospect['plan_budget_sales'];
            $totalMargin += $prospect['margin'];
            $totalActualRevenue += $prospect['actual_revenue'];

            // Milestone completion status menggunakan nilai progress_percentage
            $colIndex = 'N'; // Starting from column N
            foreach ($milestoneOrder as $milestoneName) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestoneName));
                // Tampilkan nilai progress_percentage langsung tanpa validasi 'Completed'
                $sheet->setCellValue($colIndex . $row, isset($prospect[$milestoneKey]) ? $prospect[$milestoneKey] : '0%');
                $colIndex++;
            }

            // Remarks
            $sheet->setCellValue($colIndex . $row, $prospect['remarks']);

            // Actual Revenue (kolom baru)
            $sheet->setCellValue('Y' . $row, $prospect['actual_revenue']);

            // Terapkan border untuk semua sel yang terisi
            $sheet->getStyle('A' . $row . ':' . 'Y' . $row)->applyFromArray($cellBorderStyle);

            // Terapkan background strip biru muda pada baris genap
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

        $sheet->setCellValue('A100', 'TOTAL');
        $sheet->setCellValue('C100', $totalEstimatedRevenue);
        $sheet->setCellValue('J100', $totalHPP);
        $sheet->setCellValue('K100', $totalPlanBudgetSales);
        $sheet->setCellValue('L100', $totalMargin);
        $sheet->setCellValue('Y100', $totalActualRevenue);

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
        $filename = 'Prospects_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        exit();
    }

}
