<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudyPlanResource;

class StudyPlanRelationManager extends RelationManager
{
    protected static string $relationship = 'studyPlans';
    
    protected static ?string $modelLabel = 'گزارش';
    protected static ?string $pluralModelLabel = 'گزارشات';
    protected static ?string $title = 'گزارش';

    public function form(Form $form): Form
    {
        return StudyPlanResource::form($form);
    }

    public function table(Table $table): Table
    {
        return StudyPlanResource::table($table);
    }
}
