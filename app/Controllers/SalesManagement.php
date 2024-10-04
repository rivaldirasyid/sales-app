<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PreSalesModel;
use App\Models\DivisionModel;

class SalesManagement extends BaseController
{
    protected $userModel;
    protected $preSalesModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->preSalesModel = new PreSalesModel();
        $this->divisionModel = new DivisionModel();
    }

    // Daftar sales
    public function salesIndex()
    {
        $search = $this->request->getVar('search') ?? ''; // Set default value menjadi string kosong jika $search adalah null
        $sales = $this->userModel->where('role', 'Sales')
            ->like('full_name', $search)
            ->orLike('email', $search)
            ->orLike('username', $search)
            ->findAll();

        return view('sales/index', [
            'sales' => $sales,
            'search' => $search
        ]);
    }

    // Form tambah sales
    public function salesCreate()
    {
        return view('sales/create');
    }

    // Simpan sales baru
    public function salesStore()
    {
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password_hash' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
        ];

        $this->userModel->save($data);
        return redirect()->to('/sales')->with('success', 'Sales added successfully.');
    }

    // Edit Sales
    public function salesEdit($id)
    {
        $sale = $this->userModel->find($id);
        return view('sales/edit', [
            'sale' => $sale
        ]);
    }

    // Update Sales
    public function salesUpdate($id)
    {
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password_hash' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
        ];

        $this->userModel->update($id, $data);
        return redirect()->to('/sales')->with('success', 'Sales updated successfully.');
    }

    // Hapus Sales
    public function salesDelete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/sales')->with('success', 'Sales deleted successfully.');
    }

    // Daftar pre-sales
    public function preSalesIndex()
    {
        $search = $this->request->getVar('search') ?? ''; // Set default value menjadi string kosong jika $search adalah null

        // Query pencarian berdasarkan name, status, dan division_name
        $pre_sales = $this->preSalesModel->select('pre_sales_team.*, divisions.division_name')
            ->join('divisions', 'pre_sales_team.division_id = divisions.division_id', 'left')
            ->groupStart() // Buka grup kondisi pencarian
            ->like('pre_sales_team.name', $search)
            ->orLike('pre_sales_team.status', $search)
            ->orLike('divisions.division_name', $search)
            ->groupEnd() // Tutup grup kondisi pencarian
            ->findAll();

        return view('presales/index', [
            'pre_sales' => $pre_sales,
            'search' => $search
        ]);
    }

    // Form tambah pre-sales
    public function preSalesCreate()
    {
        $divisionModel = new DivisionModel();
        $divisions = $divisionModel->findAll();
        return view('presales/create', [
            'divisions' => $divisions
        ]);
    }

    // Simpan pre-sales baru
    public function preSalesStore()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'division_id' => $this->request->getPost('division_id'),
            'status' => $this->request->getPost('status'),
        ];

        $this->preSalesModel->save($data);
        return redirect()->to('/pre-sales')->with('success', 'Pre-Sales added successfully.');
    }

    // Edit Pre-Sales
    public function preSalesEdit($id)
    {
        $preSales = $this->preSalesModel->getPreSalesWithDivisionById($id);
        $divisions = $this->divisionModel->findAll();
        return view('presales/edit', [
            'preSales' => $preSales,
            'divisions' => $divisions
        ]);
    }

    // Update Pre-Sales
    public function preSalesUpdate($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'division_id' => $this->request->getPost('division_id'),
            'status' => $this->request->getPost('status'),
        ];

        $this->preSalesModel->update($id, $data);
        return redirect()->to('/pre-sales')->with('success', 'Pre-Sales updated successfully.');
    }

    // Hapus Pre-Sales
    public function preSalesDelete($id)
    {
        $this->preSalesModel->delete($id);
        return redirect()->to('/pre-sales')->with('success', 'Pre-Sales deleted successfully.');
    }
}
