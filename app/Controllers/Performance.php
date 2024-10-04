<?php

namespace App\Controllers;

use App\Models\FinancialModel;
use App\Models\ProspectModel;
use App\Models\MilestoneProspectModel;
use CodeIgniter\Controller;

class Performance extends Controller
{
    protected $financialModel;
    protected $prospectModel;
    protected $milestoneProspectModel;

    public function __construct()
    {
        $this->financialModel = new FinancialModel();
        $this->prospectModel = new ProspectModel();
        $this->milestoneProspectModel = new MilestoneProspectModel();
    }

    public function index()
    {
        $userId = session()->get('user_id'); // Dapatkan user_id dari session
        $month = date('m'); // Bulan saat ini
        $year = date('Y'); // Tahun saat ini

        // Dapatkan data performa bulanan berdasarkan user_id, month, dan year
        $financialPerformance = $this->financialModel->getMonthlyFinancialPerformance($userId, $month, $year);
        $prospectPerformance = $this->prospectModel->getMonthlyProspectPerformance($userId, $month, $year);
        $projectPerformance = $this->milestoneProspectModel->getMonthlyProjectPerformance($userId, $month, $year);

        // Dapatkan data total prospek bulan ini berdasarkan tipe customer
        $totalProspects = $this->prospectModel->getMonthlyProspectByType($userId, $month, $year);

        // Dapatkan data revenue berdasarkan tipe customer
        $customerRevenue = $this->prospectModel->getRevenueByCustomerType($userId, $year);

        // Dapatkan summary bulanan
        $monthlySummary = $this->prospectModel->getMonthlySummary($userId, $month, $year);

        // Dapatkan summary keseluruhan
        $overallSummary = $this->prospectModel->getOverallSummary($userId);

        // Buat labels untuk 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $labels = array_slice($months, 0, intval($month)); // Ambil nama bulan sampai bulan saat ini

        // Data performa penjualan bulanan (menggunakan data asli)
        $salesPerformance = [
            'total_sales' => $financialPerformance['total_revenue'] ?? 0,
            'converted_prospects' => $prospectPerformance['converted_prospects'] ?? 0,
            'avg_conversion_time' => $prospectPerformance['avg_conversion_time'] ?? 0,
            'labels' => $labels,
            'total_sales_by_month' => $financialPerformance['total_sales_by_month'] ?? [], // Data asli total sales per bulan
            'total_prospects' => $totalProspects // Total prospects berdasarkan tipe customer
        ];

        // Data performa keuangan bulanan (menggunakan data asli)
        $financialSummary = [
            'total_revenue' => $financialPerformance['revenue_by_month'] ?? [], // Data asli revenue per bulan
            'gross_profit' => $financialPerformance['gross_profit'] ?? [], // Data asli gross profit
            'labels' => $labels
        ];

        // Data performa proyek
        $projectSummary = [
            'completed_projects' => $projectPerformance['total_projects'] ?? 0,
            'project_details' => $projectPerformance['project_details'] ?? [],
        ];

        // Kirim data ke view
        return view('performance/index', [
            'salesPerformance' => $salesPerformance,
            'financialSummary' => $financialSummary,
            'projectSummary' => $projectSummary,
            'customerRevenue' => $customerRevenue, // Tambahkan data customer revenue
            'monthlySummary' => $monthlySummary, // Tambahkan data summary bulanan
            'overallSummary' => $overallSummary // Tambahkan data summary keseluruhan
        ]);
    }

    public function getPerformanceData()
    {
        $userId = session()->get('user_id'); // Pastikan session user_id ada
        $year = date('Y'); // Tahun saat ini

        // Jika user_id tidak ada, kirim response error
        if (!$userId) {
            return $this->response->setJSON(['error' => 'User ID not found'])->setStatusCode(400);
        }

        // Dapatkan data revenue bulanan dari ProspectModel
        $prospectRevenue = $this->prospectModel->getMonthlyProspectRevenue($userId, $year);

        // Dapatkan data revenue bulanan berdasarkan tipe customer
        $customerRevenue = $this->prospectModel->getRevenueByCustomerType($userId, $year);

        // Dapatkan total prospects bulan ini untuk KSG dan Non-KSG
        $totalProspects = $this->prospectModel->getMonthlyProspectByType($userId, date('m'), date('Y'));

        $response = [
            'salesPerformance' => [
                'labels' => $prospectRevenue['labels'],
                'estimated_revenue' => $prospectRevenue['estimated_revenue'] // Total estimated revenue per bulan
            ],
            'financialSummary' => [
                'labels' => $prospectRevenue['labels'],
                'actual_revenue' => $prospectRevenue['actual_revenue'] // Total actual revenue per bulan
            ],
            'customerRevenue' => $customerRevenue, // Tambahkan data revenue bulanan berdasarkan tipe customer
            'totalProspects' => $totalProspects // Tambahkan data total prospects untuk pie chart
        ];

        return $this->response->setJSON($response);
    }
}
