<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\Counselor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use Hash;
use App\Filament\Resources\CounselorResource;

class CounselorsRelationManager extends RelationManager
{
    protected static ?string $inverseRelationship  = 'counselor';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return CounselorResource::form($form);
    }

    public function table(Table $table): Table
    {
        return CounselorResource::table($table);
    }
}
