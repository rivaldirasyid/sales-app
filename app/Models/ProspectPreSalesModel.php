<?php

namespace App\Models;

use CodeIgniter\Model;

class ProspectPreSalesModel extends Model
{
    protected $table = 'prospect_pre_sales'; // Tabel pivot untuk prospects dan pre-sales
    protected $primaryKey = 'id'; // Primary key dari tabel
    protected $allowedFields = ['prospect_id', 'pre_sales_id']; // Kolom yang bisa diisi
}
