<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Counselor;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\CounselorPlan;
use App\Models\PlanRequest;
use App\Models\Transaction;
use App\Models\StudentPlan;
use Carbon\Carbon;
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('تعداد دانش آموزان', Student::count())->hidden(),
            Stat::make('تعداد مشاوران',Counselor::count()),
            Stat::make('تعداد پرداخت این ماه', Transaction::where('status',1)
            ->whereBetween('created_at',
                [Carbon::today()->subDays(30),Carbon::today()]
                )
            ->count() 
            ),
            Stat::make('تعداد درخواست های برنامه امروز', PlanRequest::whereBetween('created_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
            ->count() 
            ),
            Stat::make('تعداد برنامه های ارسال شده امروز', StudentPlan::whereBetween('created_at',
            [Carbon::today()->subDays(1),Carbon::today()]
            )
            ->count() 
            ),
            Stat::make('تعداد گزارشات ثبت شده امروز', StudyPlan::whereBetween('created_at',
            [Carbon::today()->subDays(1),Carbon::today()]
            )
            ->count() 
            ),


        ];
    }
}
