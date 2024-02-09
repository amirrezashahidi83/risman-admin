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

    protected function getMonthPay(){
        return [
            Stat::make('تعداد دانش آموزان', Student::count())->hidden(),
            Stat::make('تعداد مشاوران',Counselor::count()),
            Stat::make('تعداد پرداخت این ماه', Transaction::where('status',1)
            ->whereBetween('created_at',
                [Carbon::today()->subDays(30),Carbon::today()]
                )
            ->count() 
            ),

        ];
    }

    protected function getStats(): array
    {
        $counselors_count = 0;
        $students_count = 0;
        $student_plans_count = 0;

        $role = auth()->user()->role->value;
        if($role == 'super'){
            $students_count = Student::count();
            $counselors_count = Counselor::count();

        }else if($role == 'school'){
            $students_count = Student::whereRelation('counselor','admin_id',auth()->user()->id)
            ->orWhereRelation('counselor.admin','role','counselor');

            $counselors_count = Counselor::where('admin_id',auth()->user()->id)->orWhereRelation('admin','role','counselor');
        }else {
            $students_count = Student::whereRelation('counselor','admin_id',auth()->user()->id);
            $counselors_count = Counselor::where('admin_id',auth()->user()->id);
        }

        return [
            Stat::make('تعداد دانش آموزان', 
                $students_count
            ),
            Stat::make('تعداد مشاوران',
                $counselors_count
            ),
            /*Stat::make('تعداد درخواست های برنامه امروز', 
            auth()->user()->role->value == 'super' ? 
                PlanRequest::whereBetween('created_at',
                    [Carbon::today()->subDays(1),Carbon::today()]
                    )
                ->count() 
            :
                PlanRequest::whereBetween('created_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
                ->whereRelation('counselor','admin_id',auth()->user()->id)
                ->count() 
            ),
            Stat::make('تعداد برنامه های ارسال شده امروز', 
            auth()->user()->role->value == 'super' ?
                StudentPlan::whereBetween('updated_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
                ->count() 
            :
                StudentPlan::whereBetween('created_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
                ->whereRelation('student.counselor','admin_id',auth()->user()->id)
                ->count() 
            ),
            Stat::make('تعداد گزارشات ثبت شده امروز', 
            auth()->user()->role->value == 'super' ?
                StudyPlan::whereBetween('created_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
                ->count() 
            :
	    StudyPlan::whereRelation('student.counselor','admin_id',auth()->user()->id)

	    ->whereBetween('created_at',
                [Carbon::today()->subDays(1),Carbon::today()]
                )
                ->count() 
            ),*/


        ];
    }
}
