<?php

namespace App\Controllers;

use App\Models\MilestoneProspectModel;

class Milestone extends BaseController
{
    protected $milestoneProspectModel;

    public function __construct()
    {
        $this->milestoneProspectModel = new MilestoneProspectModel();
    }

    // Menampilkan semua milestone berdasarkan user atau admin
    public function index()
    {
        $session = session();
        $user_id = $session->get('user_id');
        $role = $session->get('role');

        $search = $this->request->getVar('search');
        $limit = $this->request->getVar('limit') ?? 10; // Default 10 entri
        $page = $this->request->getVar('page') ?? 1;

        // Ambil data dengan pagination dan pencarian dari model
        $query = $this->milestoneProspectModel->getMilestonesWithPaginationAndSearch($user_id, $role, $search);

        // Sebelum melakukan paginate, hitung total
        $total = $query->countAllResults(false); // Gunakan false agar tidak mengosongkan builder

        // Ambil data dengan pagination
        $milestones = $query->paginate($limit, 'milestones');

        // Ambil pager dari model, bukan dari query
        $pager = $this->milestoneProspectModel->pager;

        // Kirim data ke view
        $data = [
            'milestones' => $milestones,
            'pager' => $pager,
            'limit' => $limit,
            'search' => $search,
            'total' => $total
        ];

        return view('milestone/index', $data);
    }

    // Menampilkan detail milestone untuk prospect tertentu
    public function view($prospect_id)
    {
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

        $milestones = $this->milestoneProspectModel->getMilestonesByProspect($prospect_id);
        $progress = $this->milestoneProspectModel->calculateProgress($prospect_id);
        $nextMilestone = $this->milestoneProspectModel->getNextMilestone($prospect_id, $milestoneOrder);

        $previousMilestoneCompleted = true;
        foreach ($milestones as $milestone) {
            if ($milestone['progress_percentage'] < 100) {
                $previousMilestoneCompleted = false;
                break;
            }
        }

        $data = [
            'milestones' => $milestones,
            'progress' => $progress,
            'nextMilestone' => $nextMilestone,
            'prospect_id' => $prospect_id,
            'milestoneOrder' => $milestoneOrder,
            'previousMilestoneCompleted' => $previousMilestoneCompleted
        ];

        return view('milestone/view', $data);
    }

    // Menampilkan form untuk menambah milestone baru
    public function create($prospect_id, $milestone_name)
    {
        $origin = $this->request->getGet('origin'); // Ambil origin dari URL query string

        $data = [
            'prospect_id' => $prospect_id,
            'milestone_name' => urldecode($milestone_name),
            'origin' => $origin // Kirim origin ke view
        ];

        return view('milestone/create', $data);
    }

    // Logika penyimpanan milestone baru
    public function store()
    {
        $prospect_id = $this->request->getPost('prospect_id');
        $milestone_name = $this->request->getPost('milestone_name');
        $origin = $this->request->getPost('origin');
        $progress_percentage = $this->request->getPost('progress_percentage');

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

        $file = $this->request->getFile('milestone_document');
        $filePath = null;

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $filePath = 'uploads/documents/' . $newName;
            if (!$file->move('uploads/documents/', $newName)) {
                return redirect()->back()->with('error', 'Document upload failed. Please try again.');
            }
        }

        $nextMilestone = $this->milestoneProspectModel->getNextMilestone($prospect_id, $milestoneOrder);

        if ($nextMilestone && $milestone_name === $nextMilestone) {
            $lastCompletedMilestone = $this->milestoneProspectModel->getLastCompletedMilestone($prospect_id);
            $milestone_index = $lastCompletedMilestone ? $lastCompletedMilestone['milestone_index'] + 1 : 1;

            $this->milestoneProspectModel->save([
                'prospect_id' => $prospect_id,
                'milestone_name' => $milestone_name,
                'milestone_status' => $progress_percentage == 100 ? 'Completed' : 'In progress',
                'milestone_date' => date('Y-m-d'),
                'notes' => $this->request->getPost('notes'),
                'milestone_index' => $milestone_index,
                'progress_percentage' => $progress_percentage,
                'milestone_document' => $filePath
            ]);

            // Jika milestone terakhir sudah selesai, tandai conversion
            if ($milestone_name === 'Contract Signed' && $progress_percentage == 100) {
                $prospectModel = new \App\Models\ProspectModel();
                $prospectModel->update($prospect_id, [
                    'conversion' => 1,
                    'conversion_date' => date('Y-m-d'),
                    'prospect_status' => 'CLOSED'
                ]);
            }

            return redirect()->to($origin === 'milestone' ? '/milestone/view/' . $prospect_id : '/prospect/view/' . $prospect_id)
                ->with('success', 'Milestone added successfully.');
        } else {
            return redirect()->back()->with('error', 'Milestone is not in correct order or has already been completed.');
        }
    }

    // Menampilkan form edit milestone
    public function edit($milestone_id)
    {
        $milestone = $this->milestoneProspectModel->find($milestone_id);
        $origin = $this->request->getGet('origin');

        if ($milestone) {
            $data = [
                'milestone' => $milestone,
                'origin' => $origin
            ];

            return view('milestone/edit', $data);
        } else {
            return redirect()->back()->with('error', 'Milestone not found.');
        }
    }

    // Update progress milestone dan dokumen (jika ada)
    public function updateProgress($milestone_id)
    {
        $progress_percentage = $this->request->getPost('progress_percentage');
        $origin = $this->request->getPost('origin');

        $file = $this->request->getFile('milestone_document');
        $filePath = null;

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $filePath = 'uploads/documents/' . $newName;
            if (!$file->move('uploads/documents/', $newName)) {
                return redirect()->back()->with('error', 'Document upload failed. Please try again.');
            }
        }

        $milestone = $this->milestoneProspectModel->find($milestone_id);

        if ($milestone) {
            $milestone_status = $progress_percentage == 100 ? 'Completed' : 'In progress';

            $updateData = [
                'progress_percentage' => $progress_percentage,
                'milestone_status' => $milestone_status
            ];

            if ($filePath) {
                $updateData['milestone_document'] = $filePath;

                if ($milestone['milestone_document'] && file_exists($milestone['milestone_document'])) {
                    unlink($milestone['milestone_document']);
                }
            }

            $this->milestoneProspectModel->update($milestone_id, $updateData);

            // Jika milestone terakhir sudah selesai, tandai conversion
            if ($milestone['milestone_name'] === 'Contract Signed' && $progress_percentage == 100) {
                $prospectModel = new \App\Models\ProspectModel();
                $prospectModel->update($milestone['prospect_id'], [
                    'conversion' => 1,
                    'conversion_date' => date('Y-m-d')
                ]);
            }

            return redirect()->to($origin === 'milestone' ? '/milestone/view/' . $milestone['prospect_id'] : '/prospect/view/' . $milestone['prospect_id'])
                ->with('success', 'Milestone progress updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Milestone not found.');
        }
    }

    // Menampilkan form untuk menambah dokumen ke milestone yang sudah ada
    public function addDocument($milestone_id)
    {
        $milestone = $this->milestoneProspectModel->find($milestone_id);
        if (!$milestone) {
            return redirect()->back()->with('error', 'Milestone not found.');
        }

        return view('milestone/add_document', ['milestone' => $milestone]);
    }

    // Update dokumen milestone yang sudah ada
    public function updateDocument($milestone_id)
    {
        $file = $this->request->getFile('milestone_document');
        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $filePath = 'uploads/documents/' . $newName;

            if ($file->move('uploads/documents/', $newName)) {
                $this->milestoneProspectModel->update($milestone_id, [
                    'milestone_document' => $filePath
                ]);
                return redirect()->to('/milestone/view/' . $this->milestoneProspectModel->find($milestone_id)['prospect_id'])->with('success', 'Document uploaded successfully.');
            }
        }

        return redirect()->back()->with('error', 'Document upload failed. Please try again.');
    }
}
