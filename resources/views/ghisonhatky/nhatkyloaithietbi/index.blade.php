@extends('layouts.app')
@section('title', 'Nhật ký từng loại thiết bị')
@section('content')
<div class="header">
    <h2 style="padding-left: 15px;">Nhật ký từng loại thiết bị</h2>
</div>
<div class="col-lg-12">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Ghi sổ nhật ký</a></li>
            <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Nhật ký bảo trì, sửa chữa</a></li>
            <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false">Năm sử dụng</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div
                                style="background: #f8f9fa; border: 1px solid #ccc; padding: 25px; border-radius: 10px;">
                                <!-- Nút chức năng nhỏ, căn trái -->
                                <div class="row">
                                    <div class="col-xs-6 col-sm-2">
                                        <a class="btn btn-sm btn-primary btn-block" data-toggle="modal"
                                            data-target="#addModalNew">
                                            <i class="fa fa-calendar-plus-o" style="margin-right: 5px;"></i> Thêm lịch
                                        </a>
                                    </div>
                                    <div class="col-xs-6 col-sm-2">
                                        <a class="btn btn-sm btn-primary btn-block" data-toggle="modal"
                                            data-target="#modalPrint">
                                            <i class="fa fa-print" style="margin-right: 5px;"></i> In sổ
                                        </a>
                                    </div>
                                </div>

                                <!-- Học kỳ & Phòng máy -->
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="hockySearch">NĂM SỬ DỤNG</label>
                                            <select id="hockySearch" name="idhocky" class="form-control">
                                                @foreach($hockys->reverse() as $hk)
                                                <option value="{{ $hk->id }}">
                                                    {{ $hk->namsd }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group autocomplete">
                                            <label for="phongSearch">PHÒNG MÁY</label>
                                            <input id="phongSearch" required type="text" class="form-control"
                                                name="phongSearch" placeholder="Nhập tên phòng (VD: A201)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng nhật ký -->
                    @include('ghisonhatky.nhatkyloaithietbi.partials.table')
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                </div>
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    <strong>dsad</strong>
                    <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the
                        stalks, and grow familiar with the countless indescribable forms of the insects
                        and flies, then I feel the presence of the Almighty, who formed us in his own image, and the
                        breath </p>

                    <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of
                        souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                        sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a
                        single stroke at the present moment; and yet.</p>
                </div>
            </div>
        </div>
        @include('ghisonhatky.nhatkyloaithietbi.partials.modals')
    </div>
</div>
@endsection