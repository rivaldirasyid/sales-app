<?php

namespace App\Controllers;

use App\Models\DivisionModel;

class DivisionManagement extends BaseController
{
    protected $divisionModel;

    public function __construct()
    {
        $this->divisionModel = new DivisionModel();
    }

    // Daftar divisions
    public function divisionIndex()
    {
        $search = $this->request->getVar('search') ?? ''; // Set default value menjadi string kosong jika $search adalah null
        $divisions = $this->divisionModel
            ->like('division_name', $search)
            ->orLike('division_leader', $search)
            ->findAll();

        return view('division/index', [
            'divisions' => $divisions,
            'search' => $search
        ]);
    }

    // Form tambah division
    public function divisionCreate()
    {
        return view('division/create');
    }

    // Simpan division baru
    public function divisionStore()
    {
        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'division_leader' => $this->request->getPost('division_leader'),
        ];

        $this->divisionModel->save($data);
        return redirect()->to('/division')->with('success', 'Division added successfully.');
    }

    // Form edit division
    public function divisionEdit($division_id)
    {
        $division = $this->divisionModel->find($division_id);

        if (!$division) {
            return redirect()->back()->with('error', 'Division not found.');
        }

        return view('division/edit', [
            'division' => $division
        ]);
    }

    // Update division
    public function divisionUpdate($division_id)
    {
        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'division_leader' => $this->request->getPost('division_leader'),
        ];

        $this->divisionModel->update($division_id, $data);
        return redirect()->to('/division')->with('success', 'Division updated successfully.');
    }

    // Hapus division
    public function divisionDelete($division_id)
    {
        $this->divisionModel->delete($division_id);
        return redirect()->to('/division')->with('success', 'Division deleted successfully.');
    }
}
