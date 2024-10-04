<?php

namespace App\Controllers;

use App\Models\RkapModel;
use App\Models\DivisionModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rkap extends BaseController
{
    protected $rkapModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->rkapModel = new RkapModel();
        $this->divisionModel = new DivisionModel();
    }

    // Method untuk menampilkan data RKAP per divisi, tahun, dan bulan
    public function index()
    {
        $year = $this->request->getVar('year') ?? date('Y');
        $month = $this->request->getVar('month') ?? date('F');

        // Mendapatkan semua data RKAP berdasarkan tahun dan bulan
        $rkapData = $this->rkapModel->getByYearAndMonth($year, $month);

        // Mendapatkan semua divisi untuk dropdown atau tampilan lainnya
        $divisions = $this->divisionModel->findAll();

        $data = [
            'rkapData' => $rkapData,
            'year' => $year,
            'month' => $month,
            'divisions' => $divisions
        ];

        return view('rkap/index', $data);
    }

    // Method untuk menambahkan atau memperbarui RKAP
    public function save()
    {
        $divisionId = $this->request->getPost('division_id');
        $year = $this->request->getPost('year');
        $month = $this->request->getPost('month');
        $targetRevenue = $this->request->getPost('target_revenue');
        $manualActualRevenue = $this->request->getPost('actual_revenue'); // Ambil nilai manual dari form

        // Cari prospect yang sudah dikonversi untuk divisi ini, tahun, dan bulan yang sama
        $prospectModel = new \App\Models\ProspectModel();
        $prospects = $prospectModel->select('prospects.*')
            ->join('prospect_divisions', 'prospects.prospect_id = prospect_divisions.prospect_id')
            ->where('prospect_divisions.division_id', $divisionId)
            ->where('prospects.conversion', 1)
            ->where('YEAR(prospects.conversion_date)', $year)
            ->where('MONTHNAME(prospects.conversion_date)', $month)
            ->findAll();

        // Hitung total actual revenue dari semua prospect yang sudah dikonversi
        $totalActualRevenue = 0;
        foreach ($prospects as $prospect) {
            // Hitung actual revenue per prospect
            $relatedDivisions = count($prospectModel->getDivisionsForProspect($prospect['prospect_id']));
            if ($relatedDivisions > 0) {
                $totalActualRevenue += $prospect['actual_revenue'] / $relatedDivisions;
            }
        }

        // Tentukan nilai akhir dari actual revenue
        if ($manualActualRevenue !== null && $manualActualRevenue !== '') {
            // Jika pengguna memasukkan nilai actual_revenue secara manual, gunakan itu
            $actualRevenue = $manualActualRevenue;
        } else {
            // Jika tidak, gunakan nilai otomatis
            $actualRevenue = $totalActualRevenue;
        }

        // Jika data sudah ada, update, jika belum insert data baru
        $existingRkap = $this->rkapModel
            ->where('division_id', $divisionId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existingRkap) {
            // Update RKAP jika sudah ada
            $this->rkapModel->update($existingRkap['id'], [
                'target_revenue' => $targetRevenue,
                'actual_revenue' => $actualRevenue // Simpan nilai actual_revenue yang sudah ditentukan
            ]);
        } else {
            // Insert RKAP baru
            $this->rkapModel->insert([
                'division_id' => $divisionId,
                'year' => $year,
                'month' => $month,
                'target_revenue' => $targetRevenue,
                'actual_revenue' => $actualRevenue // Masukkan actual_revenue yang sudah ditentukan
            ]);
        }

        return redirect()->to('/rkap')->with('success', 'RKAP berhasil diperbarui!');
    }


    // Method untuk menampilkan form input RKAP
    public function create()
    {
        $divisions = $this->divisionModel->findAll();

        $data = [
            'divisions' => $divisions
        ];

        return view('rkap/create', $data);
    }

    // Method untuk menampilkan form edit RKAP
    public function edit($id)
    {
        $rkap = $this->rkapModel->find($id);
        $divisions = $this->divisionModel->findAll();

        if (!$rkap) {
            return redirect()->to('/rkap')->with('error', 'RKAP tidak ditemukan.');
        }

        $data = [
            'rkap' => $rkap,
            'divisions' => $divisions
        ];

        return view('rkap/edit', $data);
    }

    // Method untuk menghapus RKAP
    public function delete($id)
    {
        $rkap = $this->rkapModel->find($id);

        if ($rkap) {
            $this->rkapModel->delete($id);
            return redirect()->to('/rkap')->with('success', 'RKAP berhasil dihapus.');
        } else {
            return redirect()->to('/rkap')->with('error', 'RKAP tidak ditemukan.');
        }
    }

    public function exportRkap($year)
    {
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RKAP ' . $year);

        // Membuat header sesuai dengan format yang diberikan
        $sheet->setCellValue('A1', 'BULAN');
        $sheet->setCellValue('B1', 'DIVISI');
        $sheet->setCellValue('B2', 'Div. Digital Transformation');
        $sheet->setCellValue('B3', 'PEROLEHAN');
        $sheet->setCellValue('C3', 'RKAP');
        $sheet->setCellValue('D2', 'Div. Infrastruktur');
        $sheet->setCellValue('D3', 'PEROLEHAN');
        $sheet->setCellValue('E3', 'RKAP');
        $sheet->setCellValue('F2', 'Div. SAP');
        $sheet->setCellValue('F3', 'PEROLEHAN');
        $sheet->setCellValue('G3', 'RKAP');
        $sheet->setCellValue('H1', 'Total Semua Divisi');
        $sheet->setCellValue('H3', 'PEROLEHAN');
        $sheet->setCellValue('I3', 'RKAP');

        // Menggabungkan sel untuk header
        $sheet->mergeCells('A1:A3');
        $sheet->mergeCells('B1:G1');
        $sheet->mergeCells('B2:C2');
        $sheet->mergeCells('D2:E2');
        $sheet->mergeCells('F2:G2');
        $sheet->mergeCells('H1:I2');

        // Tambahkan style untuk header
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

        $sheet->getStyle('A1:I3')->applyFromArray($headerStyle);

        // Bulan dari Januari sampai Desember
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Ambil semua data RKAP untuk tahun yang dipilih
        $rkapData = $this->rkapModel->where('year', $year)->findAll();

        // Inisialisasi array untuk menyimpan total perolehan dan target RKAP untuk semua bulan
        $perolehan = [];
        $rkap = [];

        // Inisialisasi array dengan bulan untuk setiap divisi
        foreach ($months as $index => $month) {
            $perolehan[$month] = [
                'digital' => 0,
                'infrastruktur' => 0,
                'sap' => 0,
                'total' => 0
            ];

            $rkap[$month] = [
                'digital' => 0,
                'infrastruktur' => 0,
                'sap' => 0,
                'total' => 0
            ];
        }

        // Iterasi semua data RKAP dan masukkan ke dalam array yang sesuai
        foreach ($rkapData as $rkapRow) {
            $month = $rkapRow['month'];
            $divisionId = $rkapRow['division_id'];
            $actualRevenue = $rkapRow['actual_revenue'] ?? 0;
            $targetRevenue = $rkapRow['target_revenue'] ?? 0;

            // Tentukan divisi dan masukkan nilai ke array
            switch ($divisionId) {
                case 1: // Div. Digital Transformation
                    $perolehan[$month]['digital'] = $actualRevenue;
                    $rkap[$month]['digital'] = $targetRevenue;
                    break;
                case 2: // Div. Infrastruktur
                    $perolehan[$month]['infrastruktur'] = $actualRevenue;
                    $rkap[$month]['infrastruktur'] = $targetRevenue;
                    break;
                case 3: // Div. SAP
                    $perolehan[$month]['sap'] = $actualRevenue;
                    $rkap[$month]['sap'] = $targetRevenue;
                    break;
            }

            // Update total perolehan dan target RKAP untuk bulan tersebut
            $perolehan[$month]['total'] += $actualRevenue;
            $rkap[$month]['total'] += $targetRevenue;
        }

        // Tambahkan styling untuk cell border
        $cellBorderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Tambahkan background untuk baris genap
        $evenRowStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'E3F2FD']
            ],
        ];

        // Format angka
        $numberFormat = '#,##0';

        // Mengisi data ke dalam spreadsheet dari baris ke-4
        $row = 4;
        foreach ($months as $monthName) {
            $sheet->setCellValue('A' . $row, $monthName); // Bulan

            // Digital Transformation
            $sheet->setCellValue('B' . $row, $perolehan[$monthName]['digital']); // Perolehan
            $sheet->setCellValue('C' . $row, $rkap[$monthName]['digital']); // RKAP

            // Infrastruktur
            $sheet->setCellValue('D' . $row, $perolehan[$monthName]['infrastruktur']); // Perolehan
            $sheet->setCellValue('E' . $row, $rkap[$monthName]['infrastruktur']); // RKAP

            // SAP
            $sheet->setCellValue('F' . $row, $perolehan[$monthName]['sap']); // Perolehan
            $sheet->setCellValue('G' . $row, $rkap[$monthName]['sap']); // RKAP

            // Total
            $sheet->setCellValue('H' . $row, $perolehan[$monthName]['total']); // Total Perolehan
            $sheet->setCellValue('I' . $row, $rkap[$monthName]['total']); // Total RKAP

            // Terapkan border pada setiap baris
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($cellBorderStyle);

            // Terapkan background pada baris genap
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($evenRowStyle);
            }

            // Terapkan format angka untuk kolom perolehan dan RKAP
            $sheet->getStyle('B' . $row . ':I' . $row)->getNumberFormat()->setFormatCode($numberFormat);

            $row++;
        }

        // Footer untuk total perolehan dan RKAP
        $sheet->setCellValue('A' . $row, 'Total Perolehan');
        $sheet->mergeCells('A' . $row . ':G' . $row); // Menggabungkan kolom A sampai G untuk menampilkan "Total Perolehan"
        $sheet->getStyle('A16:I16')->applyFromArray($headerStyle);

        // Menghitung total untuk kolom H (total perolehan) dan I (total RKAP)
        $sheet->setCellValue('H' . $row, '=SUM(H4:H15)'); // Menjumlahkan total perolehan semua divisi
        $sheet->setCellValue('I' . $row, '=SUM(I4:I15)'); // Menjumlahkan total RKAP semua divisi

        // Terapkan border dan format angka untuk total perolehan
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($cellBorderStyle);
        $sheet->getStyle('H' . $row . ':I' . $row)->getNumberFormat()->setFormatCode($numberFormat);

        // Atur lebar kolom secara otomatis
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set HTTP headers untuk download file Excel
        $filename = 'RKAP_' . $year . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
