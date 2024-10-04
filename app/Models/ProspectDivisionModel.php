<?php

namespace App\Models;

use CodeIgniter\Model;

class ProspectDivisionModel extends Model
{
    protected $table = 'prospect_divisions'; // Tabel pivot untuk prospects dan divisions
    protected $primaryKey = 'id'; // Primary key dari tabel
    protected $allowedFields = ['prospect_id', 'division_id']; // Kolom yang bisa diisi
}
