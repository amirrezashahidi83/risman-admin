<?php

namespace App\Filament\Resources\CounselorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AdminResource;

class AdminRelationManager extends RelationManager
{
    protected static string $relationship = 'admin';

    public function form(Form $form): Form
    {
        return AdminResource::form($form);
    }

    public function table(Table $table): Table
    {
        return AdminResource::table($table);
    }
}
