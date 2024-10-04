<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Pre-Sales</h4>
                <form action="<?= base_url('pre-sales/update/' . $preSales['pre_sales_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= esc($preSales['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="division_id">Division</label>
                        <select class="form-control" id="division_id" name="division_id" required>
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?= $division['division_id'] ?>"
                                    <?= $division['division_id'] == $preSales['division_id'] ? 'selected' : '' ?>>
                                    <?= $division['division_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Manager" <?= $preSales['status'] == 'Manager' ? 'selected' : '' ?>>Manager
                            </option>
                            <option value="Chief" <?= $preSales['status'] == 'Chief' ? 'selected' : '' ?>>Chief</option>
                            <option value="Staff" <?= $preSales['status'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                            <option value="Other" <?= $preSales['status'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('pre-sales') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>