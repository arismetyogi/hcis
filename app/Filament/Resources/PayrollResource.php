<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
        Forms\Components\Select::make('employee_id')
          ->relationship('employees', 'npp'),
        Forms\Components\TextInput::make('1050_honorarium')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('uang_saku_mb')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('3000_lembur')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('2580_tunj_lain')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('ujp')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('4020_sumbangan_cuti_tahunan')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6500_pot_wajib_koperasi')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6540_pot_pinjaman_koperasi')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6590_pot_ykkkf')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6620_pot_keterlambatan')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6630_pinjaman_karyawan')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6700_pot_bank_mandiri')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6701_pot_bank_bri')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6702_pot_bank_btn')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6703_pot_bank_danamon')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6704_pot_bank_dki')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6705_pot_bank_bjb')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6750_pot_adm_bank_mandiri')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6751_pot_adm_bank_bri')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6752_pot_adm_bank_bjb')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('6900_pot_lain')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('bln_thn')
          ->required()
          ->maxLength(255),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('1050_honorarium')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('uang_saku_mb')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('3000_lembur')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('2580_tunj_lain')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('ujp')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('4020_sumbangan_cuti_tahunan')
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
        Tables\Columns\TextColumn::make('bln_thn')
          ->searchable(),
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
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
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
