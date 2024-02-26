<x-filament-panels::page
    @class([
        'fi-resource-edit-record-page'
    ])
>
        <x-filament-panels::form
            :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
            wire:submit="save"
        >
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </x-filament-panels::form>

</x-filament-panels::page>
