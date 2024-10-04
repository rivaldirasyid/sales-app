<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Document for Milestone: <?= esc($milestone['milestone_name']) ?></h4>
                <p class="card-description">Please upload the document for this milestone.</p>

                <!-- Form untuk upload dokumen -->
                <form class="forms-sample"
                    action="<?= base_url('milestone/updateDocument/' . $milestone['milestone_id']) ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="milestone_document">Upload Document</label>
                        <input type="file" class="form-control" id="milestone_document" name="milestone_document"
                            accept=".pdf,.doc,.docx" required>
                        <!-- Feedback error jika file tidak diupload atau error lain -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger mt-2"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="<?= base_url('milestone/view/' . $milestone['prospect_id']) ?>"
                        class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>