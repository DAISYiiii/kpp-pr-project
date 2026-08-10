<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\Category;
use Filament\Widgets\ChartWidget;

class NewsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'สถิติจำนวนข่าวแยกตามหมวดหมู่ภารกิจ';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $categories = Category::withCount('news')->get();

        return [
            'datasets' => [
                [
                    'label' => 'จำนวนข่าว',
                    'data' => $categories->pluck('news_count'),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'],
                ],
            ],
            'labels' => $categories->pluck('category_name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // รูปแบบกราฟ (bar, pie, line, doughnut)
    }
}