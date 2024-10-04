<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProspectModel;
use App\Models\CustomerModel; // Pastikan Anda memiliki model CustomerModel atau buat jika belum ada

class Dashboard extends BaseController
{
    protected $userModel;
    protected $prospectModel;
    protected $customerModel; // Tambahkan CustomerModel

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->prospectModel = new ProspectModel();
        $this->customerModel = new CustomerModel(); // Inisialisasi CustomerModel
    }

    public function index()
    {
        // Ambil tahun saat ini
        $year = date('Y');

        // Ambil data top sales reps berdasarkan total actual revenue tahun berjalan
        $topSalesReps = $this->getTopSalesReps($year);

        // Ambil data top customers berdasarkan total actual revenue tahun berjalan
        $topCustomers = $this->getTopCustomers($year);

        // Ambil data untuk dashboard summary
        $dashboardSummary = $this->getDashboardSummary();

        // Ambil data revenue berdasarkan tipe customer dari semua user
        $customerRevenueAllUsers = $this->prospectModel->getRevenueByCustomerTypeForAllUsers($year);

        // Data user dari session
        $fullName = session()->get('full_name');

        return view('dashboard/index', [
            'fullName' => $fullName,
            'topSalesReps' => $topSalesReps,
            'topCustomers' => $topCustomers, // Tambahkan data top customers
            'dashboardSummary' => $dashboardSummary, // Tambahkan data dashboard summary
            'customerRevenueAllUsers' => $customerRevenueAllUsers // Tambahkan data customer revenue semua user
        ]);
    }

    private function getTopSalesReps($year)
    {
        // Query untuk mendapatkan top sales reps berdasarkan total actual revenue pada tahun berjalan
        $topSalesReps = $this->prospectModel->select('users.full_name, SUM(prospects.actual_revenue) as total_revenue')
            ->join('users', 'prospects.user_id = users.user_id')
            ->where('prospects.conversion', 1) // Hanya yang berhasil dikonversi
            ->where('YEAR(prospects.conversion_date)', $year) // Filter berdasarkan tahun konversi
            ->groupBy('users.user_id')
            ->orderBy('total_revenue', 'DESC')
            ->limit(5) // Ambil 5 sales reps teratas
            ->findAll();

        return $topSalesReps;
    }

    private function getTopCustomers($year)
    {
        // Query untuk mendapatkan top customers berdasarkan total actual revenue pada tahun berjalan
        $topCustomers = $this->prospectModel->select('customers.customer_name, SUM(prospects.actual_revenue) as total_revenue')
            ->join('customers', 'prospects.customer_id = customers.customer_id')
            ->where('prospects.conversion', 1) // Hanya yang berhasil dikonversi
            ->where('YEAR(prospects.conversion_date)', $year) // Filter berdasarkan tahun konversi
            ->groupBy('customers.customer_id')
            ->orderBy('total_revenue', 'DESC')
            ->limit(5) // Ambil 5 customers teratas
            ->findAll();

        return $topCustomers;
    }


    private function getDashboardSummary()
    {
        // Total Estimated Revenue
        $totalEstimatedRevenue = $this->prospectModel->selectSum('estimated_revenue') // Hanya prospects yang belum dikonversi
            ->first();

        // Total Actual Revenue
        $totalActualRevenue = $this->prospectModel->selectSum('actual_revenue')
            ->where('conversion', 1) // Hanya prospects yang sudah dikonversi
            ->first();

        // Total Number of Prospects
        $totalProspects = $this->prospectModel->countAllResults();

        // Total Converted Prospects
        $totalConvertedProspects = $this->prospectModel->where('conversion', 1)
            ->countAllResults();

        // Return data summary dalam bentuk array
        return [
            'total_estimated_revenue' => $totalEstimatedRevenue['estimated_revenue'] ?? 0,
            'total_actual_revenue' => $totalActualRevenue['actual_revenue'] ?? 0,
            'total_prospects' => $totalProspects,
            'total_converted_prospects' => $totalConvertedProspects
        ];
    }

    public function getDashboardData($year = null)
    {
        $year = $year ?: date('Y'); // Jika tidak ada parameter tahun, gunakan tahun saat ini

        // Dapatkan data revenue bulanan berdasarkan tipe customer dari semua user
        $customerRevenueAllUsers = $this->prospectModel->getRevenueByCustomerTypeForAllUsers($year);

        // Dapatkan data untuk summary dashboard berdasarkan tahun
        $dashboardSummary = $this->getDashboardSummaryByYear($year);

        // Tambahkan log untuk debugging
        log_message('debug', 'Mengambil data dashboard untuk tahun: ' . $year);

        $response = [
            'customerRevenueAllUsers' => $customerRevenueAllUsers, // Data revenue bulanan berdasarkan tipe customer
            'dashboardSummary' => $dashboardSummary // Data summary dashboard untuk tahun yang dipilih
        ];

        return $this->response->setJSON($response);
    }

    private function getDashboardSummaryByYear($year)
    {
        // Total Estimated Revenue berdasarkan tahun
        $totalEstimatedRevenue = $this->prospectModel->selectSum('estimated_revenue')
            ->where('YEAR(created_at)', $year)
            ->first();

        // Total Actual Revenue berdasarkan tahun
        $totalActualRevenue = $this->prospectModel->selectSum('actual_revenue')
            ->where('conversion', 1)
            ->where('YEAR(conversion_date)', $year)
            ->first();

        // Total Number of Prospects berdasarkan tahun
        $totalProspects = $this->prospectModel
            ->where('YEAR(created_at)', $year)
            ->countAllResults();

        // Total Converted Prospects berdasarkan tahun
        $totalConvertedProspects = $this->prospectModel
            ->where('conversion', 1)
            ->where('YEAR(conversion_date)', $year)
            ->countAllResults();

        // Return data summary dalam bentuk array
        return [
            'total_estimated_revenue' => $totalEstimatedRevenue['estimated_revenue'] ?? 0,
            'total_actual_revenue' => $totalActualRevenue['actual_revenue'] ?? 0,
            'total_prospects' => $totalProspects,
            'total_converted_prospects' => $totalConvertedProspects
        ];
    }
}
