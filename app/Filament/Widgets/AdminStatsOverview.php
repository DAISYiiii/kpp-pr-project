<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\NewsRequest;
use App\Models\Department;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('ข่าวที่เผยแพร่แล้ว', News::where('status', 'published')->count())
                ->description('จำนวนข่าวทั้งหมดบนหน้าเว็บไซต์')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('success'),

            Stat::make('คำขอรอตรวจสอบ', NewsRequest::where('current_status', 'ส่งข้อมูลแล้ว')->orWhere('current_status', 'อยู่ระหว่างตรวจสอบ')->count())
                ->description('รอเจ้าหน้าที่ประชาสัมพันธ์ตรวจสอบ')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('หน่วยงานในสังกัดทั้งหมด', Department::count())
                ->description('9 ส่วนราชการ, 1 โรงเรียน, 84 รพ.สต., 1 สถานีอนามัย')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
        ];
    }
}