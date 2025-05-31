<div class="table-responsive">
    <table id="dataTables-nkpm" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày</th>
                <th>Giờ vào</th>
                <th>Giờ ra</th>
                <th>Mục đích sử dụng</th>
                <th>Tình trạng trước khi sử dụng</th>
                <th>Tình trạng sau khi sử dụng</th>
                <th>Giáo viên sử dụng</th>
                @can('hasRole_A_M_L')
                <th>Thao tác</th>
                @endcan
            </tr>
        </thead>
    </table>
</div>
@section('js-nksd')
<!-- Moment.js -->
<script src="{{asset('js/plugins/fullcalendar/moment.min.js')}}"></script>
<script>
    $(document).ready(function() {
        function setData(a, b, c, d) {
            $("#phongSearch").val(a);
            let mays = b;
            console.log("Dữ liệu máy:", mays);
            $("#gvql").text(c);
            if (mays.length > 0) {
                $("#noMap").addClass("hidden");
                $("#hasMap").removeClass("hidden");
                roomMap(mays);
            } else {
                $("#noMap").removeClass("hidden");
                $("#hasMap").addClass("hidden");
            }
            sessionStorage.setItem("mays", JSON.stringify(b));
            sessionStorage.setItem("idphong", d);
            sessionStorage.setItem("tenphong", a);
            sessionStorage.setItem("tengvql", c);
        }

        function roomMap(totalMachines) {
            const container = document.getElementById("hasMap");
            container.innerHTML = '';
            for (let i = 1; i <= totalMachines.length; i++) {
                const pcData = totalMachines[i - 1];
                const pc = document.createElement("button");
                pc.className = "pc status_pc";
                pc.setAttribute("data-pc", JSON.stringify(pcData));
                pc.textContent = ("0" + i).slice(-2);
                // Thêm lớp CSS dựa trên tình trạng máy
                if (pcData.matinhtrang == 5) {
                    pc.classList.add("broken");
                    pc.setAttribute("title", "Không hoạt động");
                }
                container.appendChild(pc);
            }
        }
        const URL = "{{ route('nhatkyphongmay.search-phong') }}";
        // Khởi tạo autocomplete cho trường tìm kiếm phòng máy
        initRoomAutocomplete("#phongSearch", {
            url: URL,
            select: function(event, ui) {
                setData(ui.item.label, ui.item.mays, ui.item.tengvql, ui.item.id);
                return true;
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $("#phongSearch").val("");
                    sessionStorage.removeItem("idphong");
                    sessionStorage.removeItem("mays");
                    sessionStorage.removeItem("tengvql");
                    sessionStorage.removeItem("tenphong");
                    $("#gvql").text("");
                    $("#noMap").removeClass("hidden");
                    $("#hasMap").addClass("hidden");
                }
            }
        });
        initRoomAutocomplete("#phongSearchCreate", {
            url: URL,
            select: function(event, ui) {
                $("#phongSearchCreate").val(ui.item.label);
                $("#idPhongMay").val(ui.item.id);
                return true;
            },
        });
        initRoomAutocomplete("#edit-phong", {
            url: URL,
            select: function(event, ui) {
                $("#edit_phong").val(ui.item.label);
                return true;
            },
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        let table = $('#dataTables-nkpm').DataTable({
            responsive: false,
            autoWidth: false,
            ajax: {
                url: "{{ route('nhatkyphongmay.nhatkysudung.loadTable') }}",
                data: function(d) {
                    d.phong_id = sessionStorage.getItem("idphong") || null;
                    d.hocky_id = $('#hockySearch').val() || sessionStorage.getItem("idhocky") || '';
                },
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'ngay',
                },
                {
                    data: 'giovao',
                },
                {
                    data: 'giora',
                },
                {
                    data: 'mucdichsd',
                },
                {
                    data: 'tinhtrangtruocsd',
                },
                {
                    data: 'tinhtrangsausd',
                },
                {
                    data: 'giaoviensd',
                },
                {
                    data: null,
                    visible: hasPermission,
                    render: function(data, type, row) {
                        return `
                        <div class="btn-action">
                            <a href="#" class="btn btn-warning btn-xs edit-btn"
                                data-tooltip="Cập nhật"
                                data-id="${row.id}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            @can('isAdmin')
                            <a href="#" class="btn btn-danger btn-xs delete-btn"
                                data-tooltip="Xóa"
                                data-id="${row.id}">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcan
                        </div>`;
                    },
                }
            ],
            columnDefs: [{
                targets: [0, 8],
                orderable: false,
                searchable: false,
                className: 'text-center'
            }],
            order: [],
            buttons: [{
                    extend: 'copyHtml5'
                },
                {
                    extend: 'excel'
                },
                {
                    extend: 'print'
                }
            ],
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" +
                "<'col-md-4 text-center'B>" +
                "<'col-md-4 d-flex justify-content-end'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });

        function restoreState() {
            let phong = sessionStorage.getItem("idphong");
            // let mays = JSON.parse(sessionStorage.getItem('mays'));
            $('#gvql').text(sessionStorage.getItem('tengvql'));
            $("#phongSearch").val(sessionStorage.getItem("tenphong"));
            if (phong) {
                $.ajax({
                    url: "{{ route('nhatkyphongmay.loadMachines') }}",
                    method: "GET",
                    data: {
                        idphong: phong,
                    },
                    success: function(response) {
                        console.log("Dữ liệu loadMachines:", response);
                        let mays = response[0].mays;
                        sessionStorage.setItem("mays", JSON.stringify(mays));
                        if (mays.length > 0) {
                            $("#noMap").addClass("hidden");
                            $("#hasMap").removeClass("hidden");
                            roomMap(mays);
                        } else {
                            $("#noMap").removeClass("hidden");
                            $("#hasMap").addClass("hidden");
                        }
                    }
                });
            }
        }
        // Xử lý sự kiện thay đổi học kỳ
        $('#hockySearch').on('change', function() {
            sessionStorage.setItem("idhocky", $('#hockySearch').val());
            if (table) {
                table.ajax.reload();
                restoreState();
            } else {
                console.error("DataTable chưa được khởi tạo!");
            }
        });
        //Xử lý sự kiện thay đổi phòng máy
        $('#phongSearch').on('change', function() {
            $('#phongSearch').val(sessionStorage.getItem('tenphong'));
            if (table) {
                table.ajax.reload();
                restoreState();
            } else {
                console.error("DataTable chưa được khởi tạo!");
            }
        });
        // Modal trạng thái của PC
        $("#hasMap").on('click', '.status_pc', function(e) {
            e.preventDefault();
            let data = JSON.parse(this.getAttribute('data-pc'));
            $('#edit-tentb').val(data.tentb);
            $('#edit-mota').val(data.mota);
            if (data.matinhtrang == 5)
                $('#edit-tinhtrang option[value=5]').prop('selected', true);
            else
                $('#edit-tinhtrang option[value=1]').prop('selected', true);
            $('#edit-ghichu').val(data.ghichu);
            $('#editStatusForm').attr('action', '{{ route("nhatkyphongmay.nhatkysudung.update-status-pc", ":id") }}'.replace(':id', data.id));
            $('#modalUpdatePCStatus').modal('show');
            restoreState();
        });
        //Modal update
        $('#dataTables-nkpm').on('click', '.edit-btn', function(e) {
            let id = $(this).data('id');
            // Lấy thông tin đơn vị qua AJAX
            $.ajax({
                url: '{{ route("nhatkyphongmay.nhatkysudung.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#edit-phong').val(sessionStorage.getItem('tenphong'));
                    $('input[name="giovao"]').val(response.nhatky.giovao);
                    $('input[name="giora"]').val(response.nhatky.giora);
                    $('textarea[name="mucdichsd"]').val(response.nhatky.mucdichsd);
                    $('textarea[name="tinhtrangtruoc"]').val(response.nhatky.tinhtrangtruoc);
                    $('textarea[name="tinhtrangsau"]').val(response.nhatky.tinhtrangsau);
                    // Cập nhật action của form
                    $('#editForm').attr('action', '{{ route("nhatkyphongmay.nhatkysudung.update", ":id") }}'.replace(':id', id));
                    $('#editPMModal').modal('show');
                    restoreState();

                },
                error: function(xhr) {
                    console.error('Error', 'Đã xảy ra lỗi khi lấy thông tin nhật ký');
                }
            });
        });
        //Modal delete
        $('#dataTables-nkpm').on('click', '.delete-btn', function(e) {
            let id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("nhatkyphongmay.nhatkysudung.destroy", ":id") }}'.replace(':id', id));
            $('#deletePMModal').modal('show');
            restoreState();
        });
        $(document).ready(function() {
            restoreState();
        });
    });
</script>
<script>
    // Khởi tạo ClockPicker cho từng input
    function initClockPicker(inputSelector) {
        const $inputs = $(inputSelector);

        $inputs.each(function() {
            const $input = $(this);
            const $inputGroup = $input.closest('.input-group');
            $input.clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                donetext: 'Xong',
                appendTo: '.modal-body',
                afterShow: function() {
                    const $clockpicker = $('.clockpicker-popover');
                    // Di chuyển popover vào modal-body
                    $inputGroup.append($clockpicker);

                    function updateClockPosition() {
                        const inputPos = $input.position();
                        const inputHeight = $input.outerHeight();

                        $clockpicker.css({
                            position: 'absolute',
                            top: inputPos.top + inputHeight - 20 + 'px',
                            left: inputPos.left - 15 + 'px',
                            width: $input.outerWidth() + 'px',
                            zIndex: 1051 // cao hơn modal để hiển thị
                        });
                    }

                    updateClockPosition();

                    $inputGroup.off('scroll.clockpicker').on('scroll.clockpicker', updateClockPosition);
                    $(window).off('resize.clockpicker').on('resize.clockpicker', updateClockPosition);
                },
                afterHide: function() {
                    $inputGroup.off('scroll.clockpicker');
                    $(window).off('resize.clockpicker');
                }
            });

            // Lắng nghe thay đổi để kiểm tra hợp lệ
            $input.on('change', function() {
                const group = $(this).data('group');
                const $in = $(`.clockpicker-input[data-group="${group}"][data-type="in"]`);
                const $out = $(`.clockpicker-input[data-group="${group}"][data-type="out"]`);
                const $error = $out.closest('.form-group').find('.error-message');

                const timeIn = $in.val();
                const timeOut = $out.val();

                if (timeIn && timeOut) {
                    const minutesIn = convertToMinutes(timeIn);
                    const minutesOut = convertToMinutes(timeOut);

                    if (minutesOut <= minutesIn) {
                        $error.show();
                        $('#saveButton').prop('disabled', true);
                    } else {
                        $error.hide();
                        $('#saveButton').prop('disabled', false);
                    }
                } else {
                    $error.hide();
                    $('#saveButton').prop('disabled', false);
                }
            });
        });
    }

    // Chuyển đổi giờ phút sang tổng phút
    function convertToMinutes(time) {
        const [h, m] = time.split(':').map(Number);
        return h * 60 + m;
    }

    // Khởi tạo khi tài liệu sẵn sàng
    $(document).ready(function() {
        initClockPicker('.clockpicker-input');

        // Khi modal mở, làm mới clockpicker
        $('#addModalNew').on('shown.bs.modal', function() {
            $('.clockpicker-input').clockpicker('remove');
            initClockPicker('.clockpicker-input');
        });
    });
</script>
@endsection