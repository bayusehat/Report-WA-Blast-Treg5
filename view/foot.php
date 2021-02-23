<footer class="footer">
                © 20201 CCare Treg 5 <span class="d-none d-sm-inline-block"> - Crafted with <i class="mdi mdi-heart text-danger"></i> by IT Team CC</span>.
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- jQuery  -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/metismenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/waves.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>


    <!-- Jquery-Ui -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>

    <script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="plugins/moment/moment.js"></script>
    <script src='plugins/fullcalendar/js/fullcalendar.min.js'></script>
    <script src="assets/pages/calendar-init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
				var dataTable = $('#dataku').DataTable( {
					"processing": true,
					"serverSide": true,
                    "paging": true,
					"ajax":{
						url :"data_upload.php",
						type: "post",
						error: function(){
							$(".dataku-error").html("");
							$("#dataku").append('<tbody class="dataku-error"><tr><th colspan="3">Tidak ada data untuk ditampilkan</th></tr></tbody>');
							$("#dataku-error-proses").css("display","none");
							
						}
					}
				} );
			} );
    </script>

</body>

</html>