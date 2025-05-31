<ul class="nav metismenu" id="side-menu">
    <li class="nav-header">
        <div class="dropdown profile-element">
            <span>
                <img alt="image" class="img-circle" src="{{ asset('images/logo.png') }}" width="30px" height="30px" />
            </span>
            <a href="{{ route('home') }}">
                <span class="clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold">QUẢN LÝ THIẾT BỊ</strong>
                    </span>
                </span>
            </a>
        </div>
        <div class="logo-element">
            <img alt="image" class="img-circle" src="{{ asset('images/logo.png') }}" width="30px" height="30px" />
        </div>
    </li>

    {{-- Quản lý đơn vị --}}
    @php $menu1 = request()->is('donvi*') || request()->is('phongkho*'); @endphp
    <li class="{{ $menu1 ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-building-columns"></i>
            <span class="nav-label">Quản lý đơn vị</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu1 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('donvi*') ? 'active' : '' }}"><a href="{{ route('donvi.index') }}">Đơn vị</a></li>
            <li class="{{ request()->is('phongkho*') ? 'active' : '' }}"><a href="{{ route('phongkho.index') }}">Phòng-kho</a></li>
        </ul>
    </li>
    {{-- Quản lý thiết bị --}}
    @php $menu2 = request()->is('loaithietbi*') || request()->is('nhomthietbi*') || request()->is('maymocthietbi*'); @endphp
    <li class="{{ $menu2 ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-desktop"></i>
            <span class="nav-label">Thiết bị máy móc</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu2 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('loaithietbi*') ? 'active' : '' }}">
                <a href="{{ route('loaithietbi.index') }}">Loại thiết bị máy móc</a>
            </li>
            <li class="{{ request()->is('nhomthietbi*') ? 'active' : '' }}">
                <a href="{{ route('nhomthietbi.index') }}">Nhóm thiết bị máy móc</a>
            </li>
            <li class="{{ request()->is('maymocthietbi*') ? 'active' : '' }}">
                <a href="{{ route('maymocthietbi.index') }}">Máy móc thiết bị</a>
            </li>
        </ul>
    </li>

    @can('isAdmin')
    {{-- Quản lý người dùng --}}
    @php $menu3 = request()->is('loaitaikhoan*') || request()->is('taikhoan*'); @endphp
    <li class="{{ $menu3 ? 'active' : '' }}">
        <a href="#"><i class="fa fa-group"></i>
            <span class="nav-label">Quản lý người dùng</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu3 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('loaitaikhoan*') ? 'active' : '' }}"><a href="{{ route('loaitaikhoan.index') }}">Loại tài khoản</a></li>
            <li class="{{ request()->is('taikhoan*') ? 'active' : '' }}"><a href="{{ route('taikhoan.index') }}">Danh sách tài khoản</a></li>
            <li><a href="#">Lịch sử truy cập</a></li>
        </ul>
    </li>
    @endcan

    {{-- Đồ nội thất --}}
    @php $menu4 = request()->is('loainoithat*')||request()->is('noithat*')||request()->is('kiemke*'); @endphp
    <li class="{{ $menu4 ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-bed"></i>
            <span class="nav-label">Đồ nội thất</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu4 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('loainoithat*') ? 'active' : '' }}"><a href="{{route('loainoithat.index') }}">Loại đồ nội thất</a></li>
            <li class="{{ request()->is('noithat*') ? 'active' : '' }}"><a href="{{ route('noithat.index', ['openModal' => 'selectDonVi']) }}">Danh sách nội thất</a></li>
            <li class="{{ request()->is('kiemke*') ? 'active' : '' }}"><a href="{{ route('kiemke.index',['openModal' => 'selectDonVi']) }}">Danh mục kiểm kê</a></li>
        </ul>
    </li>

    {{-- Biểu mẫu --}}
    @php $menu5 = request()->is('bieumau*'); @endphp
    <li class="{{ $menu5 ? 'active' : '' }}">
        <a href="#"><i class="fa fa-file"></i>
            <span class="nav-label">Biểu mẫu</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu5 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('bieumau/thietbi*') ? 'active' : '' }}">
                <a href="{{ route('bieumau.thietbi') }}">Biểu mẫu thiết bị</a>
            </li>
            <li class="{{ request()->is('bieumau/sokho*') ? 'active' : '' }}">
                <a href="{{ route('bieumau.sokho') }}">Sổ quản lý kho</a>
            </li>
            <li class="{{ request()->is('bieumau/nhatky*') ? 'active' : '' }}">
                <a href="{{ route('bieumau.nhatky') }}">Nhật ký phòng máy</a>
            </li>
        </ul>
    </li>

    {{-- Ghi sổ nhật ký --}}
    @php $menu6 = request()->is('nhatkyphongmay*') || request()->is('hocky*') || request()->is('nhatkythietbi*')|| request()->is('soquanlykho*'); @endphp
    <li class="{{ $menu6 ? 'active' : '' }}">
        <a href="#"><i class="fa fa-book"></i>
            <span class="nav-label">Ghi sổ nhật ký</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu6 ? 'in' : 'collapse' }}">
            <li class="{{ request()->is('hocky*') ? 'active' : '' }}"><a href="{{ route('hocky.index') }}">Quản lý học kỳ</a></li>
            <li class="{{ request()->is('nhatkyphongmay*') ? 'active' : '' }}">
                <a href="{{ route('nhatkyphongmay.index') }}">Nhật ký phòng máy</a>
            </li>
            <li><a href="{{ route('soquanlykho.index') }}">Sổ quản lý kho</a></li>
            <!-- <li><a href="{{ route('nhatkyloaithietbi.index') }}">Nhật ký từng loại thiết bị</a></li> -->
        </ul>
    </li>
    @canany('hasRole_A_M_L')
    {{-- Đồ dùng văn phòng --}}
    @php $menu7 = request()->is('quanlydanhmuc*')||request()->is('denghi*'); @endphp
    <li class="{{ $menu7? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-file-text"></i>
            <span class="nav-label">Mua sắm vật tư</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu7? 'in' : 'collapse' }}">
            <li class="{{ request()->is('quanlydanhmuc*')? 'active' : '' }}">
                <a href="{{ route('quanlydanhmuc.index') }}">Mua sắm sửa chữa thường xuyên</a>
            </li>
            <li class="{{ request()->is('denghi*')? 'active' : '' }}">
                <a href="{{ route('denghi.index') }}">Quản lý đề nghị</a>
            </li>
            <!-- <li class="{{ request()->is('quanlydanhmuc*')? 'active' : '' }}">
                <a href="#">Mua sắm theo dự án</a>
            </li> -->
        </ul>
    </li>
    @can('hasRole_Admin_Manager')
    {{-- Vật tư dự trù --}}
    @php $menu8 = request()->is('capphatvattu*')||request()->is('quanlyvattu*'); @endphp
    <li class="{{ $menu8 ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-bed"></i>
            <span class="nav-label">Vật tư dự trù</span><span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level {{ $menu8? 'in' : 'collapse' }}">
            <li class="{{ request()->is('quanlyvattu*') ? 'active' : '' }}"><a href="{{route('quanlyvattu.index') }}">Quản lý vật tư</a></li>
            <li class="{{ request()->is('capphatvattu*') ? 'active' : '' }}"><a href="{{route('capphatvattu.index') }}">Cấp phát vật tư</a></li>
        </ul>
    </li>
    @endcan
    @endcan
</ul>