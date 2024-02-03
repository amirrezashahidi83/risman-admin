<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CounselorPlanResource;
class AllPlansRelationManager extends RelationManager
{
    protected static string $relationship = 'allPlans';

    protected static ?string $modelLabel = 'برنامه';
    protected static ?string $pluralModelLabel = 'برنامه ها';
    protected static ?string $title = 'برنامه';

    public function form(Form $form): Form
    {
        return CounselorPlanResource::form($form);
    }

    public function table(Table $table): Table
    {
        return CounselorPlanResource::table($table);
    }
}
