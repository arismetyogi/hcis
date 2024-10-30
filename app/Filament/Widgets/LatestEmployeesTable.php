<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LatestEmployeesTable extends BaseWidget
{
  protected static ?int $sort = 3;

  protected int | string | array $columnSpan = 'full';

  public function getLatestDistinctBlnThn($limit = 3)
  {
    // Fetch the latest distinct 'bln_thn' values, limited to the specified count
    return DB::table('payrolls')
      ->select('bln_thn')
      ->distinct()
      ->orderByDesc('bln_thn')
      ->limit($limit)
      ->pluck('bln_thn');
  }


  public function table(Table $table): Table
  {
    $latestBlnThns = $this->getLatestDistinctBlnThn();

    // dd($latestBlnThns);

    return $table
      ->query(
        Employee::query()
          ->when(!Auth::user()->is_admin, function ($query) {
            return $query->where('department_id', Auth::user()->department_id);
          })
          // ->when(request()->input('bln_thn'), function ($query, $bln_thn) {
          //   // Apply the filter when the blnThn is present
          //   $query->whereHas('payrolls', function ($subquery) use ($bln_thn) {
          //     $subquery->where('bln_thn', $bln_thn);
          //   });
          // })
          ->withCount('payrolls')
          ->with(['outlet'])
      )
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('sap_id')
          ->label('ID SAP')
          ->sortable()
          ->searchable(),
        TextColumn::make('npp')
          ->label('NPP')
          ->sortable()
          ->searchable(),
        TextColumn::make('first_name')
          ->label('Employee Name')
          ->sortable()
          ->searchable(['first_name', 'last_name'])
          ->getStateUsing(function ($record) {
            return $record->first_name . ' ' . $record->last_name;
          }),
        TextColumn::make('department_id')
          ->label('Department')
          ->getStateUsing(function ($record) {
            return $record->department->id . ' - ' . $record->department->name;
          })
          ->sortable(),
        TextColumn::make('outlet_id')
          ->label('Outlet')
          ->getStateUsing(function ($record) {
            return $record->outlet->outlet_sap_id . ' - ' . $record->outlet->name;
          })
          ->sortable(),
        IconColumn::make('payroll_status')
          ->label('Status Payroll')
          ->getStateUsing(
            fn(Employee $record): string => $record->payrolls()->exists()
          )
          ->boolean(),
        TextColumn::make('payrolls_count')
          ->label('Jml Record Payroll')
          ->sortable()
          ->getStateUsing(
            fn(Employee $record): int => $record->payrolls_count
          ),
        TextColumn::make('created_at')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        TextColumn::make('updated_at')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('Unit Kerja')
          ->relationship('department', 'name')
          ->searchable()
          ->preload()
          ->visible(fn($livewire) => Auth::user()->is_admin)
          ->indicator('Unit Kerja'),
        SelectFilter::make('Jabatan')
          ->relationship('title', 'name'),
      ])
    ;
  }
}
