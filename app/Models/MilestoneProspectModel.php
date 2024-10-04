<?php

namespace App\Models;

use CodeIgniter\Model;

class MilestoneProspectModel extends Model
{
    protected $table = 'milestones_prospect';
    protected $primaryKey = 'milestone_id';
    protected $allowedFields = ['prospect_id', 'milestone_name', 'milestone_status', 'milestone_date', 'notes', 'milestone_index', 'progress_percentage', 'milestone_document'];

    // Mengambil semua milestone dengan progress untuk user tertentu (termasuk yang progressnya 0%)
    public function getMilestonesWithPaginationAndSearch($user_id = null, $role = 'Admin', $search = null)
    {
        $builder = $this->select('
        prospects.prospect_id,
        customers.customer_name,
        users.full_name AS sales_utama,
        GROUP_CONCAT(DISTINCT divisions.division_name ORDER BY divisions.division_name SEPARATOR "\n") as division_names,
        (SELECT COUNT(*) FROM milestones_prospect WHERE prospect_id = prospects.prospect_id AND milestone_status = "Completed") * 10 AS progress
    ')
            ->join('prospects', 'prospects.prospect_id = milestones_prospect.prospect_id', 'left')
            ->join('customers', 'prospects.customer_id = customers.customer_id', 'left')
            ->join('users', 'users.user_id = prospects.user_id', 'left')
            ->join('prospect_divisions', 'prospect_divisions.prospect_id = prospects.prospect_id', 'left')
            ->join('divisions', 'divisions.division_id = prospect_divisions.division_id', 'left')
            ->groupBy('prospects.prospect_id');

        if ($role === 'Sales') {
            $builder->where('prospects.user_id', $user_id);
        }

        if ($search) {
            $builder->groupStart()
                ->like('customers.customer_name', $search)
                ->orLike('divisions.division_name', $search)
                ->orLike('users.full_name', $search)
                ->groupEnd();
        }

        return $builder;
    }



    // Mengambil milestone yang sudah dicapai oleh prospect
    public function getMilestonesByProspect($prospect_id)
    {
        return $this->select('milestones_prospect.*, prospects.user_id')
            ->join('prospects', 'prospects.prospect_id = milestones_prospect.prospect_id')
            ->where('milestones_prospect.prospect_id', $prospect_id)
            ->orderBy('milestone_index', 'ASC')
            ->findAll();
    }


    // Menghitung persentase progres berdasarkan milestone yang sudah completed
    public function calculateProgress($prospect_id)
    {
        // Daftar milestone yang seharusnya ada (sesuai urutan)
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

        $totalMilestones = count($milestoneOrder); // Total milestone yang seharusnya ada
        $completedMilestones = $this->where('prospect_id', $prospect_id)
            ->where('milestone_status', 'Completed')
            ->countAllResults(); // Hitung milestone yang sudah completed

        // Hitung persentase progress
        $progress = ($completedMilestones / $totalMilestones) * 100;

        return $progress;
    }

    // Mendapatkan milestone terakhir yang sudah selesai untuk prospect terkait
    public function getLastCompletedMilestone($prospect_id)
    {
        return $this->where('prospect_id', $prospect_id)
            ->where('milestone_status', 'Completed')
            ->orderBy('milestone_index', 'DESC')
            ->first();
    }

    // Mendapatkan milestone berikutnya berdasarkan urutan
    public function getNextMilestone($prospect_id, $milestoneOrder)
    {
        $lastCompletedMilestone = $this->getLastCompletedMilestone($prospect_id);

        if ($lastCompletedMilestone) {
            $lastIndex = array_search($lastCompletedMilestone['milestone_name'], $milestoneOrder);
            return $lastIndex !== false && isset($milestoneOrder[$lastIndex + 1]) ? $milestoneOrder[$lastIndex + 1] : null;
        }

        return $milestoneOrder[0]; // Jika belum ada milestone, kembalikan yang pertama
    }

    public function isLastMilestoneCompleted($prospect_id)
    {
        // Periksa apakah milestone terakhir yaitu 'Contract Signed' sudah selesai
        $lastMilestone = $this->where('prospect_id', $prospect_id)
            ->where('milestone_name', 'Contract Signed')
            ->where('milestone_status', 'Completed')
            ->countAllResults();

        return $lastMilestone > 0;
    }

    public function getMonthlyProjectPerformance($userId, $month, $year)
    {
        // Jumlah Proyek yang Selesai
        $completedProjects = $this->select('COUNT(DISTINCT milestones_prospect.prospect_id) as total_projects')
            ->join('prospects', 'prospects.prospect_id = milestones_prospect.prospect_id')
            ->where('prospects.user_id', $userId)
            ->where('milestones_prospect.milestone_status', 'Completed')
            ->where('milestones_prospect.milestone_name', 'Contract Signed')
            ->where('MONTH(milestones_prospect.milestone_date)', $month)
            ->where('YEAR(milestones_prospect.milestone_date)', $year)
            ->first();

        // Performa Berdasarkan Proyek (Detail Per Project)
        $projectDetails = $this->select('
                prospects.prospect_id,
                prospects.prospect_scope,
                prospects.estimated_revenue,
                milestones_prospect.milestone_name,
                milestones_prospect.milestone_status,
                milestones_prospect.milestone_date
            ')
            ->join('prospects', 'prospects.prospect_id = milestones_prospect.prospect_id')
            ->where('prospects.user_id', $userId)
            ->where('MONTH(milestones_prospect.milestone_date)', $month)
            ->where('YEAR(milestones_prospect.milestone_date)', $year)
            ->findAll();

        return [
            'total_projects' => $completedProjects['total_projects'] ?? 0,
            'project_details' => $projectDetails,
        ];
    }

}
