<?php
namespace Tests\Feature;

use function Pest\Livewire\livewire;
it('creates admin',function () {
        livewire(\App\Filament\Resources\AdminResource\Pages\CreateAdmin::class)
        ->fillForm([

        ])
        ->call('create')
        ->assertHasNoFormErrors();
    }
);

