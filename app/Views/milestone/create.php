<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Milestone: <?= esc($milestone_name) ?></h4>
                <p class="card-description">Tambahkan milestone baru untuk prospek ini</p>
                <form class="forms-sample" action="<?= base_url('milestone/store') ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="prospect_id" value="<?= esc($prospect_id) ?>">
                    <input type="hidden" name="milestone_name" value="<?= esc($milestone_name) ?>">
                    <input type="hidden" name="origin" value="<?= esc($origin ?? 'prospect') ?>">
                    <!-- Tambahkan asal halaman -->
                    <div class="form-group">
                        <label for="progress_percentage">Progress Percentage</label>
                        <input type="number" class="form-control" id="progress_percentage" name="progress_percentage"
                            min="0" max="100" value="0" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" placeholder="Enter notes here"
                            rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="milestone_document">Upload Document (optional)</label>
                        <input type="file" class="form-control" id="milestone_document" name="milestone_document"
                            accept=".pdf,.doc,.docx">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="<?= base_url('prospect/view/' . $prospect_id) ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>