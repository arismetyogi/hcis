<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestEmployeesTable extends BaseWidget
{
  protected static ?int $sort = 3;

  protected int | string | array $columnSpan = 'full';
  public function table(Table $table): Table
  {
    return $table
      ->query(
        Employee::query()
          ->when(!Auth::user()->is_admin, function ($query) {
            return $query->where('department_id', Auth::user()->department_id);
          })
          ->withCount('payrolls')
      )
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('npp')
          ->label('NPP'),
        TextColumn::make('id')
          ->label('Employee Name')
          ->sortable()
          ->getStateUsing(function ($record) {
            return $record->first_name . ' ' . $record->last_name;
          }),
        TextColumn::make('outlet_id')
          ->label('Outlet')
          ->getStateUsing(function ($record) {
            return $record->outlet->outlet_sap_id . ' - ' . $record->outlet->name;
          })
          ->sortable(),
        TextColumn::make('payroll_id')
          ->label('Status Payroll')
          // ->getStateUsing(fn(Employee $record): string => $record->payrolls()->exists())
          ->getStateUsing(fn(Employee $record): string => $record->payrolls()->exists() ? '󰄬' : ''),
      ]);
  }
}
