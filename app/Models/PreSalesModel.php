<?php

namespace App\Models;

use CodeIgniter\Model;

class PreSalesModel extends Model
{
    protected $table = 'pre_sales_team';
    protected $primaryKey = 'pre_sales_id';
    protected $allowedFields = ['name', 'division_id', 'status'];

    // Mengambil data pre-sales beserta informasi divisinya
    public function getPreSalesWithDivision()
    {
        return $this->select('pre_sales_team.*, divisions.division_name')
            ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
            ->findAll();
    }

    // Mendapatkan detail Pre-Sales berdasarkan ID, termasuk divisi
    public function getPreSalesWithDivisionById($pre_sales_id)
    {
        return $this->select('pre_sales_team.*, divisions.division_name')
            ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
            ->where('pre_sales_team.pre_sales_id', $pre_sales_id)
            ->first();
    }
}