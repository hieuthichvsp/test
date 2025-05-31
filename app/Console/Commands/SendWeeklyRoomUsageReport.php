<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\report\RoomUsageController;
use Illuminate\Support\Facades\Mail;

class SendWeeklyRoomUsageReport extends Command
{
    protected $signature = 'report:weekly-room-usage';
    protected $description = 'Gửi báo cáo sử dụng phòng máy hàng tuần cho GVQL';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        try {
            $controller = new RoomUsageController();
            $reports = $controller->generateWeeklyReport();

            if (empty($reports['reports_by_gvql'])) {
                $this->info('Không có dữ liệu để gửi.');
                return;
            }

            foreach ($reports['reports_by_gvql'] as $gvqlEmail => $gvqlData) {
                $filePath = $controller->createWordReport($reports, $gvqlEmail);

                Mail::raw('Kính gửi thầy(cô)' . $gvqlData['gvql_name'] . ",\n\nĐính kèm là báo cáo sử dụng phòng máy hàng tuần.\n\nTrân trọng,\nHệ thống quản lý phòng máy", function ($message) use ($gvqlEmail, $filePath) {
                    $message->to($gvqlEmail)
                        ->subject('Báo cáo sử dụng phòng máy hàng tuần')
                        ->attach($filePath);
                });

                // Xóa file tạm sau khi gửi mail
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->info('Đã gửi báo cáo sử dụng phòng máy hàng tuần cho GVQL: ' . $gvqlEmail);
            }
        } catch (\Exception $e) {
            $this->error('Error sending weekly room usage report: ' . $e->getMessage());
        }
    }
}
