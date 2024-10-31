<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PayrollExporter;
use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Employee;
use App\Models\Outlet;
use App\Models\Payroll;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PayrollResource extends Resource
{
  protected static ?string $model = Payroll::class;

  protected static ?string $navigationIcon = 'heroicon-o-banknotes';

  protected static ?string $navigationLabel = 'Payroll';

  protected static ?string $modelLabel = 'Payroll';

  protected static ?string $slug = 'employees-payrolls';


  public static function getGlobalSearchEloquentQuery(): Builder
  {
    return parent::getGlobalSearchEloquentQuery()->with(['employee']);
  }
  public static function getGloballySearchableAttributes(): array
  {
    return ['employee.npp', 'employee.first_name', 'employee.last_name'];
  }

  public static function getGlobalSearchResultDetails(Model $record): array
  {
    return [
      'Karyawan' => $record->employee->npp . ' - ' . $record->employee->first_name . ' ' . $record->employee->last_name,
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    $user = Auth::user();

    if ($user->is_admin) {
      // If the user is an admin, return the total count without filtering
      return static::getModel()::count();
    }

    // Count the records filtered by the user's department
    return static::getModel()::whereHas('employee', function ($subquery) {
      $subquery->where('department_id', Auth::user()->department_id);
    })->count();
  }
  public static function getNavigationBadgeColor(): string|array|null
  {
    return 'warning';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('bln_thn')
          ->label('TahunBulan')
          ->required()
          ->options(function () {
            // Get current month
            $currentMonth = Carbon::now()->startOfMonth();

            // Define options for -1, 0 (current), +1 month
            return [
              $currentMonth->copy()->subMonth()->format('ym') => $currentMonth->copy()->subMonth()->format('F Y'),
              $currentMonth->format('Ym') => $currentMonth->format('F Y'),
              $currentMonth->copy()->addMonth()->format('ym') => $currentMonth->copy()->addMonth()->format('F Y'),
              $currentMonth->copy()->addMonth(2)->format('ym') => $currentMonth->copy()->addMonth(2)->format('F Y'),
            ];
          })
          ->default(Carbon::now()->format('Ym')),
        // Forms\Components\TextInput::make('bln_thn')
        //   ->label('TahunBulan')
        //   ->required()
        //   ->placeholder('2410')
        //   ->maxLength(4),
        Forms\Components\Select::make('employee_id')
          ->relationship(
            'employee',
            'id',
            fn($query) => Auth::user()->is_admin ? $query : $query->where('department_id', Auth::user()->department_id)
          )
          ->getOptionLabelFromRecordUsing(fn($record) => "{$record->npp} - {$record->first_name} {$record->last_name}")
          ->preload()
          ->searchable(['npp', 'first_name', 'last_name'])
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
          ->label('Thn-Bln')
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
        Tables\Columns\TextColumn::make('outlet.name')
          ->label('Unit Kerja')
          ->getStateUsing(fn($record) => "{$record->outlet->outlet_sap_id}-{$record->outlet->name}")
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('department_id')
          ->label('Unit Bisnis')
          ->getStateUsing(fn($record) => "{$record->department->id}-{$record->department->name}")

          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('1050_honorarium')
          ->label('1050-Honorarium')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('uang_saku_mb')
          ->label('Uang Saku MB')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('3000_lembur')
          ->label('3000-Lembur')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('2580_tunj_lain')
          ->label('2580-Tunj Lain')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('ujp')
          ->label('UJP')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('4020_sumbangan_cuti_tahunan')
          ->label('4020-Sumb. Cuti Tahunan')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6500_pot_wajib_koperasi')
          ->label('6500-Pot Wajib Koperasi')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6540_pot_pinjaman_koperasi')
          ->label('6540-Pot Pinjaman Koperasi')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6590_pot_ykkkf')
          ->label('6590-Pot YKKKF')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6620_pot_keterlambatan')
          ->label('6620-Pot Keterlambatan')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6630_pinjaman_karyawan')
          ->label('6630-Pinjaman Karyawan')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6700_pot_bank_mandiri')
          ->label('6700-Pot Bank Mandiri')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6701_pot_bank_bri')
          ->label('6701-Pot Bank BRI')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6702_pot_bank_btn')
          ->label('6702-Pot Bank BTN')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6703_pot_bank_danamon')
          ->label('6703-Pot Bank Danamon')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6704_pot_bank_dki')
          ->label('6704-Pot Bank DKI')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6705_pot_bank_bjb')
          ->label('6705-Pot Bank BJB')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6750_pot_adm_bank_mandiri')
          ->label('6750-Pot Adm Bank Mandiri')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6751_pot_adm_bank_bri')
          ->label('6751-Pot Adm Bank BRI')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6752_pot_adm_bank_bjb')
          ->label('6752-Pot Adm Bank BJB')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('6900_pot_lain')
          ->label('6900-Pot Lainnya')
          ->numeric()
          ->summarize(Sum::make())
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->summarize(Sum::make())
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
        ActionGroup::make([
          ViewAction::make(),
          EditAction::make(),
          DeleteAction::make(),
        ])->icon('heroicon-m-ellipsis-horizontal')->color('warning')
      ], position: ActionsPosition::BeforeColumns)
      ->headerActions([
        ExportAction::make()
          ->exporter(PayrollExporter::class)
          ->columnMapping(false)
          ->formats([
            ExportFormat::Xlsx,
          ])
          ->fileName(fn(Export $export): string => "payroll-{$export->getKey()}")
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          ExportBulkAction::make()
            ->exporter(PayrollExporter::class)
            ->columnMapping(false)
            ->formats([
              ExportFormat::Xlsx,
            ])
            ->fileName(fn(Export $export): string => "payroll-{$export->getKey()}"),
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
      // 'view' => Pages\ViewPayroll::route('/{record}'),
      'edit' => Pages\EditPayroll::route('/{record}/edit'),
    ];
  }
}
