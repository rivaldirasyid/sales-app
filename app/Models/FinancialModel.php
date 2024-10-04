<?php

namespace App\Models;

use CodeIgniter\Model;

class FinancialModel extends Model
{
    protected $table = 'financial_details';  // Nama tabel di database
    protected $primaryKey = 'financial_id';  // Primary key tabel

    // Kolom yang dapat diisi melalui input pengguna
    protected $allowedFields = ['prospect_id', 'hpp', 'plan_budget_sales', 'margin'];

    // Mengaktifkan fitur timestamp
    protected $useTimestamps = true;  // Otomatis menambahkan created_at dan updated_at
    protected $createdField = 'created_at';  // Kolom untuk tanggal dibuat
    protected $updatedField = 'updated_at';  // Kolom untuk tanggal diperbarui

    // Method untuk mengambil data financial berdasarkan prospect dan user
    public function getFinancialByUser($userId)
    {
        return $this->select('financial_details.*,  
                          customers.customer_name, 
                          prospects.prospect_scope, 
                          prospects.estimated_revenue')
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id')
            ->join('customers', 'customers.customer_id = prospects.customer_id') // Join ke tabel customers
            ->where('prospects.user_id', $userId)
            ->findAll();
    }

    public function getAllFinancial()
    {
        return $this->select('financial_details.*, 
                          customers.customer_name, 
                          prospects.prospect_scope, 
                          prospects.estimated_revenue') // Menambahkan field scope dan estimated revenue
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id')
            ->join('customers', 'customers.customer_id = prospects.customer_id') // Join ke tabel customers
            ->findAll();
    }

    public function getFinancialWithPaginationAndSearch($userId = null, $role = 'Admin', $search = null)
    {
        $builder = $this->select('
        financial_details.*, 
        customers.customer_name, 
        prospects.prospect_scope, 
        prospects.estimated_revenue
    ')
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id', 'left')
            ->join('customers', 'customers.customer_id = prospects.customer_id', 'left');

        if ($role === 'Sales') {
            $builder = $builder->where('prospects.user_id', $userId);
        }


        if ($search) {
            $builder = $builder->groupStart()
                ->like('customers.customer_name', $search)
                ->orLike('prospects.prospect_scope', $search)
                ->groupEnd();
        }

        return $builder;
    }


    public function getMonthlyFinancialPerformance($userId, $month, $year)
    {
        // Ambil data total revenue, gross profit, dan margin dari prospect
        $financialData = $this->select('
            SUM(prospects.actual_revenue) as total_sales,
            SUM(financial_details.margin) as gross_profit,
            (SUM(financial_details.margin) / SUM(financial_details.plan_budget_sales)) * 100 as margin
        ')
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id')
            ->where('prospects.user_id', $userId)
            ->where('prospects.conversion', 1) // Pastikan hanya mengambil prospect yang sudah dikonversi
            ->where('MONTH(financial_details.created_at)', $month)
            ->where('YEAR(financial_details.created_at)', $year)
            ->first();

        // Ambil data penjualan bulanan untuk line chart menggunakan actual_revenue
        $salesByMonth = $this->select('
            MONTH(financial_details.created_at) as month,
            SUM(prospects.actual_revenue) as total_sales
        ')
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id')
            ->where('prospects.user_id', $userId)
            ->where('prospects.conversion', 1) // Hanya hitung yang sudah dikonversi
            ->where('YEAR(financial_details.created_at)', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();

        // Struktur data untuk sales by month
        $totalSalesByMonth = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
        foreach ($salesByMonth as $monthData) {
            $totalSalesByMonth[$monthData['month'] - 1] = $monthData['total_sales'];
        }

        // Ambil data revenue bulanan untuk bar chart menggunakan actual_revenue
        $revenueByMonth = $this->select('
            MONTH(financial_details.created_at) as month,
            SUM(prospects.actual_revenue) as revenue
        ')
            ->join('prospects', 'prospects.prospect_id = financial_details.prospect_id')
            ->where('prospects.user_id', $userId)
            ->where('prospects.conversion', 1) // Hanya hitung yang sudah dikonversi
            ->where('YEAR(financial_details.created_at)', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();

        $totalRevenueByMonth = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
        foreach ($revenueByMonth as $monthData) {
            $totalRevenueByMonth[$monthData['month'] - 1] = $monthData['revenue'];
        }

        return array_merge($financialData, [
            'total_sales_by_month' => $totalSalesByMonth,
            'revenue_by_month' => $totalRevenueByMonth,
            'labels' => $this->getMonthLabels()
        ]);
    }


    private function getMonthLabels()
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

}
