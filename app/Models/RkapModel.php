<?php

namespace App\Models;

use CodeIgniter\Model;

class RkapModel extends Model
{
    protected $table = 'rkap';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'division_id',
        'year',
        'month',
        'target_revenue',
        'actual_revenue',
        'created_at',
        'updated_at'
    ];

    // Menggunakan timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Fungsi untuk mendapatkan perolehan berdasarkan tahun dan bulan
    public function getByYearAndMonth($year, $month)
    {
        return $this->select('rkap.*, divisions.division_name') // Pilih semua kolom dari RKAP dan nama divisi
            ->join('divisions', 'divisions.division_id = rkap.division_id') // Gabungkan dengan tabel divisi
            ->where('rkap.year', $year)
            ->where('rkap.month', $month)
            ->findAll();
    }


    // Fungsi untuk mendapatkan total revenue untuk divisi pada tahun tertentu
    public function getRevenueByYearAndDivision($division_id, $year)
    {
        return $this->select('SUM(actual_revenue) as total_revenue')
            ->where('division_id', $division_id)
            ->where('year', $year)
            ->first();
    }

    public function getTotalActualRevenueForDivision($division_id, $year, $month)
    {
        // Langkah 1: Ambil semua prospek yang sudah dikonversi untuk divisi, tahun, dan bulan terkait
        $query = $this->db->table('prospects p')
            ->select('p.prospect_id, p.actual_revenue')
            ->join('prospect_divisions pd', 'p.prospect_id = pd.prospect_id')
            ->where('pd.division_id', $division_id)
            ->where('YEAR(p.conversion_date)', $year)
            ->where('MONTHNAME(p.conversion_date)', $month)
            ->where('p.conversion', 1)
            ->groupBy('p.prospect_id');

        $prospects = $query->get()->getResultArray();

        // Langkah 2: Hitung total actual revenue berdasarkan jumlah divisi yang terkait dengan setiap prospect
        $totalActualRevenue = 0;

        foreach ($prospects as $prospect) {
            // Hitung jumlah divisi yang terkait dengan prospect ini
            $divisionCount = $this->db->table('prospect_divisions')
                ->where('prospect_id', $prospect['prospect_id'])
                ->countAllResults();

            // Bagi actual revenue dengan jumlah divisi terkait
            if ($divisionCount > 0) {
                $totalActualRevenue += $prospect['actual_revenue'] / $divisionCount;
            }
        }

        return $totalActualRevenue;
    }

}
