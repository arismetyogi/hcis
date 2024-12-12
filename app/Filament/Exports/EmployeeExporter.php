<?php

namespace App\Filament\Exports;

use App\Models\Employee;
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

class EmployeeExporter extends Exporter implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable;
  public int $tries = 3;

  protected static ?string $model = Employee::class;

  public static function getColumns(): array
  {
    return [
      ExportColumn::make('sap_id')
        ->label('ID SAP'),
      ExportColumn::make('npp')
        ->label('NPP'),
      ExportColumn::make('department_id')
        ->label('Kode BM'),
      ExportColumn::make('department.name')
        ->label('Nama BM'),
      ExportColumn::make('outlet.outlet_sap_id')
        ->label('Kode Outlet'),
      ExportColumn::make('outlet.name')
        ->label('Nama Outlet'),
      ExportColumn::make('nik')
        ->label('NIK'),
      ExportColumn::make('name')
        ->label('Nama Lengkap')
        ->getStateUsing(function ($record) {
          return $record->first_name . ' ' . $record->last_name;
        }),
      ExportColumn::make('date_of_birth')
        ->label('Tanggal Lahir'),
      ExportColumn::make('phone_no')
        ->label('No Telp')
        ->getStateUsing(function ($record) {
          return '+62' . $record->phone_no; // Concatenate prefix with the phone number
        }),
      ExportColumn::make('sex')
        ->label('Jenis Kelamin'),
      ExportColumn::make('religion')
        ->label('Agama')
        ->getStateUsing(function ($record) {
          return $record->religion;
        }),
      ExportColumn::make('blood_type')
        ->label('Golongan Darah')
        ->getStateUsing(function ($record) {
          return $record->blood_type;
        }),
      ExportColumn::make('address')
        ->label('Alamat'),
      ExportColumn::make('postcode.postal_code')
        ->label('Kode Pos'),
      ExportColumn::make('npwp')
        ->label('NPWP'),
      ExportColumn::make('employee_status.name')
        ->label('Status Pegawai'),
      ExportColumn::make('title.name')
        ->label('Jabatan'),
      ExportColumn::make(name: 'subtitle.name')
        ->label('Sub Jabatan'),
      ExportColumn::make('band.name')
        ->label('Band'),
      ExportColumn::make('gradeeselon.id')
        ->label('Grade Eselon')
        ->getStateUsing(function ($record) {
          return $record->gradeeselon->grade . '-' . $record->gradeeselon->eselon;
        }),
      ExportColumn::make('area.name')
        ->label('Area'),
      ExportColumn::make('emplevel.name')
        ->label('Level Pegawai'),
      ExportColumn::make('saptitle_id')
        ->label('Kode Jab SAP'),
      ExportColumn::make('saptitle_name')
        ->label('Nama Jab SAP'),
      ExportColumn::make('date_hired')
        ->label('Tanggal Mulai Bekerja'),
      ExportColumn::make('date_promoted')
        ->label('Tanggal Diangkat'),
      ExportColumn::make('date_last_mutated')
        ->label('Tgl Mutasi Terakhir'),
      ExportColumn::make('descstatus.name')
        ->label('Deskripsi Status'),
      ExportColumn::make('bpjs_id')
        ->label('No BPJS'),
      ExportColumn::make('insured_member_count')
        ->label('Jml Tanggungan'),
      ExportColumn::make('bpjs_class')
        ->label('Kelas BPJS'),
      ExportColumn::make('bpjstk_id')
        ->label('No BPJSTK'),
      ExportColumn::make('contract_document_id')
        ->label('No Kontrak'),
      ExportColumn::make('contract_sequence_no')
        ->label('Kontrak Ke-'),
      ExportColumn::make('contract_term')
        ->label('Masa Kontrak'),
      ExportColumn::make('contract_start')
        ->label('Mulai Kontrak'),
      ExportColumn::make('contract_end')
        ->label('Berakhir Kontrak'),
      ExportColumn::make('status_pasangan')
        ->label('Status Pasangan'),
      ExportColumn::make('jumlah_tanggungan')
        ->label('Jumlah Tanggungan Pajak'),
      ExportColumn::make('pasangan_ditanggung_pajak')
        ->label('Pasangan Ditanggung Pajak'),
      ExportColumn::make('rekening_no')
        ->label('No Rekening Payroll'),
      ExportColumn::make('rekening_name')
        ->label('Nama Rekening'),
      ExportColumn::make('bank.name')
        ->label('Nama Bank'),
      ExportColumn::make('recruitment.name')
        ->label('Ket Rekrutmen'),
      ExportColumn::make('pants_size')
        ->label('Ukuran Celana'),
      ExportColumn::make('shirt_size')
        ->label('Ukuran Baju'),
    ];
  }

  public function getXlsxCellStyle(): ?Style
  {
    return (new Style())
      ->setFontName('Consolas');
  }

  public function getXlsxHeaderCellStyle(): ?Style
  {
    return (new Style())
      ->setFontBold()
      ->setFontName('Consolas');
  }

  public static function getCompletedNotificationBody(Export $export): string
  {
    $body = 'Data karyawan berhasil diexport sejumlah ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows);

    if ($failedRowsCount = $export->getFailedRowsCount()) {
      $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal diexport.';
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
