<?php
namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use App\Models\StudyTime;

class StudyTimeCollection extends Collection
{
    public function sumAll()
    {
        $result = ['study_time' => 0,'test_count' => 0];

        foreach($this as $studyTime){
            $result['study_time'] += $studyTime->study_time;
            $result['test_count'] += $studyTime->test_count;
        }

        return $result;
    }

    public function compare($second){

        $first = $this->sumAll();

        $second = $second->sumAll();

        return [
            'study_time' => $second['study_time'] - $first['study_time'],
            'test_count' => $second['test_count'] - $first['test_count']
        ];
    }
}

?>