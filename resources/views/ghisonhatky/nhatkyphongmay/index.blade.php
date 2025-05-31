@extends('layouts.app')
@section('title', 'Nhật ký phòng máy')
@section('content')
<div class="col-lg-12">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active tab-border"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Nhật ký sử dụng</a></li>
            <li class="tab-border"><a data-toggle="tab" href="#tab-2" aria-expanded="false">Danh sách thiết bị</a></li>
            @canany('hasRole_A_M_L')
            <li class="tab-border"><a data-toggle="tab" href="#tab-3" aria-expanded="false">Bảo trì sửa chữa</a></li>
            @endcan
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 text-center" style="margin-bottom: 20px; padding: 0 15px">
                            <div style="background: #f8f9fa; border: 1px solid #ccc; padding: 25px; border-radius: 10px;">
                                <h4 class="text-center" style="color: #dc3545; font-weight: bold;">SƠ ĐỒ PHÒNG MÁY</h4>
                                <div class="room-map">
                                    <p id="noMap" class="hidden text-center" style="font-size: 18px;">Chưa có sơ đồ phòng máy!</p>
                                    <div id="hasMap" class="machine-grid"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div style="padding: 20px 8px 20px 20px;">
                                <p class="text-right" style="margin-top: -18px; color: black;">
                                    GIÁO VIÊN QUẢN LÝ: <strong id="gvql" style="color: red;"></strong>
                                </p>

                                <div class="form-group">
                                    <label for="hockySearch">HỌC KỲ</label>
                                    <select id="hockySearch" name="idhocky" class="form-control">
                                        @foreach($hockys as $hk)
                                        <option value="{{ $hk->id }}" @if($hk==$hockysCurrent) selected @endif>
                                            Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                            @if($hk == $hockysCurrent) - Học kỳ hiện tại @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group autocomplete">
                                    <label for="phongSearch">PHÒNG MÁY</label>
                                    <input id="phongSearch" required type="text" class="form-control" name="phongSearch"
                                        placeholder="Nhập tên phòng (VD: A201)">
                                </div>
                                @can('hasRole_A_M_L')
                                <div class="row" style="margin-top: 15px;">
                                    <!-- <div class="col-xs-12 col-sm-4">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModalOld">
                                            <i class="fa fa-calendar"></i> Thêm lịch cũ
                                        </a>
                                    </div> -->
                                    <div class="col-xs-12 col-sm-4" style="margin-bottom: 5px;">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModalNew">
                                            <i class="fa fa-calendar-plus-o" title="Thêm lịch sử dụng"></i><span class="hidden-md"> Thêm lịch sử dụng</span>
                                        </a>
                                    </div>
                                    <div class="col-xs-12 col-sm-4" style="margin-bottom: 5px;">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalPrint">
                                            <i class="fa fa-print" title="In sổ nhật ký"></i><span class="hidden-md"> In sổ nhật ký</span>
                                        </a>
                                    </div>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content" style="padding: 15px 0;">
                        <!-- Bảng nhật ký -->
                        @include('ghisonhatky.nhatkyphongmay.nhatkysudung.table')
                    </div>
                </div>
                @include('ghisonhatky.nhatkyphongmay.nhatkysudung.partials.modals')
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <div class="ibox float-e-margins">
                            <div class="ibox-tools" style="padding: 0 15px; display: flex; justify-content: space-between; align-items: center;">
                                <h2 class="h2-title">Danh sách thiết bị</h2>
                                <div class="btn-action">
                                    <button class="btn btn-primary btn-filter">
                                        <i class="fa fa-filter" title="Lọc dữ liệu"></i><span class="hidden-sm hidden-xs"> Lọc dữ liệu</span>
                                    </button>
                                    <button class="btn btn-info btn-reset">
                                        <i class="fa fa-refresh" title="Làm mới bộ lọc"></i><span class="hidden-sm hidden-xs"> Làm mới bộ lọc</span>
                                    </button>
                                </div>
                            </div>
                            <div class="ibox-content">
                                @include('ghisonhatky.nhatkyphongmay.danhsachthietbi.table')
                            </div>
                        </div>
                    </div>
                </div>
                @include('ghisonhatky.nhatkyphongmay.danhsachthietbi.partials.modals')
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    <div class="row">
                        <h2 style="padding-left:15px; margin-top:0;margin-bottom:20px;" class="h2-title">Nhật ký bảo trì</h2>
                        <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                            <label for="hockySearch1" class="control-label">HỌC KỲ</label>
                            <select id="hockySearch1" name="idhocky1" class="form-control">
                                @foreach($hockys as $hk)
                                <option value="{{ $hk->id }}" @if($hk==$hockysCurrent) selected @endif>
                                    Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                    @if($hk == $hockysCurrent) - Học kỳ hiện tại @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12" style="margin-bottom: 20px;">
                            <div class="form-group autocomplete">
                                <label for="phongSearch1" class="control-label">PHÒNG MÁY</label>
                                <input id="phongSearch1" required type="text" class="form-control" name="phongSearch1" placeholder="Nhập tên phòng (VD: A201)">
                                <div class="row" style="margin-top: 15px;">
                                    <div class="col-xs-12 col-sm-4" style="margin-bottom: 5px;">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addBTSCModal">
                                            <i class="fa fa-calendar-plus-o" title="Thêm lịch bảo trì"></i><span class="hidden-md hidden-sm"> Thêm lịch bảo trì</span>
                                        </a>
                                    </div>
                                    <div class="col-xs-12 col-sm-4" style="margin-bottom: 5px;">
                                        <a class="btn btn-primary btn-block" id="btn-printlichbaotri">
                                            <i class="fa fa-print" title="In sổ bảo trì"></i><span class="hidden-md hidden-sm"> In sổ bảo trì</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!-- Bảng nhật ký -->
                        @include('ghisonhatky.nhatkyphongmay.baotrisuachua.table')
                    </div>
                </div>
                @include('ghisonhatky.nhatkyphongmay.baotrisuachua.partials.modals')
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/component/autocomplete-phong.js') }}"></script>
<script>
    $(document).ready(function() {
        // Khi nhấn vào tab, lưu tab hiện tại vào localStorage
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });

        // Khi trang load lại, kiểm tra có tab nào được lưu không, thì kích hoạt nó
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>
@yield('js-nksd')
@yield('js-dstb')
@yield('js-btsc')
@endsection