<?php

namespace App\Filament\Resources\AdminResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CounselorResource;

class CounselorsRelationManager extends RelationManager
{
    protected static string $relationship = 'counselors';
    protected static ?string $modelLabel = 'مشاور';
    protected static ?string $pluralModelLabel = 'مشاوران';
    protected static ?string $title = 'مشاور';

    public function form(Form $form): Form
    {
        return CounselorResource::form($form);
    }

    public function table(Table $table): Table
    {
        return CounselorResource::table($table);
    }
}
