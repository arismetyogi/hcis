<?php

namespace App\Filament\Resources;

use App\Models\Outlet;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Employee;
use App\Models\Postcode;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use App\Models\Gradeeselon;
use Filament\Resources\Resource;
use App\Enums\MalePantsSizeEnums;
use App\Enums\StatusPasanganEnums;
use Illuminate\Support\Collection;
use App\Enums\FemalePantsSizeEnums;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Filament\Forms\Set;

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
                    Forms\Components\TextInput::make('last_name')
                      ->label('Nama Belakang')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('nik')
                      ->label('NIK')
                      ->unique()
                      ->required()
                      ->type('text')
                      ->maxLength(16)
                      ->reactive()
                      ->placeholder('19001030102200001')
                      // ->rules(['numeric', 'digits:16'])
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('nik', preg_replace('/\D/', '', $state));
                      }),
                    Forms\Components\TextInput::make('npwp')
                      ->label('NPWP')
                      ->unique()
                      ->required()
                      ->type('text')
                      ->maxLength(16)
                      ->reactive()
                      // ->rules(['numeric', 'digits:16'])
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('npwp', preg_replace('/\D/', '', $state));
                      }),
                    Forms\Components\DatePicker::make('date_of_birth')
                      ->label('Tanggal Lahir')
                      ->required(),
                    Forms\Components\TextInput::make('phone_no')
                      ->label('No Telp.')
                      ->tel()
                      ->required()
                      ->placeholder('081234567890')
                      ->maxLength(15),
                    Forms\Components\Select::make('sex')
                      ->label('Jenis Kelamin')
                      ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                      ])
                      ->required()
                      ->live()
                      ->afterStateUpdated(function ($set, $state) {
                        // Reset the 'pants_size' field when 'sex' changes
                        $set('pants_size', null);
                      }),
                    Forms\Components\TextInput::make('address')
                      ->label('Alamat')
                      ->required()
                      ->maxLength(255)
                      ->columnSpan(2),
                    Forms\Components\Select::make('postcode_id')
                      ->label('Kode Pos')
                      ->relationship('postcode', 'postal_code')
                      ->getSearchResultsUsing(function (string $search) {
                        return Postcode::query()
                          ->where('postal_code', 'like', "%{$search}%")
                          ->orWhere('urban', 'like', "%{$search}%")
                          ->orWhere('subdistrict', 'like', "%{$search}%")
                          ->limit(10) // Limit the number of results to avoid performance issues
                          ->get()
                          ->mapWithKeys(function ($postcode) {
                            return [
                              $postcode->id => "{$postcode->postal_code} - {$postcode->urban}, {$postcode->subdistrict}"
                            ];
                          });
                      })
                      // ->getOptionLabelUsing(
                      //   fn($value) => optional(Postcode::find($value))
                      //     ->postal_code . ' - ' . optional(Postcode::find($value))
                      //     ->urban . ' - ' . optional(Postcode::find($value))
                      //     ->subdistrict
                      // )
                      ->searchable()
                      ->nullable(),
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
                      ->default(auth()->user()->department_id)
                      ->disabled(!auth()->user()->is_admin())
                      // ->getSearchResultsUsing(function (string $search) {
                      //   return Department::query()
                      //     ->where('name', 'like', "%{$search}%")
                      //     ->limit(10) // Limit the number of results to avoid performance issues
                      //     ->get()
                      //     ->mapWithKeys(function ($department) {
                      //       return [
                      //         $department->id => "{$department->department_id}",
                      //       ];
                      //     });
                      // })
                      // ->getOptionLabelUsing(
                      //   fn($value) => optional(Department::find($value))->name
                      // )
                      ->searchable()
                      ->live()
                      ->preload()
                      ->afterStateUpdated(fn(Set $set) => $set('outlet_id', null))
                      ->required(),
                    Forms\Components\Select::make('outlet_id')
                      ->label('Nama Outlet')
                      ->options(
                        fn(Get $get) => Outlet::query()
                          ->where('department_id', $get('department_id'))
                          ->pluck('name', 'id')
                      )
                      ->live()
                      ->preload()
                      ->searchable()
                      ->required(),
                    Forms\Components\TextInput::make('npp')
                      ->label('NPP')
                      ->unique()
                      ->type('text')
                      ->maxLength(9)
                      ->placeholder('19990101A')
                      ->rules(['regex:/^\d{8}[A-Z]$/'])
                      ->required(),
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
                    Forms\Components\Select::make('band_id')
                      ->label('Nama Band')
                      ->relationship('band', 'name')
                      ->required(),
                    Forms\Components\Select::make('gradeeselon_id')
                      ->label('Grade Eselon')
                      ->relationship('gradeeselon', 'id')
                      ->getOptionLabelFromRecordUsing(fn($record) => "{$record->grade} - {$record->eselon}")
                      ->preload()
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
                      ->unique()
                      ->type('text')
                      ->required(),
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
                      ->unique()
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('insured_member_count')
                      ->label('Jumlah Tanggungan')
                      ->minValue(0)
                      ->maxValue(4)
                      ->required()
                      ->default(0)
                      ->rules(['integer', 'min:0', 'max:4']),
                    Forms\Components\TextInput::make('bpjs_class')
                      ->label('Kelas BPJS')
                      ->minValue(0)
                      ->maxValue(3)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\TextInput::make('bpjstk_id')
                      ->label('No BPJSTK')
                      ->unique()
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
                    Forms\Components\TextInput::make('contract_document_id')
                      ->label('No Kontrak')
                      ->unique()
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
                      ->label('Status Pasangan Pajak')
                      ->options(StatusPasanganEnums::options())
                      ->required(),
                    Forms\Components\TextInput::make('jumlah_tanggungan')
                      ->label('Jumlah Tanggungan Pajak')
                      ->integer()
                      ->minValue(0)
                      ->maxValue(3)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\Select::make('pasangan_ditanggung_pajak')
                      ->label('Pasangan Ditanggung Pajak')
                      ->boolean()
                      ->required(),
                    Forms\Components\Select::make('bank_id')
                      ->label('Nama Bank')
                      ->relationship('bank', 'name')
                      ->required(),
                    Forms\Components\TextInput::make('rekening_no')
                      ->label('No. Rekening Payroll')
                      ->unique()
                      ->required()
                      ->type('text')
                      ->maxLength(10)
                      ->placeholder('Masukkan No. Rekening')
                      ->rule(['regex:/^[0-9]+$/', 'max:10']),
                    Forms\Components\TextInput::make('rekening_name')
                      ->label('Nama Pemilik Rekening')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\Select::make('recruitment_id')
                      ->relationship('recruitment', 'name')
                      ->required(),
                    Forms\Components\Select::make('pants_size')
                      ->label('Ukuran Celana')
                      ->options(function (callable $get) {
                        $sex = $get('sex');

                        $options = [];
                        // Return different options based on the selected sex
                        if ($sex === 'male') {
                          // Generate options for male (e.g., 28 - 40 inches)
                          for ($i = 28; $i <= 45; $i += 1) {
                            $options[$i] = "{$i} Inch           ";
                          }
                        } elseif ($sex === 'female') {
                          // Generate options for female (e.g., UK sizes 6 - 18)
                          for ($i = 6; $i <= 20; $i += 1) {
                            $options[$i] = "UK {$i}";
                          }
                        }

                        return $options;
                      })
                      ->reactive(),
                    Forms\Components\Select::make('shirt_size')
                      ->label('Ukuran Baju')
                      ->options(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL+'])
                      ->required(),
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
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('last_name')
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
        Tables\Columns\TextColumn::make('departments.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('NIK')
          ->searchable(),
        Tables\Columns\TextColumn::make('npwp')
          ->searchable(),
        Tables\Columns\TextColumn::make('employee_statuses.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('titles.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('subtitles.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('bands.name')
          ->searchable(),
        Tables\Columns\TextColumn::make('outlets.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('npp')
          ->searchable(),
        Tables\Columns\TextColumn::make('gradeeselon_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('emplevel_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('saptitle_name')
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
        Tables\Columns\TextColumn::make('descstatuses.name')
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
        // Tables\Columns\TextColumn::make('honorarium')
        //   ->numeric()
        //   ->sortable(),
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
      ->query(function ($query) {
        $user = auth()->user();

        // If the user is not an admin, scope by department
        if (!$user->hasRole('admin')) {
          return $query->where('department_id', $user->department_id);
        }

        return $query;
      })
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
