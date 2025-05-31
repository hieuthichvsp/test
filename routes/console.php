<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// Lập lịch để báo cáo
Schedule::command('report:weekly-room-usage')
    // ->weeklyOn(1, '07:00')
    ->everyMinute()
    ->timezone('Asia/Ho_Chi_Minh')
    ->before(function () {
        Log::info('Scheduler chuẩn bị chạy lệnh report:weekly-room-usage');
    })
    ->after(function () {
        Log::info('Scheduler đã chạy xong lệnh report:weekly-room-usage');
    });
