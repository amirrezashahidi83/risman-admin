<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Counselor;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('تعداد دانش آموزان', Student::count()),
            Stat::make('تعداد مشاوران',Counselor::count()),
            Stat::make('تعداد پرداخت این ماه', Transaction::where('status',1)
            ->whereBetween('created_at',
                [Carbon::today()->subDays(30),Carbon::today()]
                )
            ->count() 
            ),
        ];
    }
}
