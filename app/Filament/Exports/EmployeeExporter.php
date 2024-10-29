<?php

namespace App\Filament\Exports;

use App\Models\Employee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EmployeeExporter extends Exporter
{
  protected static ?string $model = Employee::class;

  public static function getColumns(): array
  {
    return [
      ExportColumn::make('npp'),
      ExportColumn::make('department_id')
        ->label('Kode BM'),
      ExportColumn::make('department.name')
        ->label('Nama BM'),
      ExportColumn::make('outlet.outlet_sap_id')
        ->label('Kode Outlet'),
      ExportColumn::make('outlet.name')
        ->label('Nama Outlet'),
      ExportColumn::make('nik'),
      ExportColumn::make('first_name'),
      ExportColumn::make('last_name'),
      ExportColumn::make('date_of_birth'),
      ExportColumn::make('phone_no'),
      ExportColumn::make('sex'),
      ExportColumn::make('address'),
      ExportColumn::make('postcode.postal_code'),
      ExportColumn::make('npwp'),
      ExportColumn::make('employee_status.name'),
      ExportColumn::make('title.name'),
      ExportColumn::make('subtitle.name'),
      ExportColumn::make('band.name'),
      ExportColumn::make('gradeeselon.id'),
      ExportColumn::make('area.name'),
      ExportColumn::make('emplevel.name'),
      ExportColumn::make('saptitle_id'),
      ExportColumn::make('saptitle_name'),
      ExportColumn::make('date_hired'),
      ExportColumn::make('date_promoted'),
      ExportColumn::make('date_last_mutated'),
      ExportColumn::make('descstatus.name'),
      ExportColumn::make('bpjs_id'),
      ExportColumn::make('insured_member_count'),
      ExportColumn::make('bpjs_class'),
      ExportColumn::make('bpjstk_id'),
      ExportColumn::make('contract_document_id'),
      ExportColumn::make('contract_sequence_no'),
      ExportColumn::make('contract_term'),
      ExportColumn::make('contract_start'),
      ExportColumn::make('contract_end'),
      ExportColumn::make('status_pasangan'),
      ExportColumn::make('jumlah_tanggungan'),
      ExportColumn::make('pasangan_ditanggung_pajak'),
      ExportColumn::make('rekening_no'),
      ExportColumn::make('rekening_name'),
      ExportColumn::make('bank.name'),
      ExportColumn::make('recruitment.name'),
      ExportColumn::make('pants_size'),
      ExportColumn::make('shirt_size'),
    ];
  }

  public static function getCompletedNotificationBody(Export $export): string
  {
    $body = 'Data karyawan berhasil diexport sejumlah ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows);

    if ($failedRowsCount = $export->getFailedRowsCount()) {
      $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal diexport.';
    }

    return $body;
  }
}
