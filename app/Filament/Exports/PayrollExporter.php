<?php

namespace App\Filament\Exports;

use App\Models\Payroll;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class PayrollExporter extends Exporter implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable;
  public $tries = 3;
  protected static ?string $model = Payroll::class;

  public static function getColumns(): array
  {
    return [
      ExportColumn::make('bln_thn')
        ->label('TahunBulan'),
      ExportColumn::make('employee.npp')
        ->label('NPP'),
      ExportColumn::make('employee_id')
        ->label('Nama Pegawai')
        ->getStateUsing(function ($record) { //join first_name and last_name from employee relationship
          return "{$record->employee->first_name} {$record->employee->last_name}";
        }),
      ExportColumn::make('1050_honorarium')
        ->label('1050-Honorarium'),
      ExportColumn::make('uang_saku_mb')
        ->label('Uang Saku MB'),
      ExportColumn::make('3000_lembur')
        ->label('3000-Lembur'),
      ExportColumn::make('2580_tunj_lain')
        ->label('2580-Tunjangan Lain'),
      ExportColumn::make('ujp')
        ->label('Upah Jasa Pelayanan'),
      ExportColumn::make('4020_sumbangan_cuti_tahunan')
        ->label('4020-Sumb Cuti Tahunan'),
      ExportColumn::make('6500_pot_wajib_koperasi')
        ->label('6500-Pot Wajib Koperasi'),
      ExportColumn::make('6540_pot_pinjaman_koperasi')
        ->label('6540-Pot Pinjaman Koperasi'),
      ExportColumn::make('6590_pot_ykkkf')
        ->label('6590-Pot YKKKF'),
      ExportColumn::make('6620_pot_keterlambatan')
        ->label('6620-Pot Keterlambatan'),
      ExportColumn::make('6630_pinjaman_karyawan')
        ->label('6630-Pinj Karyawan'),
      ExportColumn::make('6700_pot_bank_mandiri')
        ->label('6700-Pot Bank Mandiri'),
      ExportColumn::make('6701_pot_bank_bri')
        ->label('6701-Pot Bank BRI'),
      ExportColumn::make('6702_pot_bank_btn')
        ->label('6702-Pot Bank BTN'),
      ExportColumn::make('6703_pot_bank_danamon')
        ->label('6703-Pot Bank Danamon'),
      ExportColumn::make('6704_pot_bank_dki')
        ->label('6704-Pot Bank DKI'),
      ExportColumn::make('6705_pot_bank_bjb')
        ->label('6705-Pot Bank BJB'),
      ExportColumn::make('6750_pot_adm_bank_mandiri')
        ->label('6750-Pot Adm Bank Mandiri'),
      ExportColumn::make('6751_pot_adm_bank_bri')
        ->label('6751-Pot Adm Bank BRI'),
      ExportColumn::make('6752_pot_adm_bank_bjb')
        ->label('6752-Pot Adm Bank BJB'),
      ExportColumn::make('6900_pot_lain')
        ->label('6900-Pot Lainnya'),
    ];
  }

  public function getXlsxCellStyle(): ?Style
  {
    return (new Style())
      ->setFontSize(12)
      ->setFontName('Consolas');
  }

  public function getXlsxHeaderCellStyle(): ?Style
  {
    return (new Style())
      ->setFontBold()
      ->setFontItalic()
      ->setFontSize(14)
      ->setFontName('Consolas')
      ->setFontColor(Color::rgb(255, 255, 77))
      ->setBackgroundColor(Color::rgb(0, 0, 0))
      ->setCellAlignment(CellAlignment::CENTER)
      ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
  }
  public static function getCompletedNotificationBody(Export $export): string
  {
    $body = 'Your payroll export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

    if ($failedRowsCount = $export->getFailedRowsCount()) {
      $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
    }

    return $body;
  }

  // This method will be called when the job fails
  public function failed(MaxAttemptsExceededException $exception)
  {
    // Cleanup action: delete the job if it reaches max attempts
    $this->delete();
  }
}
