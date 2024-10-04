<?php

namespace App\Models;

use CodeIgniter\Model;

class ProspectModel extends Model
{
    protected $table = 'prospects';
    protected $primaryKey = 'prospect_id';
    protected $allowedFields = [
        'customer_id',
        'prospect_scope',
        'estimated_revenue',
        'actual_revenue',
        'projected_quarter',
        'target_month_contract',
        'user_id',
        'prospect_status',
        'conversion',
        'conversion_date',
        'remarks'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllProspectsWithDetails()
    {
        // Ambil data prospects dengan detail yang diperlukan
        $prospects = $this->select('
        prospects.*, 
        customers.customer_name,
        users.full_name AS sales_utama,
        financial_details.hpp,
        financial_details.plan_budget_sales,
        financial_details.margin
    ')
            ->join('customers', 'prospects.customer_id = customers.customer_id', 'left')
            ->join('users', 'prospects.user_id = users.user_id', 'left')
            ->join('financial_details', 'prospects.prospect_id = financial_details.prospect_id', 'left')
            ->findAll();

        // Ambil semua milestones terkait untuk setiap prospect
        $milestoneModel = new MilestoneProspectModel();
        foreach ($prospects as &$prospect) {
            // Hitung progress menggunakan calculateProgress()
            $prospect['progress'] = $milestoneModel->calculateProgress($prospect['prospect_id']) . '%';

            // Ambil detail milestones untuk setiap prospect
            $milestones = $milestoneModel->select('milestone_name, progress_percentage')
                ->where('prospect_id', $prospect['prospect_id'])
                ->findAll();

            foreach ($milestones as $milestone) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestone['milestone_name']));
                $prospect[$milestoneKey] = $milestone['progress_percentage'] . '%';
            }

            // Jika milestone tertentu belum ada, beri nilai default 0%
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
            foreach ($milestoneOrder as $milestone) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestone));
                if (!isset($prospect[$milestoneKey])) {
                    $prospect[$milestoneKey] = '0%';
                }
            }
        }

        return $prospects;
    }

    public function getAllProspectsWithDetailsByYear($year, $search = null)
    {
        // Query dasar untuk mengambil prospek berdasarkan tahun dengan detail yang diperlukan
        $query = $this->select('
        prospects.*, 
        customers.customer_name,
        users.full_name AS sales_utama,
        financial_details.hpp,
        financial_details.plan_budget_sales,
        financial_details.margin
    ')
            ->join('customers', 'prospects.customer_id = customers.customer_id', 'left')
            ->join('users', 'prospects.user_id = users.user_id', 'left')
            ->join('financial_details', 'prospects.prospect_id = financial_details.prospect_id', 'left')
            ->where('YEAR(prospects.created_at)', $year);

        // Jika ada pencarian, tambahkan filter berdasarkan nama customer atau scope
        if ($search) {
            $query = $query->groupStart()
                ->like('customers.customer_name', $search)
                ->orLike('prospects.prospect_scope', $search)
                ->groupEnd();
        }

        $prospects = $query->findAll();

        // Ambil semua milestones terkait untuk setiap prospect
        $milestoneModel = new MilestoneProspectModel();
        foreach ($prospects as &$prospect) {
            // Hitung progress menggunakan calculateProgress()
            $prospect['progress'] = $milestoneModel->calculateProgress($prospect['prospect_id']) . '%';

            // Ambil detail milestones untuk setiap prospect
            $milestones = $milestoneModel->select('milestone_name, progress_percentage')
                ->where('prospect_id', $prospect['prospect_id'])
                ->findAll();

            foreach ($milestones as $milestone) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestone['milestone_name']));
                $prospect[$milestoneKey] = $milestone['progress_percentage'] . '%';
            }

            // Jika milestone tertentu belum ada, beri nilai default 0%
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
            foreach ($milestoneOrder as $milestone) {
                $milestoneKey = 'milestone_' . strtolower(str_replace(' ', '_', $milestone));
                if (!isset($prospect[$milestoneKey])) {
                    $prospect[$milestoneKey] = '0%';
                }
            }
        }

        return $prospects;
    }

    public function getDivisionsForProspect($prospectId)
    {
        return $this->db->table('prospect_divisions')
            ->select('divisions.division_name')
            ->join('divisions', 'prospect_divisions.division_id = divisions.division_id')
            ->where('prospect_divisions.prospect_id', $prospectId)
            ->get()->getResultArray();
    }

    public function getPreSalesForProspect($prospectId)
    {
        return $this->db->table('prospect_pre_sales')
            ->select('pre_sales_team.pre_sales_id, pre_sales_team.name AS pre_sales_name, pre_sales_team.status AS pre_sales_status, divisions.division_name')
            ->join('pre_sales_team', 'prospect_pre_sales.pre_sales_id = pre_sales_team.pre_sales_id')
            ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
            ->where('prospect_pre_sales.prospect_id', $prospectId)
            ->get()->getResultArray();
    }


    public function saveProspectWithRelations($data, $divisionIds, $preSalesIds)
    {
        $this->save($data);
        $prospectId = $this->getInsertID();

        $prospectDivisionModel = new ProspectDivisionModel();
        foreach ($divisionIds as $divisionId) {
            $prospectDivisionModel->insert([
                'prospect_id' => $prospectId,
                'division_id' => $divisionId,
            ]);
        }

        $prospectPreSalesModel = new ProspectPreSalesModel();
        foreach ($preSalesIds as $preSalesId) {
            $prospectPreSalesModel->insert([
                'prospect_id' => $prospectId,
                'pre_sales_id' => $preSalesId,
            ]);
        }

        return $prospectId;
    }

    public function getMonthlySummary($userId, $month, $year)
    {
        // Total Prospek Bulan Ini
        $totalProspects = $this->where('user_id', $userId)
            ->where('MONTH(created_at)', $month)
            ->where('YEAR(created_at)', $year)
            ->countAllResults();

        // Prospek yang dikonversi Bulan Ini
        $convertedProspects = $this->where('user_id', $userId)
            ->where('conversion', 1)
            ->where('MONTH(conversion_date)', $month)
            ->where('YEAR(conversion_date)', $year)
            ->countAllResults();

        // Estimated Revenue Bulan Ini
        $estimatedRevenue = $this->selectSum('estimated_revenue')
            ->where('user_id', $userId)
            ->where('MONTH(created_at)', $month)
            ->where('YEAR(created_at)', $year)
            ->first();

        // Actual Revenue Bulan Ini
        $actualRevenue = $this->selectSum('actual_revenue')
            ->where('user_id', $userId)
            ->where('conversion', 1)
            ->where('MONTH(conversion_date)', $month)
            ->where('YEAR(conversion_date)', $year)
            ->first();

        return [
            'total_prospects' => $totalProspects,
            'converted_prospects' => $convertedProspects,
            'estimated_revenue' => $estimatedRevenue['estimated_revenue'] ?? 0,
            'actual_revenue' => $actualRevenue['actual_revenue'] ?? 0
        ];
    }

    public function getOverallSummary($userId)
    {
        // Total Prospek Keseluruhan
        $totalProspects = $this->where('user_id', $userId)->countAllResults();

        // Prospek yang dikonversi Keseluruhan
        $convertedProspects = $this->where('user_id', $userId)
            ->where('conversion', 1)
            ->countAllResults();

        // Total Estimated Revenue Keseluruhan
        $estimatedRevenue = $this->selectSum('estimated_revenue')
            ->where('user_id', $userId)
            ->first();

        // Total Actual Revenue Keseluruhan
        $actualRevenue = $this->selectSum('actual_revenue')
            ->where('user_id', $userId)
            ->where('conversion', 1)
            ->first();

        return [
            'total_prospects' => $totalProspects,
            'converted_prospects' => $convertedProspects,
            'estimated_revenue' => $estimatedRevenue['estimated_revenue'] ?? 0,
            'actual_revenue' => $actualRevenue['actual_revenue'] ?? 0
        ];
    }


    public function getMonthlyProspectPerformance($userId, $month, $year)
    {
        // Jumlah Prospek yang berhasil dikonversi
        $convertedProspects = $this->where('user_id', $userId)
            ->where('conversion', 1)  // Hanya hitung yang dikonversi
            ->where('MONTH(conversion_date)', $month)
            ->where('YEAR(conversion_date)', $year)
            ->countAllResults();

        // Rata-rata waktu konversi (dari created_at ke conversion_date)
        $conversionTimes = $this->select('DATEDIFF(conversion_date, created_at) as conversion_time')
            ->where('user_id', $userId)
            ->where('conversion', 1)
            ->where('MONTH(conversion_date)', $month)
            ->where('YEAR(conversion_date)', $year)
            ->findAll();

        $totalConversionTime = 0;
        $totalConversions = count($conversionTimes);

        foreach ($conversionTimes as $time) {
            $totalConversionTime += $time['conversion_time'];
        }

        $avgConversionTime = $totalConversions > 0 ? ($totalConversionTime / $totalConversions) : 0;

        // Mendapatkan label bulan
        $labels = $this->getMonthLabels();

        return [
            'converted_prospects' => $convertedProspects,
            'avg_conversion_time' => $avgConversionTime,
            'labels' => $labels
        ];
    }

    // Method untuk mengambil total estimated_revenue dan actual_revenue bulanan
    public function getMonthlyProspectRevenue($userId, $year)
    {
        // Ambil data estimated_revenue bulanan
        $estimatedRevenueByMonth = $this->select('
                MONTH(created_at) as month,
                SUM(estimated_revenue) as estimated_revenue
            ')
            ->where('user_id', $userId)
            ->where('YEAR(created_at)', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();

        $estimatedRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
        foreach ($estimatedRevenueByMonth as $data) {
            $estimatedRevenueData[$data['month'] - 1] = $data['estimated_revenue'];
        }

        // Ambil data actual_revenue bulanan
        $actualRevenueByMonth = $this->select('
                MONTH(created_at) as month,
                SUM(actual_revenue) as actual_revenue
            ')
            ->where('user_id', $userId)
            ->where('conversion', 1) // Hanya ambil prospect yang sudah dikonversi
            ->where('YEAR(created_at)', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->findAll();

        $actualRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
        foreach ($actualRevenueByMonth as $data) {
            $actualRevenueData[$data['month'] - 1] = $data['actual_revenue'];
        }

        return [
            'estimated_revenue' => $estimatedRevenueData,
            'actual_revenue' => $actualRevenueData,
            'labels' => $this->getMonthLabels()
        ];
    }

    public function getRevenueByCustomerType($userId, $year)
    {
        $customerTypes = ['KSG', 'Non-KSG'];
        $revenueData = [];

        foreach ($customerTypes as $type) {
            // Ambil data estimated revenue bulanan berdasarkan tipe customer
            $estimatedRevenueByMonth = $this->select('
                MONTH(prospects.created_at) as month,
                SUM(estimated_revenue) as estimated_revenue
            ')
                ->join('customers', 'customers.customer_id = prospects.customer_id')
                ->where('customers.type', $type)
                ->where('user_id', $userId)
                ->where('YEAR(prospects.created_at)', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->findAll();

            $estimatedRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
            foreach ($estimatedRevenueByMonth as $data) {
                $estimatedRevenueData[$data['month'] - 1] = $data['estimated_revenue'];
            }

            // Ambil data actual revenue bulanan berdasarkan tipe customer
            $actualRevenueByMonth = $this->select('
                MONTH(prospects.created_at) as month,
                SUM(actual_revenue) as actual_revenue
            ')
                ->join('customers', 'customers.customer_id = prospects.customer_id')
                ->where('customers.type', $type)
                ->where('user_id', $userId)
                ->where('conversion', 1) // Hanya ambil prospect yang sudah dikonversi
                ->where('YEAR(prospects.created_at)', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->findAll();

            $actualRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
            foreach ($actualRevenueByMonth as $data) {
                $actualRevenueData[$data['month'] - 1] = $data['actual_revenue'];
            }

            $revenueData[$type] = [
                'estimated_revenue' => $estimatedRevenueData,
                'actual_revenue' => $actualRevenueData,
                'labels' => $this->getMonthLabels()
            ];
        }

        return $revenueData;
    }

    public function getMonthlyProspectByType($userId, $month, $year)
    {
        $prospectData = $this->select('customers.type, COUNT(*) as total')
            ->join('customers', 'prospects.customer_id = customers.customer_id') // Join dengan tabel customers
            ->where('prospects.user_id', $userId)
            ->where('MONTH(prospects.created_at)', $month)
            ->where('YEAR(prospects.created_at)', $year)
            ->groupBy('customers.type') // Kelompokkan berdasarkan tipe customer
            ->findAll();

        // Atur hasil dalam format KSG dan Non-KSG
        $data = [
            'KSG' => 0,
            'NonKSG' => 0
        ];

        foreach ($prospectData as $prospect) {
            if ($prospect['type'] === 'KSG') {
                $data['KSG'] = $prospect['total'];
            } elseif ($prospect['type'] === 'Non-KSG') {
                $data['NonKSG'] = $prospect['total'];
            }
        }

        return $data;
    }

    public function getRevenueByCustomerTypeForAllUsers($year)
    {
        $customerTypes = ['KSG', 'Non-KSG'];
        $revenueData = [];

        foreach ($customerTypes as $type) {
            // Ambil data estimated revenue bulanan berdasarkan tipe customer
            $estimatedRevenueByMonth = $this->select('
            MONTH(prospects.created_at) as month,
            SUM(estimated_revenue) as estimated_revenue
        ')
                ->join('customers', 'customers.customer_id = prospects.customer_id')
                ->where('customers.type', $type)
                ->where('YEAR(prospects.created_at)', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->findAll();

            $estimatedRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
            foreach ($estimatedRevenueByMonth as $data) {
                $estimatedRevenueData[$data['month'] - 1] = $data['estimated_revenue'];
            }

            // Ambil data actual revenue bulanan berdasarkan tipe customer
            $actualRevenueByMonth = $this->select('
            MONTH(prospects.created_at) as month,
            SUM(actual_revenue) as actual_revenue
        ')
                ->join('customers', 'customers.customer_id = prospects.customer_id')
                ->where('customers.type', $type)
                ->where('conversion', 1) // Hanya ambil prospect yang sudah dikonversi
                ->where('YEAR(prospects.created_at)', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->findAll();

            $actualRevenueData = array_fill(0, 12, 0); // Isi array dengan 0 untuk 12 bulan
            foreach ($actualRevenueByMonth as $data) {
                $actualRevenueData[$data['month'] - 1] = $data['actual_revenue'];
            }

            $revenueData[$type] = [
                'estimated_revenue' => $estimatedRevenueData,
                'actual_revenue' => $actualRevenueData,
                'labels' => $this->getMonthLabels()
            ];
        }

        return $revenueData;
    }



    private function getMonthLabels()
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }

    public function getTotalProspects($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

}
