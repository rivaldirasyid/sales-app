<?php

namespace App\Controllers;

use App\Models\ProspectModel;
use App\Models\FinancialModel;
use App\Models\MilestoneProspectModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Project extends BaseController
{
    protected $prospectModel;
    protected $financialModel;
    protected $milestoneProspectModel;
    protected $customerModel;
    protected $userModel;

    public function __construct()
    {
        $this->prospectModel = new ProspectModel();
        $this->financialModel = new FinancialModel();
        $this->milestoneProspectModel = new MilestoneProspectModel(); // Tambahkan inisialisasi model ini
        $this->customerModel = new CustomerModel();
        $this->userModel = new UserModel();
    }

    // Menampilkan daftar proyek yang sudah dikonversi dari prospek
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

        $totalActualRevenue = $this->prospectModel->selectSum('actual_revenue')
            ->where('YEAR(created_at)', $currentYear) // Hanya untuk tahun ini
            ->first();


        // Membuat query dasar dengan filter conversion = 1 untuk project
        if ($role == 'Admin' || $role == 'AM') {
            $query = $this->prospectModel->select('prospects.*, customers.customer_name, users.full_name AS sales_utama')
                ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
                ->join('users', 'users.user_id = prospects.user_id', 'left')
                ->where('prospects.conversion', 1); // Hanya menampilkan prospek yang sudah dikonversi menjadi proyek
        } else {
            $query = $this->prospectModel->select('prospects.*, customers.customer_name, users.full_name AS sales_utama')
                ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
                ->join('users', 'users.user_id = prospects.user_id', 'left')
                ->where('prospects.conversion', 1) // Hanya menampilkan prospek yang sudah dikonversi menjadi proyek
                ->where('prospects.user_id', $user_id);
        }

        // Implementasi pencarian untuk customer_name dan prospect_scope
        if ($search) {
            $query = $query->groupStart()
                ->like('customer_name', $search)
                ->orLike('prospect_scope', $search)
                ->groupEnd();
        }

        $data['projects'] = $query->paginate($limit, 'projects');
        $data['pager'] = $this->prospectModel->pager;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['milestoneProspectModel'] = $this->milestoneProspectModel;
        $data['totalActualRevenue'] = $totalActualRevenue['actual_revenue'] ?? 0; // Tambahkan model ke data view

        return view('project/index', $data);
    }

    public function export()
    {
        $projects = $this->prospectModel->select('
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
            ->where('prospects.conversion', 1) // Hanya menampilkan prospek yang sudah dikonversi menjadi proyek
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Project Data');

        // Set header untuk baris pertama dan kedua
        $sheet->setCellValue('A1', 'Customer');
        $sheet->setCellValue('B1', 'Scope');
        $sheet->setCellValue('C1', 'State');
        $sheet->setCellValue('D1', 'Divisi');
        $sheet->setCellValue('E1', 'Sales');
        $sheet->setCellValue('F1', 'Quarter');
        $sheet->setCellValue('G1', 'Target');
        $sheet->setCellValue('G2', 'Perolehan');
        $sheet->setCellValue('H1', 'No. ');
        $sheet->setCellValue('H2', 'Kontrak');
        $sheet->setCellValue('I1', 'Kontrak');
        $sheet->setCellValue('I2', 'Date');
        $sheet->setCellValue('J1', 'Nilai');
        $sheet->setCellValue('J2', 'Kontrak');
        $sheet->setCellValue('K1', 'Keterangan');
        $sheet->setCellValue('L1', 'Bulan');

        // Menggabungkan header yang memerlukan penggabungan
        $sheet->mergeCells('A1:A2'); // Menggabungkan Customer
        $sheet->mergeCells('B1:B2'); // Menggabungkan Scope
        $sheet->mergeCells('C1:C2'); // Menggabungkan Status
        $sheet->mergeCells('D1:D2'); // Menggabungkan Divisi
        $sheet->mergeCells('E1:E2'); // Menggabungkan Sales
        $sheet->mergeCells('F1:F2'); // Menggabungkan Quartal
        $sheet->mergeCells('K1:K2'); // Menggabungkan Keterangan
        $sheet->mergeCells('L1:L2'); // Menggabungkan Bulan

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

        // Terapkan style header
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
        foreach ($projects as $project) {
            // Menambahkan data ke dalam Excel
            $sheet->setCellValue('A' . $row, $project['customer_name']);
            $sheet->setCellValue('B' . $row, $project['prospect_scope']);
            $sheet->setCellValue('C' . $row, $project['prospect_status']);

            // Ambil divisi yang terkait dengan project ini
            $divisions = $this->prospectModel->select('GROUP_CONCAT(divisions.division_name ORDER BY divisions.division_name SEPARATOR ", ") as division_names')
                ->join('prospect_divisions', 'prospect_divisions.prospect_id = prospects.prospect_id', 'left')
                ->join('divisions', 'divisions.division_id = prospect_divisions.division_id', 'left')
                ->where('prospects.prospect_id', $project['prospect_id'])
                ->first();

            $sheet->setCellValue('D' . $row, $divisions['division_names']);
            $sheet->setCellValue('E' . $row, $project['sales_utama']);
            $sheet->setCellValue('F' . $row, $project['projected_quarter']);
            $sheet->setCellValue('G' . $row, $project['estimated_revenue']);
            $sheet->setCellValue('H' . $row, $project['remarks']);
            $sheet->setCellValue('I' . $row, $project['conversion_date']); // Date of contract
            $sheet->setCellValue('J' . $row, $project['actual_revenue']);
            $sheet->setCellValue('K' . $row, null); // Bisa diubah sesuai kebutuhan
            $sheet->setCellValue('L' . $row, date('F', strtotime($project['conversion_date']))); // Mengambil bulan dari tanggal

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
        $filename = 'Projects_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        exit();
    }

    public function updateActualRevenue($prospect_id)
    {
        $actual_revenue = $this->request->getPost('actual_revenue');

        // Update actual revenue
        $this->prospectModel->update($prospect_id, [
            'actual_revenue' => $actual_revenue
        ]);

        return redirect()->to('/prospect/view/' . $prospect_id)->with('success', 'Actual revenue updated successfully.');
    }



}
