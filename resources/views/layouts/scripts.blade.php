<!-- JQuery -->
<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<!-- Jquery UI -->
<script src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- Metis Menu -->
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- Custom and plugin javascript -->
<script src="{{ asset('js/inspinia.js') }}"></script>
<!-- Loading progress bar -->
<script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('js/plugins/toastr/toastr.min.js') }}"></script>
<!-- Data Tables -->
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<!-- Clock Picker -->
<script src="{{ asset('js/plugins/clockpicker/clockpicker.js') }}"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Dropzone -->
<!-- <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script> -->
<!-- Select2 -->
<script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- Page-Level Scripts -->
@yield('js')