<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Division</h4>
                <form action="<?= base_url('division/update/' . $division['division_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="division_name">Division Name</label>
                        <input type="text" class="form-control" id="division_name" name="division_name"
                            placeholder="Division Name" value="<?= esc($division['division_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="division_leader">Division Leader</label>
                        <input type="text" class="form-control" id="division_leader" name="division_leader"
                            placeholder="Division Leader" value="<?= esc($division['division_leader']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('division') ?>" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>