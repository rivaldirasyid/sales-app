<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Milestone: <?= esc($milestone['milestone_name']) ?></h4>
                <p class="card-description">Ubah persentase progres dari milestone ini</p>
                <form class="forms-sample"
                    action="<?= base_url('milestone/updateProgress/' . $milestone['milestone_id']) ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="origin" value="<?= esc($origin ?? 'milestone') ?>">
                    <!-- Kirim origin ke form -->

                    <div class="form-group">
                        <label for="progress_percentage">Progress Percentage</label>
                        <input type="number" class="form-control" id="progress_percentage" name="progress_percentage"
                            value="<?= esc($milestone['progress_percentage']) ?>" min="0" max="100" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4"
                            placeholder="Enter notes here"><?= esc($milestone['notes']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="milestone_document">Update Document (optional)</label>
                        <input type="file" class="form-control" id="milestone_document" name="milestone_document"
                            accept=".pdf,.doc,.docx">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="<?= base_url('milestone/view/' . $milestone['prospect_id']) ?>"
                        class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>