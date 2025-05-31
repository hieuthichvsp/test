<?php

namespace App\Policies;

use App\Models\PhongKho;
use App\Models\Taikhoan;

class PhongKhoPolicy
{
    /**
     * Admin hoặc người quản lý đúng đơn vị thì được sửa.
     */
    public function update(Taikhoan $user, PhongKho $phongKho)
    {
        // Admin có quyền hết
        if ($user->can('isAdmin')) {
            return true;
        }

        // Nếu user quản lý đơn vị mà phongkho đó thuộc về
        return $user->donvis->contains('id', $phongKho->madonvi);
    }

    /**
     * Chỉ admin mới được xóa.
     */
    public function delete(Taikhoan $user, PhongKho $phongKho)
    {
        return $user->can('isAdmin');
    }
}
