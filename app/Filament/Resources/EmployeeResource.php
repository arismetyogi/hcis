<?php

namespace App\Filament\Resources;

use App\Models\Outlet;
use App\Traits\FiltersByDepartment;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Employee;
use App\Models\Postcode;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Enums\StatusPasanganEnums;
use App\Filament\Exports\EmployeeExporter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
  use FiltersByDepartment;
  protected static ?string $model = Employee::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';

  protected static ?string $recordTitleAttribute = 'first_name'; //global search

  public static function getGlobalSearchResultTitle(Model $record): string
  {
    return $record->first_name . ' ' . $record->last_name;
  }
  public static function getGloballySearchableAttributes(): array
  {
    return ['npp', 'first_name', 'last_name'];
  }

  public static function getGlobalSearchResultDetails(Model $record): array
  {
    return [
      'NPP' => $record->npp,
      'Outlet' => $record->outlet->outlet_sap_id . ' - ' . $record->outlet->name,
    ];
  }
  public static function getEloquentQuery(): Builder
  {
    return self::scopeForUserDepartment(parent::getEloquentQuery());
  }

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
            Section::make('Employees\' Personal Information')
              ->schema([
                Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    TextInput::make('first_name')
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
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('npwp', preg_replace('/\D/', '', $state));
                      }),
                    Forms\Components\DatePicker::make('date_of_birth')
                      ->label('Tanggal Lahir')
                      ->required(),
                    Forms\Components\TextInput::make('phone_no')
                      ->label('No Telp.')
                      ->required()
                      ->tel()
                      ->prefix('+62')
                      // ->telRegex('/^[+]*[(]{0,2}[0-9]{2,4}[)]{0,1}[-\s\.\/0-9]*$/')
                      ->maxLength(13),
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
                      ->disabled(fn($livewire) => !Auth::user()->is_admin)
                      //   // ->disabled(!Auth::user()->is_admin)
                      ->default(Auth::user()->department_id)
                      ->getSearchResultsUsing(function (string $search) {
                        return Department::query()
                          ->where('name', 'like', "%{$search}%")
                          ->orWhere('id', 'like', "%{$search}%")
                          ->limit(10) // Limit the number of results to avoid performance issues
                          ->get()
                          ->mapWithKeys(function ($department) {
                            return [
                              $department->id => "{$department->department_id}",
                            ];
                          });
                      })
                      // ->getOptionLabelUsing(
                      //   fn($value) => optional(Department::find($value))->id . ' - ' . optional(Department::find($value))->name
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
                      ->type('text')
                      ->maxLength(16)
                      ->reactive()
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('bpjs_id', preg_replace('/\D/', '', $state));
                      }),
                    Forms\Components\TextInput::make('insured_member_count')
                      ->label('Jumlah Tanggungan')
                      ->integer()
                      ->minValue(0)
                      ->maxValue(4)
                      ->required()
                      ->default(0)
                      ->rules(['integer', 'min:0', 'max:4']),
                    Forms\Components\TextInput::make('bpjs_class')
                      ->label('Kelas BPJS')
                      ->integer()
                      ->minValue(0)
                      ->maxValue(3)
                      ->required()
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\TextInput::make('bpjstk_id')
                      ->label('No BPJSTK')
                      ->unique()
                      ->required()
                      ->type('text')
                      ->maxLength(16)
                      ->reactive()
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('bpjstk_id', preg_replace('/\D/', '', $state));
                      }),
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
                      ->integer()
                      ->minValue(0)
                      ->maxValue(3)
                      ->rules(['integer', 'min:0', 'max:3']),
                    Forms\Components\TextInput::make('contract_term')
                      ->label('Masa Kontrak')
                      ->integer()
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
                      ->maxLength(16)
                      ->reactive()
                      ->afterStateUpdated(function (callable $set, $state) {
                        // Ensure only numeric values remain
                        $set('rekening_no', preg_replace('/\D/', '', $state));
                      }),
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
        Tables\Columns\TextColumn::make('npp')
          ->searchable(),
        // Tables\Columns\TextColumn::make('first_name')
        //   ->searchable()
        //   ->sortable(),
        // Tables\Columns\TextColumn::make('last_name')
        //   ->searchable(),
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Lengkap')
          ->getStateUsing(function ($record) {
            return $record->first_name . ' ' . $record->last_name;
          })
          ->searchable(),
        Tables\Columns\TextColumn::make('date_of_birth')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('phone_no')
          ->getStateUsing(function ($record) {
            return '+62' . $record->phone_no; // Concatenate prefix with the phone number
          })
          ->searchable(),
        Tables\Columns\TextColumn::make('outlet.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('department.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('sex')
          ->searchable(),
        Tables\Columns\TextColumn::make('address')
          ->label('Alamat')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('nik')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('npwp')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('employee_status.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('title.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('subtitle.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('band.name')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('gradeeselon_id')
          ->label('Grade - Eselon')
          ->getStateUsing(function ($record) {
            return $record->gradeeselon->grade . '-' . $record->gradeeselon->eselon;
          })
          ->sortable(),
        Tables\Columns\TextColumn::make('emplevel.name')
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
        Tables\Columns\TextColumn::make('descstatus.name')
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjs_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('insured_member_count')
          ->label('Jumlah Tanggungan')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjs_class')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('bpjstk_id')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('contract_document_id')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('rekening_no')
          ->searchable(),
        Tables\Columns\TextColumn::make('rekening_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('bank.name')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('recruitment.name')
          ->numeric()
          ->sortable(),
        Tables\Columns\TextColumn::make('pants_size')
          ->getStateUsing(function ($record) {
            return $record->pants_size;
          })
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
        SelectFilter::make('Unit Kerja')
          ->relationship('department', 'name')
          ->searchable()
          ->preload()
          ->indicator('Unit Kerja'),
        SelectFilter::make('Jabatan')
          ->relationship('title', 'name'),
      ])
      ->actions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make(),
      ])
      ->headerActions([
        ExportAction::make()
          ->exporter(EmployeeExporter::class)
          ->columnMapping(false)
      ])
      ->bulkActions([
        BulkActionGroup::make([
          ExportBulkAction::make()
            ->exporter(EmployeeExporter::class)
            ->columnMapping(false),
          DeleteBulkAction::make(),
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
      // 'view' => Pages\ViewEmployee::route('/{record}'),
      'edit' => Pages\EditEmployee::route('/{record}/edit'),
    ];
  }
}
