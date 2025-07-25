</div>
<!-- /.content-wrapper -->


<footer class="main-footer">
  <strong>Copyright &copy; 2025 <a href="#">Arturo Saravia</a>.</strong>
  Todos los derechos reservados.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 1.0.0
  </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="<?= plugins() ?>/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= plugins() ?>/jquery-ui/jquery-ui.min.js"></script>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>





<!-- Bootstrap 4 -->
<script src="<?= plugins() ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= plugins() ?>/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= plugins() ?>/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= plugins() ?>/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= plugins() ?>/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= plugins() ?>/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= plugins() ?>/moment/moment.min.js"></script>
<script src="<?= plugins() ?>/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= plugins() ?>/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= plugins() ?>/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= plugins() ?>/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>


<!-- SweetAlert2 -->
<script src="<?= plugins() ?>/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?= plugins() ?>/toastr/toastr.min.js"></script>


<!-- DataTables  & Plugins -->
<script src="<?= plugins() ?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?= plugins() ?>/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= plugins() ?>/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= plugins() ?>/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= plugins() ?>/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= plugins() ?>/datatables-buttons/js/buttons.bootstrap4.min.js"></script>


<script src="<?= plugins() ?>/datatables-select/js/dataTables.select.min.js"></script>
<script src="<?= plugins() ?>/datatables-select/js/select.bootstrap4.min.js"></script>



<script src="<?= plugins() ?>/jszip/jszip.min.js"></script>
<script src="<?= plugins() ?>/pdfmake/pdfmake.min.js"></script>
<script src="<?= plugins() ?>/pdfmake/vfs_fonts.js"></script>
<script src="<?= plugins() ?>/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= plugins() ?>/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= plugins() ?>/datatables-buttons/js/buttons.colVis.min.js"></script>


<!-- AdminLTE App -->
<script src="<?= dist() ?>/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= dist() ?>/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= dist() ?>/js/pages/dashboard.js"></script>



<script>
  const base_url = "<?= base_url() ?>";
</script>

<script src="<?= assets() ?>/js/utils.js"></script>
<script src="<?= assets() ?>/js/main.js"></script>
<script src="<?= assets() ?>/js/views/<?= $data['view_js'] ?>.js"></script>
</body>

</html>