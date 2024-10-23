<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Outlet;
use App\Models\Payroll;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class EmployeeStatsOverview extends BaseWidget
{
  protected static ?int $sort = 1;
  protected function getStats(): array
  {
    return [
      Stat::make('Employee Count', value: Employee::query()
        ->when(!Auth::user()->is_admin, function ($query) {
          return $query->where('department_id', Auth::user()->department_id);
        })
        ->count()),
      Stat::make('Payrolls filled', value: Payroll::query()
        ->when(!Auth::user()->is_admin, function ($query) {
          return $query->where('department_id', Auth::user()->department_id);
        })->count()),
      Stat::make('Outlet Count', value: Outlet::query()
        ->when(!Auth::user()->is_admin, function ($query) {
          return $query->where('department_id', Auth::user()->department_id);
        })
        ->count()),
    ];
  }
}
