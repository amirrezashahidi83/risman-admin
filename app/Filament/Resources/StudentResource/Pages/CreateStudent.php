<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\User;
use Hash;
class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions() : array{
        return [
        ];
    }

    /*protected function handleRecordCreation(array $data): Model
    {
        $data['user']['password'] = $data['password'];
        $data['user']['role'] = 2;
        $data['user']['status'] = $data['status'];
        unset($data['password']);
        $user = User::create($data['user']);
        unset($data['user']);
        $student = Student::create($data);
        $student->user_id = $user->id;
        $student->save();
        return $student;
    }*/
}
