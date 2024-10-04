<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisionModel extends Model
{
    protected $table = 'divisions';
    protected $primaryKey = 'division_id';
    protected $allowedFields = ['division_name', 'division_leader'];
}
