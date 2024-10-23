<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Payroll;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EmployeeChart extends ChartWidget
{
  protected static ?string $heading = 'Employee Number';
  protected static string $color = 'warning';
  protected static ?int $sort = 2;


  protected function getData(): array
  {
    $empdata = Trend::model(Employee::class)
      ->between(
        start: now()->startOfMonth(),
        end: now()->endOfMonth()
      )
      ->perDay()
      ->count();

    $payrolldata = Trend::model(Payroll::class)
      ->between(
        start: now()->startOfMonth(),
        end: now()->endOfMonth()
      )
      ->perDay()
      ->count();

    return [
      'datasets' => [
        [
          'label' => 'Employees',
          'data' => $empdata->map(fn(TrendValue $value) => $value->aggregate),
        ],
        [
          'label' => 'Payrolls Filled',
          'data' => $payrolldata->map(fn(TrendValue $value) => $value->aggregate),
        ],
      ],
      'labels' => $empdata->map(fn(TrendValue $value) => $value->date),
    ];
  }

  protected function getType(): string
  {
    return 'line';
  }
}
