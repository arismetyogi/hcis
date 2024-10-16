<?php

namespace App\Filament\Resources;

use App\Enums\FemalePantsSizeEnums;
use App\Enums\MalePantsSizeEnums;
use App\Enums\StatusPasanganEnums;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Gradeeselon;
use App\Models\Postcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
  protected static ?string $model = Employee::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Grid::make()
          ->columns([
            'default' => 2,
            'md' => 3,
            'lg' => 3,
          ])
          ->schema([
            Forms\Components\Section::make('Employees\' Personal Information')
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\TextInput::make('first_name')
                      ->label('Nama Depan')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('middle_name')
                      ->label('Nama Tengah')
                      ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                      ->label('Nama Belakang')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('nik')
                      ->label('NIK')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\DatePicker::make('date_of_birth')
                      ->label('Tanggal Lahir')
                      ->required(),
                    Forms\Components\TextInput::make('phone_no')
                      ->label('No Telp.')
                      ->tel()
                      ->required()
                      ->maxLength(15),
                    Forms\Components\Select::make('sex')
                      ->label('Jenis Kelamin')
                      ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                      ])
                      ->required()
                      ->afterStateUpdated(function ($set, $state) {
                        // Reset the 'pants_size' field when 'sex' changes
                        $set('pants_size', null);
                      }),
                    Forms\Components\TextInput::make('address')
                      ->label('Alamat')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\Select::make('postcode_id')
                      ->label('Kode Pos')
                      ->relationship('postcode', 'postal_code')
                      ->getSearchResultsUsing(function (string $search) {
                        return Postcode::query()
                          ->where('postal_code', 'like', "%{$search}%")
                          ->orWhere('urban', 'like', "%{$search}%")
                          ->orWhere('subdistrict', 'like', "%{$search}%")
                          ->limit(50) // Limit the number of results to avoid performance issues
                          // ->pluck('postal_code', 'id');
                          ->get()
                          ->mapWithKeys(function ($postcode) {
                            return [
                              $postcode->id => "{$postcode->postal_code} - {$postcode->urban} - {$postcode->subdistrict}",
                            ];
                          });
                      })
                      ->getOptionLabelUsing(
                        fn($value) => optional(Postcode::find($value))
                          ->postal_code . ' - ' . optional(Postcode::find($value))
                          ->urban . ' - ' . optional(Postcode::find($value))
                          ->subdistrict
                      )
                      ->searchable(),
                    Forms\Components\TextInput::make('npwp')
                      ->label('NPWP')
                      ->required()
                      ->maxLength(255),
                  ])
              ]),
            Forms\Components\Section::make('Employees\' Job Information')
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\Select::make('department_id')
                      ->label('Unit Kerja - UB')
                      ->relationship('department', 'name')
                      ->getSearchResultsUsing(function (string $search) {
                        return Department::query()
                          ->where('name', 'like', "%{$search}%")
                          ->limit(50) // Limit the number of results to avoid performance issues
                          ->get()
                          ->mapWithKeys(function ($department) {
                            return [
                              $department->id => "{$department->name}",
                            ];
                          });
                      })
                      ->getOptionLabelUsing(
                        fn($value) => optional(Department::find($value))->name
                      )
                      ->searchable()
                      ->required(),
                    Forms\Components\Select::make('outlet_id')
                      ->label('Nama Outlet')
                      ->relationship('outlet', 'name')
                      ->required(),
                    Forms\Components\TextInput::make('npp')
                      ->label('NPP')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\Select::make('employee_status_id')
                      ->label('Status Pegawai')
                      ->relationship('employee_status', 'name')
                      ->required(),
                    Forms\Components\Select::make('title_id')
                      ->label('Nama Jabatan')
                      ->relationship('title', 'name')
                      ->required(),
                    Forms\Components\Select::make('subtitle_id')
                      ->label('Nama SubJabatan')
                      ->relationship('subtitle', 'name')
                      ->required(),
                    Forms\Components\Select::make('band')
                      ->label('Nama Band')
                      ->relationship('band', 'name')
                      ->required(),
                    Forms\Components\Select::make('gradeeselon_id')
                      ->label('Grade Eselon')
                      ->relationship('gradeeselon', 'id')
                      ->getSearchResultsUsing(function (string $search) {
                        return Gradeeselon::query()
                          ->where('grade', 'like', "%{$search}%")
                          ->orWhere('eselon', 'like', "%{$search}%")
                          ->limit(10) // Limit the number of results to avoid performance issues
                          ->get()
                          ->mapWithKeys(function ($grade) {
                            return [
                              $grade->id => "{$grade->grade} - {$grade->eselon}"
                            ];
                          });
                      })
                      ->getOptionLabelUsing(
                        fn($value) => optional(Gradeeselon::find($value))->grade . ' - ' . optional(value: Gradeeselon::find($value))->eselon
                      )
                      ->required(),
                    Forms\Components\Select::make('area_id')
                      ->label('Area')
                      ->relationship('area', 'name')
                      ->required(),
                    Forms\Components\Select::make('emplevel_id')
                      ->label('Level Pegawai')
                      ->relationship('emplevel', 'name')
                      ->required(),
                    Forms\Components\TextInput::make('saptitle_id')
                      ->label('ID Jab SAP')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('saptitle_name')
                      ->label('Nama Jab SAP')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\DatePicker::make('date_hired')
                      ->label('Tanggal Diterima')
                      ->required(),
                    Forms\Components\DatePicker::make('date_promoted')
                      ->label('Tanggal Diangkat')
                      ->required(),
                    Forms\Components\DatePicker::make('date_last_mutated')
                      ->label('Tanggal Mutasi Terakhir')
                      ->required(),
                    Forms\Components\Select::make('descstatus_id')
                      ->label('Deskripsi Status')
                      ->relationship('descstatus', 'name')
                      ->required(),
                  ])
              ]),
            Forms\Components\Section::make('Insurance Information')
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\TextInput::make('bpjs_id')
                      ->label('No BPJS')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('insured_member_count')
                      ->label('Jumlah Tanggungan')
                      ->minValue(0)
                      ->maxValue(4)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:4']),
                    Forms\Components\TextInput::make('bpjs_class')
                      ->label('Kelas BPJS')
                      ->minValue(0)
                      ->maxValue(3)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\TextInput::make('bpjstk_id')
                      ->label('No BPJSTK')
                      ->required()
                      ->numeric(),
                  ])
              ]),
            Forms\Components\Section::make('Contract Info')
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\TextInput::make('contract_id')
                      ->label('No Kontrak')
                      ->maxLength(50),
                    Forms\Components\TextInput::make('contract_sequence_no')
                      ->label('Kontrak Ke-')
                      ->minValue(0)
                      ->maxValue(3)
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\TextInput::make('contract_term')
                      ->label('Masa Kontrak')
                      ->minValue(0)
                      ->maxValue(5)
                      ->rules(['integer', 'min:0', 'max:5']),
                    Forms\Components\DatePicker::make('contract_start')
                      ->label('Tanggal Mulai'),
                    Forms\Components\DatePicker::make('contract_end')
                      ->label('Tanggal Berakhir'),

                  ])
              ]),
            Forms\Components\Section::make('Tax & Honorary Info')
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\Select::make('status_pasangan')
                      ->label('Status Pasangan')
                      ->options(StatusPasanganEnums::options())
                      ->required(),
                    Forms\Components\TextInput::make('jumlah_tanggungan')
                      ->label('Jumlah Tanggungan')
                      ->integer()
                      ->minValue(0)
                      ->maxValue(3)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\Select::make('pasangan_ditanggung_pajak')
                      ->label('Pasangan Ditanggung Pajak')
                      ->boolean()
                      ->required(),
                    Forms\Components\TextInput::make('rekening_no')
                      ->label('No. Rekening Payroll')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('rekening_name')
                      ->label('Nama Pemilik Rekening')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\Select::make('bank_id')
                      ->relationship('bank', 'name')
                      ->required(),
                    Forms\Components\Select::make('recruitment_id')
                      ->relationship('recruitment', 'name')
                      ->required(),
                    Forms\Components\Select::make('pants_size')
                      ->label('Ukuran Celana')
                      ->options(function (callable $get) {
                        $sex = $get('sex');
                        // Return different options based on the selected sex
                        if ($sex === 'male') {
                          return collect(MalePantsSizeEnums::cases())
                            ->mapWithKeys(fn($size) => [$size->value => $size->label()])
                            ->toArray();
                        }
                        if ($sex === 'female') {
                          return collect(FemalePantsSizeEnums::cases())
                            ->mapWithKeys(fn($size) => [$size->value => $size->label()])
                            ->toArray();
                        }
                        return [];
                      })
                      ->reactive(),
                    Forms\Components\TextInput::make('shirt_size')
                      ->required()
                      ->maxLength(255),
                  ])
              ]),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('first_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('middle_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('last_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('city_of_birth')
          ->searchable(),
        Tables\Columns\TextColumn::make('date_of_birth')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('phone_no')
          ->searchable(),
        Tables\Columns\TextColumn::make('sex')
          ->searchable(),
        Tables\Columns\TextColumn::make('address')
          ->searchable(),
        Tables\Columns\TextColumn::make('zip_code')
          ->searchable(),
        Tables\Columns\TextColumn::make('department_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('payroll_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('NIK')
          ->searchable(),
        Tables\Columns\TextColumn::make('npwp')
          ->searchable(),
        Tables\Columns\TextColumn::make('employee_status')
          ->searchable(),
        Tables\Columns\TextColumn::make('title_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('subtitle_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('band')
          ->searchable(),
        Tables\Columns\TextColumn::make('outlet_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('npp')
          ->searchable(),
        Tables\Columns\TextColumn::make('gradeeselon_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('area_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('emplevel_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('saptitle_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('date_hired')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('date_promoted')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('date_last_mutated')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('descstatus_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjs_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('insured_member_count')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjs_class')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjstk_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('contract_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('tax_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('honorarium')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('rekening_no')
          ->searchable(),
        Tables\Columns\TextColumn::make('rekening_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('bank_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('recruitment_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('pants_size')
          ->searchable(),
        Tables\Columns\TextColumn::make('shirt_size')
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
      'index' => Pages\ListEmployees::route('/'),
      'create' => Pages\CreateEmployee::route('/create'),
      'view' => Pages\ViewEmployee::route('/{record}'),
      'edit' => Pages\EditEmployee::route('/{record}/edit'),
    ];
  }
}
