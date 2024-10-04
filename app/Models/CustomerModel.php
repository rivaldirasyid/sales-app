<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers'; // Nama tabel yang akan digunakan
    protected $primaryKey = 'customer_id'; // Primary key dari tabel
    protected $allowedFields = ['customer_name', 'customer_email', 'customer_phone', 'customer_address', 'type']; // Tambahkan 'type' di sini
    protected $useTimestamps = true; // Aktifkan jika menggunakan created_at dan updated_at
}
