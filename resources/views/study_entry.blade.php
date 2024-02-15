<div style="display: grid;grid-template-columns: auto auto auto;">
    @foreach ( $getRecord()->data as $study)
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