<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEmployee extends CreateRecord
{
  protected static string $resource = EmployeeResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $user = Auth::user();

    // Set department_id to the user's department if the user is not an admin
    if (!$user->is_admin) {
      $data['department_id'] = $user->department_id;
    }
    return $data;
  }

  protected function mutateFormDataBeforeSave($data): array
  {
    $user = Auth::user();

    // Set department_id to the user's department if the user is not an admin
    if (!$user->is_admin) {
      $data['department_id'] = $user->department_id;
    }
    return $data;
  }
}
