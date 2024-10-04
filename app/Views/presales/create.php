<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Pre-Sales</h4>
                <form action="<?= base_url('pre-sales/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Pre-Sales Name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="division_id">Division</label>
                        <select class="form-control" id="division_id" name="division_id" required>
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= $division['division_id'] ?>"><?= $division['division_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Manager">Manager</option>
                            <option value="Chief">Chief</option>
                            <option value="Staff">Staff</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="<?= base_url('pre-sales') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>