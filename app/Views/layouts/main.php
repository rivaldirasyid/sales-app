<!DOCTYPE html>
<html lang="en">
<?= $this->include('partials/_header') ?>

<body>
  <div class="container-scroller">
    <?= $this->include('partials/_navbar') ?>
    <div class="container-fluid page-body-wrapper">
      <?= $this->include('partials/_sidebar') ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <?= $this->renderSection('content') ?>
        </div>
        <?= $this->include('partials/_footer') ?>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="<?= base_url('template/vendors/js/vendor.bundle.base.js') ?>"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="<?= base_url('template/vendors/chart.js/Chart.min.js') ?>"></script>
  <script src="<?= base_url('template/vendors/datatables.net/jquery.dataTables.js') ?>"></script>
  <script src="<?= base_url('template/vendors/datatables.net-bs4/dataTables.bootstrap4.js') ?>"></script>
  <script src="<?= base_url('template/js/dataTables.select.min.js') ?>"></script>

  <script src="<?= base_url('template/vendors/typeahead.js/typeahead.bundle.min.js') ?>"></script>
  <script src="<?= base_url('template/vendors/select2/select2.min.js') ?>"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?= base_url('template/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('template/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('template/js/template.js') ?>"></script>
  <script src="<?= base_url('template/js/settings.js') ?>"></script>
  <script src="<?= base_url('template/js/todolist.js') ?>"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="<?= base_url('template/js/dashboard.js') ?>"></script>
  <script src="<?= base_url('template/js/Chart.roundedBarCharts.js') ?>"></script>
  <script src="<?= base_url('template/js/chart.js') ?>"></script>

  <script src="<?= base_url('template/js/file-upload.js') ?>"></script>
  <script src="<?= base_url('template/js/typeahead.js') ?>"></script>
  <script src="<?= base_url('template/js/select2.js') ?>"></script>
  <!-- End custom js for this page-->
</body>

</html>