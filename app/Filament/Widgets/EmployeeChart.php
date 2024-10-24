<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class EmployeeChart extends ChartWidget
{
  protected static ?string $heading = 'Employee Number';
  protected static string $color = 'warning';
  protected static ?int $sort = 2;


  // protected function getData(): array
  // {
  //   [];
  // }

  protected function getType(): string
  {
    return 'donut';
  }
}
