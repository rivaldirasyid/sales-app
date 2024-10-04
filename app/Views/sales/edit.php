<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Sales</h4>
                <form action="<?= base_url('sales/update/' . $sale['user_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="<?= esc($sale['full_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= esc($sale['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= esc($sale['username']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (Fill if you want to change)</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="Sales" <?= $sale['role'] == 'Sales' ? 'selected' : '' ?>>Sales</option>
                            <option value="Admin" <?= $sale['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="AM" <?= $sale['role'] == 'AM' ? 'selected' : '' ?>>AM</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('sales') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>