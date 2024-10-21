<?php

namespace App\Filament\Exports;

use App\Models\Payroll;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PayrollExporter extends Exporter
{
  protected static ?string $model = Payroll::class;

  public static function getColumns(): array
  {
    return [
      // ExportColumn::make('id')
      //   ->label('ID'),
      ExportColumn::make('bln_thn'),
      ExportColumn::make('employee.npp'),
      ExportColumn::make('employee.first_name'),
      ExportColumn::make('employee.last_name'),
      ExportColumn::make('1050_honorarium'),
      ExportColumn::make('uang_saku_mb'),
      ExportColumn::make('3000_lembur'),
      ExportColumn::make('2580_tunj_lain'),
      ExportColumn::make('ujp'),
      ExportColumn::make('4020_sumbangan_cuti_tahunan'),
      ExportColumn::make('6500_pot_wajib_koperasi'),
      ExportColumn::make('6540_pot_pinjaman_koperasi'),
      ExportColumn::make('6590_pot_ykkkf'),
      ExportColumn::make('6620_pot_keterlambatan'),
      ExportColumn::make('6630_pinjaman_karyawan'),
      ExportColumn::make('6700_pot_bank_mandiri'),
      ExportColumn::make('6701_pot_bank_bri'),
      ExportColumn::make('6702_pot_bank_btn'),
      ExportColumn::make('6703_pot_bank_danamon'),
      ExportColumn::make('6704_pot_bank_dki'),
      ExportColumn::make('6705_pot_bank_bjb'),
      ExportColumn::make('6750_pot_adm_bank_mandiri'),
      ExportColumn::make('6751_pot_adm_bank_bri'),
      ExportColumn::make('6752_pot_adm_bank_bjb'),
      ExportColumn::make('6900_pot_lain'),
      // ExportColumn::make('created_at'),
      // ExportColumn::make('updated_at'),
    ];
  }

  public static function getCompletedNotificationBody(Export $export): string
  {
    $body = 'Your payroll export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

    if ($failedRowsCount = $export->getFailedRowsCount()) {
      $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
    }

    return $body;
  }
}
