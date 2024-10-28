<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title; ?> | Aplikasi Pengadaan Barang</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/fonts.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Datepicker -->
    <link href="<?= base_url(); ?>assets/vendor/daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/datatables/buttons/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/datatables/responsive/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendor/gijgo/css/gijgo.min.css" rel="stylesheet">

    <style>
        #accordionSidebar,
        .topbar {
            z-index: 1;
        }

        select[readonly] {
            pointer-events: none;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-dark bg-primary topbar mb-4 static-top shadow-sm">

                    
                    <h2 class="font-weight-bold text-white"><i class="fas fa-box"></i> Stok Jireh GS</h2>

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link bg-transparent d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars text-white"></i>
                    </button>


                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <?= $contents; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-light">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Aplikasi Monitor Stok. JIREH GS made by Bryan Charity</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin logout?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Klik "Logout" dibawah ini jika anda yakin ingin logout.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batalkan</button>
                    <a class="btn btn-primary" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url(); ?>assets/js/sb-admin-2.min.js"></script>

    <!-- Datepicker -->
    <script src="<?= base_url(); ?>assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/daterangepicker/daterangepicker.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/buttons/js/buttons.colVis.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/responsive/js/responsive.bootstrap4.min.js"></script>

    <script src="<?= base_url(); ?>assets/vendor/gijgo/js/gijgo.min.js"></script>



    <script>
        $(document).ready(function(){ 
            // hide
            $(document).on("click", ".stok_terkini_laporan", function() {
                $('.action-date').hide(); 

            });

            // unhide
            $(document).on("click", ".barang_masuk_laporan", function() {
                $('.action-date').show(); 

            });
            $(document).on("click", ".retur_laporan", function() {
                $('.action-date').show(); 

            });
            $(document).on("click", ".mutasi_barang_laporan", function() {
                $('.action-date').show(); 

            });
        });

        // Modal Data Atribut Pembayaran 
        $(document).on('click', '.data-bj', function(e) {
            e.preventDefault();
            $("#modal_show_bj").modal('show');
            $.post('<?php echo base_url('barangmasuk/show_barangmasuk'); ?>', {
                    id: $(this).attr('data-id'),
                },
                function(html) {

                    $(".body-detail").html(html);
                }
            );
        });

        $(document).on('click', '.data-bk', function(e) {
            e.preventDefault();
            $("#modal_show_bj").modal('show');
            $.post('<?php echo base_url('barangkeluar/show_barangkeluar'); ?>', {
                    id: $(this).attr('data-id'),
                },
                function(html) {

                    $(".body-detail").html(html);
                }
            );
        });
        $(document).on('click', '.data-rt', function(e) {
            e.preventDefault();
            $("#modal_show_bj").modal('show');
            $.post('<?php echo base_url('retur/show_detailretur'); ?>', {
                    id: $(this).attr('data-id'),
                },
                function(html) {

                    $(".body-detail").html(html);
                }
            );
        });
        $(document).on('click', '.data-transfer', function(e) {
            e.preventDefault();
            $("#modal_show_bj").modal('show');
            $.post('<?php echo base_url('transferbarang/show_detailtransfer'); ?>', {
                    id: $(this).attr('data-id'),
                },
                function(html) {

                    $(".body-detail").html(html);
                }
            );
        });

        $(document).on('click', '.close_modal', function(e) {
            $("#modal_show_bj").modal("hide");

        });
    </script>

    <script type="text/javascript">
        $(function() {
            $('.date').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#tangal').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            }

            $('#tanggal').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 hari terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
                    'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                }
            }, cb);

            cb(start, end);
        });

        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                buttons: [{
                    extend: 'copy',
                    // exportOptions: {
                    //     columns: 'th:not(:last-child)'
                    // }
                }, {
                    extend: 'csv',
                    // exportOptions: {
                    //     columns: 'th:not(:last-child)'
                    // }
                }, {
                    extend: 'print',
                    // exportOptions: {
                    //     columns: 'th:not(:last-child)'
                    // }
                }, {
                    extend: 'excel',
                    // exportOptions: {
                    //     columns: 'th:not(:last-child)'
                    // }
                }, {
                    extend: 'pdf',
                    // exportOptions: {
                    //     columns: 'th:not(:last-child)'
                    // }
                }],
                dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'B><'col-md-4'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }]
            });

            table.buttons().container().appendTo('#dataTable_wrapper .col-md-5:eq(0)');
        });
    </script>
    <script type="text/javascript">
        let hal = '<?= $this->uri->segment(1); ?>';

        let satuan = $('#satuan');
        let stok = $('#stok');
        let total = $('#total_stok');
        let jumlah = hal == 'barangmasuk' ? $('#jumlah_masuk') : $('#jumlah_keluar');

        $(document).on('keyup', '#jumlah_masuk', function() {
            let totalStok = parseInt(stok.val()) + parseInt(this.value);
            total.val(Number(totalStok));
        });

        $(document).on('keyup', '#jumlah_keluar', function() {
            let totalStok = parseInt(stok.val()) - parseInt(this.value);
            total.val(Number(totalStok));
        });
    </script>

    <!-- Transfer Barang -->
    <script>
        if ($('#jumlah_edit').val() == undefined) {
            var i = 0;

        } else {
            var i = parseInt($('#jumlah_edit').val());
            var jenis = 'edit';
        }

        function tambah_barang(jenis = '') {

            if (jenis == 'tambah' || jenis == 'kurang') {
                let data = '<tr id="data' + (i + 1) + '">' +
                    '<td>' +
                    "<select name='id_barang[]' id='id_barang" + (i + 1) + "' class='form-control selectpicker' data-live-search='true' required onchange='total_masukkeluar(" + (i + 1) + ")'>" +
                    "<option value='' selected>-- Pilih Nama Barang --</option>" +
                    "<?= $barang_opt ?>" +
                    "</select>" +
                    '</td>' +
                    '<td>' +
                    "<input type='text' name='stok_asal[]' readonly class='form-control stok" + (i + 1) + " jumlah' value='0' required onblur='total_masukkeluar(" + (i + 1) + ")'>" +
                    '</td>' +
                    '<td>' +
                    '<div class="input-group">' +
                    "<input type='number' name='jumlah[]' required class='form-control jumlah" + (i + 1) + " jumlah' value='0' required onblur='total_masukkeluar(" + (i + 1) + ",1,\"" + jenis + "\")'>" +
                    '<div class="input-group-append">' +
                    '<span class="input-group-text satuan' + (i + 1) + '">Satuan</span>' +
                    '</div></div>' +
                    '</td>' +
                    '<td>' +
                    "<input type='text' name='stok_tujuan[]' readonly class='form-control total_stok" + (i + 1) + " jumlah' value='0' required onblur='total_masukkeluar(" + (i + 1) + ")'>" +
                    '</td>' +
                    '<td><button type="button" onclick="hapus_barang(' + (i + 1) + ')" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button></td>'
                '<td></td>' +
                '</tr>'
                $('#detail_transfer').append(data);
            } else {
                let data = '<tr id="data' + (i + 1) + '">' +
                    '<td>' +
                    "<select name='id_barang[]' id='id_barang" + (i + 1) + "' class='form-control selectpicker' data-live-search='true' required>" +
                    "<option value='' selected>-- Pilih Nama Barang --</option>" +
                    "<?= $barang_opt ?>" +
                    "</select>" +
                    '</td>' +
                    '<td>' +
                    "<input type='text' name='jumlah[]' required class='form-control jumlah" + (i + 1) + " jumlah' value='0' required>" +

                    '</td>' +
                    '<td><button type="button" onclick="hapus_barang(' + (i + 1) + ')" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button></td>'
                '<td></td>' +
                '</tr>'
                $('#detail_transfer').append(data);
            }


            i++;
        }

        function total_barang(id, opt = '') {

            let satuan = $('.satuan' + id).val;
            let stok = $('.stok' + id);
            let total = $('.total_stok' + id); //stok tujuan
            let id_barang = $('#id_barang' + id);

            let input = $('.jumlah' + id);

            input = input.val()


            // let url = '<?= base_url('barang/getstoktransfer/'); ?>' + id_barang.val() + '/' + $('#asal_cabang').val() + '/' + $('#tujuan_cabang').val();
            let url = '<?= base_url('barang/getStokDataBarang/'); ?>' + id_barang.val();
            $.getJSON(url, function(data) {

                if (opt == '1') { // ketika di input jumlah
                    // jml_stok = data.stok_awal;
                    // jml_tujuan = data.stok_tujuan;

                    jml_stok = data.stok;
                    jml_tujuan = 0;

                    if ($('#jumlah_transedit' + id).val() == undefined) {

                        // total.val(parseInt(jml_tujuan) + parseInt(input)); //stok tujuan
                        total.val(parseInt(jml_stok) - parseInt(input));
                        // stok.val(parseInt(jml_stok) + parseInt(input));
                        stok.val(parseInt(jml_stok));
                    } else {
                        var jml = parseInt($('#jumlah_transedit' + id).val());
                        // total.val(parseInt(jml_tujuan) - jml + parseInt(input)); //stok tujuan
                        // stok.val(parseInt(jml_stok) + jml - parseInt(input));
                        stok.val(parseInt(jml));
                        total.val(parseInt(jml) - parseInt(input));

                        // total.val(parseInt(jml_stok) + parseInt(input));
                    }

                } else {
                    // stok.val(data.stok_awal);
                    // total.val(data.stok_tujuan);
                    stok.val(data.stok);
                    total.val(0);
                }
            });

        }

        function total_masukkeluar(id, opt = '', jenis = '') {
            let satuan = $('.satuan' + id);
            let stok = $('.stok' + id);
            let total = $('.total_stok' + id); //stok tujuan
            let id_barang = $('#id_barang' + id);

            let input = $('.jumlah' + id);


            input = input.val()

            let url = '<?= base_url('barang/getStokDataBarang/'); ?>' + id_barang.val();
            $.getJSON(url, function(data) {

                if (opt == '1') { // ketika di input jumlah
                    stok.val(data.stok);
                    satuan.html(data.nama_satuan);
                    jml_stok = data.stok;
                    if (jenis == 'tambah') {
                        total.val(parseInt(jml_stok) + parseInt(input)); //stok tujuan
                    } else {
                        total.val(parseInt(jml_stok) - parseInt(input)); //stok tujuan
                    }


                } else {
                    stok.val(data.stok);
                    satuan.html(data.nama_satuan);
                }
            });
        }

        function total_transfer(id) {

            let stok = $('.stok' + id);
            let input = $('.jumlah' + id);
            let total = $('.total_stok' + id);
            let id_barang = $('#id_barang' + id);

            let url = '<?= base_url('barang/getstoktransfer/'); ?>' + id_barang.val() + '/' + $('#asal_cabang').val() + '/' + $('#tujuan_cabang').val();
            $.getJSON(url, function(data) {

            });
        }

        function hapus_barang(id) {
            $('#data' + id).remove();
        }
    </script>
    <!-- End Transfer Barang -->
</body>

</html>