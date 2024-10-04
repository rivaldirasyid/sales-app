<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customer extends BaseController
{
    public function create()
    {
        return view('customers/create');
    }

    public function store()
    {
        $customerModel = new CustomerModel();

        // Ambil data dari form termasuk origin dan prospect_id
        $customerData = [
            'customer_name' => $this->request->getPost('customer_name'),
            'customer_email' => $this->request->getPost('customer_email'),
            'customer_phone' => $this->request->getPost('customer_phone'),
            'customer_address' => $this->request->getPost('customer_address'),
            'type' => $this->request->getPost('type')
        ];

        $origin = $this->request->getPost('origin'); // Ambil origin dari form
        $prospect_id = $this->request->getPost('prospect_id'); // Ambil prospect_id dari form

        // Simpan data customer baru
        $customerModel->save($customerData);

        // Redirect berdasarkan origin
        if ($origin === 'edit' && $prospect_id) {
            return redirect()->to('/prospect/edit/' . $prospect_id)->with('success', 'Customer berhasil ditambahkan.');
        } else {
            return redirect()->to('/prospect/create')->with('success', 'Customer berhasil ditambahkan.');
        }
    }
}
