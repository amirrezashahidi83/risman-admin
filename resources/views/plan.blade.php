@foreach ( json_decode($getRecord()->plan->data,true) as $day => $data)
    <h5>{{ \Morilog\Jalali\Jalalian::fromDateTime($day)->format('%A, %d %B %y') }}</h5>
        <div style="display: grid;grid-template-columns: auto auto auto;">
            @foreach ( $data as $study)
            <x-filament::fieldset style="margin:10px;">
                <div>
                    درس : {{ $study['lesson']['title'] }}
                </div>

                <div>
                    ساعت مطالعه : {{ floor($study['study_time'] / 60) .":" .$study['study_time'] % 60 }}
                </div>

                <div>
                    تعداد تست : {{ $study['test_count'] }}
                </div>
            </x-filament::fieldset>
            @endforeach
        </div>
@endforeach