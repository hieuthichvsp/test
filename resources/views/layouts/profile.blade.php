<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown" style="padding: 5px 0">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" style="padding: 10px 15px;">
            @php
            $imagePath = 'avatar/taikhoan/'. Auth::user()->email . '_' . Auth::user()->id.'/'. Auth::user()->hinhanh;
            @endphp

            @if (file_exists(public_path($imagePath)))
            <img src="{{ asset($imagePath) }}" class="img-circle" alt="Avatar" width="30" height="30">
            @else
            <img src="{{ asset('images/avatar.jpg') }}" class="img-circle" alt="Avatar" width="30" height="30">
            @endif
            <span style="margin-left: 5px;">{{Auth::user()->hoten}}</span></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li class="text-center">
                @if (file_exists(public_path($imagePath)))
                <img src="{{ asset($imagePath) }}" class="img-circle" alt="Avatar" width="100" height="100">
                @else
                <img src="{{ asset('images/avatar.jpg') }}" class="img-circle" alt="Avatar" width="100" height="100">
                @endif
                <h4 style="margin-top: 10px; margin-bottom: 5px;">{{Auth::user()->hoten}}</h4>
                <p style="font-size: 12px; color: gray;">{{ Auth::user()->email }}</p>
            </li>
            <li role="separator" class="divider"></li>
            <li><a href="{{route('profile.index')}}"><i class="glyphicon glyphicon-user"></i> Thông tin cá nhân</a></li>
            <li><a href="{{ route('profile.passwordUpdateForm') }}"><i class="glyphicon glyphicon-lock"></i> Đổi mật khẩu</a></li>
            <li role="separator" class="divider"></li>
            <li>
                <a href="#"
                    onclick="event.preventDefault();sessionStorage.clear(); document.getElementById('logout-form').submit();">
                    <i class="glyphicon glyphicon-log-out"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </li>
</ul>