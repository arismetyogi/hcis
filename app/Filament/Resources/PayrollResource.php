<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PayrollExporter;
use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Employee;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollResource extends Resource
{
  protected static ?string $model = Payroll::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel = 'Payroll';

  protected static ?string $modelLabel = 'Payroll';

  protected static ?string $slug = 'employees-payrolls';



  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('bln_thn')
          ->label('bulan-tahun')
          ->required()
          ->placeholder('0824')
          ->maxLength(8),
        Forms\Components\Select::make('employee_id')
          ->relationship('employee', 'id')
          ->getOptionLabelFromRecordUsing(fn($record) => "{$record->npp} - {$record->first_name} {$record->last_name}")
          ->searchable()
          ->preload()
          ->required(),
        Forms\Components\TextInput::make('1050_honorarium')
          ->label('1050 - Honorarium')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('uang_saku_mb')
          ->label('Uang Saku MB')
          ->numeric(),
        Forms\Components\TextInput::make('3000_lembur')
          ->label('3000 - Lembur')
          ->numeric(),
        Forms\Components\TextInput::make('2580_tunj_lain')
          ->label('2580 - Tunjangan Lainnya')
          ->numeric(),
        Forms\Components\TextInput::make('ujp')
          ->label('Upah Jasa Pelayanan')
          ->numeric(),
        Forms\Components\TextInput::make('4020_sumbangan_cuti_tahunan')
          ->label('4020 - Sumbangan Cuti Tahunan')
          ->numeric(),
        Forms\Components\TextInput::make('6500_pot_wajib_koperasi')
          ->label('6500 - Potongan Wajib Koperasi')
          ->numeric(),
        Forms\Components\TextInput::make('6540_pot_pinjaman_koperasi')
          ->label('6540 - Potongan Pinjaman Koperasi')
          ->numeric(),
        Forms\Components\TextInput::make('6590_pot_ykkkf')
          ->label('6590 - Potongan YKKKF')
          ->numeric(),
        Forms\Components\TextInput::make('6620_pot_keterlambatan')
          ->label('6620 - Potongan Keterlambatan')
          ->numeric(),
        Forms\Components\TextInput::make('6630_pinjaman_karyawan')
          ->label('6630 - Pinjaman Karyawan')
          ->numeric(),
        Forms\Components\TextInput::make('6700_pot_bank_mandiri')
          ->label('6700 - Pot. Bank Mandiri')
          ->numeric(),
        Forms\Components\TextInput::make('6701_pot_bank_bri')
          ->label('6701 - Pot. Bank BRI')
          ->numeric(),
        Forms\Components\TextInput::make('6702_pot_bank_btn')
          ->label('6702 - Pot. Bank BTN')
          ->numeric(),
        Forms\Components\TextInput::make('6703_pot_bank_danamon')
          ->label('6703 - Pot. Bank Danamon')
          ->numeric(),
        Forms\Components\TextInput::make('6704_pot_bank_dki')
          ->label('6704 - Pot. Bank DKI')
          ->numeric(),
        Forms\Components\TextInput::make('6705_pot_bank_bjb')
          ->label('6705 - Pot. Bank BJB')
          ->numeric(),
        Forms\Components\TextInput::make('6750_pot_adm_bank_mandiri')
          ->label('6750 - Pot. Adm. Bank Mandiri')
          ->numeric(),
        Forms\Components\TextInput::make('6751_pot_adm_bank_bri')
          ->label('6751 - Pot. Adm. Bank BRI')
          ->numeric(),
        Forms\Components\TextInput::make('6752_pot_adm_bank_bjb')
          ->label('6752 - Pot. Adm. Bank BJB')
          ->numeric(),
        Forms\Components\TextInput::make('6900_pot_lain')
          ->label('6900 - Pot. Lainnya')
          ->numeric(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([

        Tables\Columns\TextColumn::make('bln_thn')
          ->label('Bln-Thn')
          ->searchable(),
        Tables\Columns\TextColumn::make('employee.npp')
          ->label('NPP Pegawai')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('employee.id')
          ->label('Nama Pegawai')
          ->getStateUsing(function ($record) { //join first_name and last_name from employee relationship
            return "{$record->employee->first_name} {$record->employee->last_name}";
          })
          ->searchable(['employee.first_name', 'employee.last_name']) // Make both columns searchable
          ->sortable(function ($query, $direction) {
            $query->orderBy('employee.first_name', $direction)
              ->orderBy('employee.last_name', $direction);
          })
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('1050_honorarium')
          ->label('1050 - Honorarium')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('uang_saku_mb')
          ->label('Uang Saku MB')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('3000_lembur')
          ->label('3000 - Lembur')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('2580_tunj_lain')
          ->label('2580 - Tunj Lain')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('ujp')
          ->label('UJP')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('4020_sumbangan_cuti_tahunan')
          ->label('4020 - Sumb. Cuti Tahunan')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6500_pot_wajib_koperasi')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6540_pot_pinjaman_koperasi')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6590_pot_ykkkf')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6620_pot_keterlambatan')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6630_pinjaman_karyawan')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6700_pot_bank_mandiri')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6701_pot_bank_bri')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6702_pot_bank_btn')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6703_pot_bank_danamon')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6704_pot_bank_dki')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6705_pot_bank_bjb')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6750_pot_adm_bank_mandiri')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6751_pot_adm_bank_bri')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6752_pot_adm_bank_bjb')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('6900_pot_lain')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
      ])
      ->headerActions([
        ExportAction::make()
          ->exporter(PayrollResource::class)
          ->columnMapping(false)
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          ExportBulkAction::make()
            ->exporter(PayrollExporter::class)
            ->columnMapping(false),
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListPayrolls::route('/'),
      'create' => Pages\CreatePayroll::route('/create'),
      'view' => Pages\ViewPayroll::route('/{record}'),
      'edit' => Pages\EditPayroll::route('/{record}/edit'),
    ];
  }
}
