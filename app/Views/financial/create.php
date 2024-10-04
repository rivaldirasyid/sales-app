<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Data Financial untuk <?= esc($prospect['customer_name']) ?></h4>
                <p class="card-description">Lengkapi data financial prospek ini</p>
                <form class="forms-sample" action="<?= base_url('financial/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="prospect_id" value="<?= esc($prospect['prospect_id']) ?>">

                    <div class="form-group">
                        <label for="hpp">HPP (Harga Pokok Penjualan)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="hpp" name="hpp" required
                                placeholder="Masukkan HPP">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="plan_budget_sales">Plan Budget Sales</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="plan_budget_sales" name="plan_budget_sales"
                                required placeholder="Masukkan Plan Budget Sales">
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="margin">Margin (%)</label>
                        <input type="number" class="form-control" id="margin" name="margin" step="0.01" required
                            placeholder="Masukkan Margin dalam Persen">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="<?= base_url('prospect/view/' . $prospect['prospect_id']) ?>"
                        class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>