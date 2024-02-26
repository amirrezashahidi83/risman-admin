<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
class SchoolProfile extends Page implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = []; 

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.school-profile';

    protected static ?string $title = 'پروفایل مدرسه';
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
    public function mount(): void 
    {
        //dd(auth()->user()->school);
        $this->form->fill(auth()->user()->school->toArray());
    }
 
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('نام موسسه')
                    ->required(),
                    ColorPicker::make('theme')
                        ->label('رنگ سازمانی')
                        ->rgb()
                        ->dehydrateStateUsing(fn (string $state): string => strval($state))
            
        ])->statePath('data');
    } 

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('ذخیره کردن')
                ->submit('save'),
        ];    
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
 
            
            auth()->user()->school->update($data);

            Notification::make() 
            ->success()
            ->title('با موفقیت ذخیره شد')
            ->send(); 
        } catch (Halt $exception) {
            return;
        }
    }
}
