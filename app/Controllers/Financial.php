<?php

namespace App\Controllers;

use App\Models\FinancialModel;
use App\Models\ProspectModel;

class Financial extends BaseController
{
    protected $financialModel;
    protected $prospectModel;

    public function __construct()
    {
        $this->financialModel = new FinancialModel();
        $this->prospectModel = new ProspectModel();
    }

    // Menampilkan daftar financial berdasarkan user yang login
    public function index()
    {
        $session = session();
        $role = $session->get('role');
        $userId = $session->get('user_id');

        // Ambil parameter untuk pencarian, pagination, dan jumlah entri
        $search = $this->request->getVar('search');
        $limit = $this->request->getVar('limit') ?? 10; // Default 10 entri
        $page = $this->request->getVar('page') ?? 1;

        // Ambil data dengan pagination dan pencarian dari model
        $query = $this->financialModel->getFinancialWithPaginationAndSearch($userId, $role, $search);

        // Sebelum melakukan paginate, hitung total
        $total = $query->countAllResults(false); // Gunakan false agar tidak mengosongkan builder

        // Ambil data dengan pagination
        $financialData = $query->paginate($limit, 'financials');

        // Ambil pager dari model, bukan dari query
        $pager = $this->financialModel->pager;

        // Kirim data ke view
        $data = [
            'financials' => $financialData,
            'pager' => $pager,
            'limit' => $limit,
            'search' => $search,
            'total' => $total
        ];

        return view('financial/index', $data);
    }


    // Menampilkan form untuk menambah data financial baru
    public function create($prospect_id)
    {
        $prospect = $this->prospectModel
            ->select('prospects.*, customers.customer_name')  // Mengambil customer_name juga
            ->join('customers', 'customers.customer_id = prospects.customer_id', 'left')
            ->find($prospect_id);

        // Cek apakah prospect ada
        if (!$prospect) {
            return redirect()->to('/prospect')->with('error', 'Prospect not found');
        }

        $data = [
            'prospect' => $prospect
        ];

        return view('financial/create', $data);
    }

    // Menyimpan data financial baru
    public function store()
    {
        $this->financialModel->save([
            'prospect_id' => $this->request->getPost('prospect_id'),
            'hpp' => $this->request->getPost('hpp'),
            'plan_budget_sales' => $this->request->getPost('plan_budget_sales'),
            'margin' => $this->request->getPost('margin')
        ]);

        return redirect()->to('/prospect/view/' . $this->request->getPost('prospect_id'))->with('success', 'Financial details added successfully.');
    }
}
