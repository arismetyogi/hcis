<?php

namespace App\Filament\Resources;

use App\Models\Outlet;
use App\Traits\FiltersByDepartment;
use Filament\Actions\Exports\Enums\ExportFormat;
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
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
    use FiltersByDepartment;
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    //global search

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->npp . ' - ' . $record->first_name . ' ' . $record->last_name;
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['npp', 'first_name', 'last_name', 'sap_id'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Outlet' => $record->outlet->outlet_sap_id . ' - ' . $record->outlet->name,
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        // Get the current user's department ID
        $userDepartmentId = Auth::user()->department_id;

        $user = Auth::user();
        if ($user->is_admin) {
            // If admin, count records across all departments
            return static::getModel()::count();
        }

        // If Not Admin, Count the records filtered by the user's department
        return static::getModel()::where('department_id', $userDepartmentId)->count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
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
                                            ->placeholder('Nama Depan Sesuai KTP')
                                            ->maxLength(255)
                                            ->dehydrateStateUsing(function ($state) {
                                                // Format the state to Proper Case
                                                return ucwords(strtolower($state));
                                            }),
                                        Forms\Components\TextInput::make('last_name')
                                            ->label('Nama Belakang')
                                            ->placeholder('Nama Belakang Sesuai KTP')
                                            ->maxLength(255)
                                            ->dehydrateStateUsing(function ($state) {
                                                // Format the state to Proper Case
                                                return ucwords(strtolower($state));
                                            }),
                                        Forms\Components\TextInput::make('nik')
                                            ->label('NIK')
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->type('text')
                                            ->maxLength(16)
                                            ->reactive()
                                            ->placeholder('19001030102200001')
                                            ->rules('string|max:16|min:16')
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                // Ensure only numeric values remain
                                                $set('nik', preg_replace('/\D/', '', $state));
                                            }),
                                        Forms\Components\TextInput::make('npwp')
                                            ->label('NPWP')
                                            ->unique(ignoreRecord: true)
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
                                            ->displayFormat('d/m/Y')
                                            ->reactive()
                                            ->afterStateUpdated(fn($state, $set) =>
                                            $set('npp', Carbon::parse($state)->format('Ymd')))
                                            ->required(),
                                        Forms\Components\TextInput::make('phone_no')
                                            ->label('No Telp.')
                                            ->required()
                                            ->tel()
                                            ->prefix('+62')
                                            ->placeholder('81234567890')
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
                                        Forms\Components\Select::make('blood_type')
                                            ->label('Golongan Darah')
                                            ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'])
                                            ->required(),
                                        Forms\Components\Select::make('religion')
                                            ->label('Agama')
                                            ->options(['Islam' => 'Islam', 'Katolik' => 'Katolik', 'Protestan' => 'Protestan', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghuchu' => 'Konghuchu', 'Lainnya' => 'Lainnya'])
                                            ->required(),
                                        Forms\Components\TextInput::make('address')
                                            ->label('Alamat')
                                            ->required()
                                            ->placeholder('Jl. Budi Utomo No. 1, Pasar Baru')
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
                                            ->label('Unit Bisnis')
                                            ->relationship('department', 'name')
                                            ->disabled(fn($livewire) => !Auth::user()->is_admin)
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
                                            ->searchable()
                                            ->live()
                                            ->preload()
                                            ->afterStateUpdated(fn(Set $set) => $set('outlet_id', null))
                                            ->required(),
                                        Forms\Components\Select::make('outlet_id')
                                            ->label('Unit Kerja/Outlet')
                                            ->options(
                                                fn(Get $get) => Outlet::query()
                                                    ->where('department_id', $get('department_id'))
                                                    ->pluck('name', 'id')
                                            )
                                            ->live()
                                            ->preload()
                                            ->searchable()
                                            ->required(),
                                        TextInput::make('sap_id')
                                            ->label('ID SAP')
                                            ->unique(ignoreRecord: true)
                                            ->type('text')
                                            ->maxLength(13)
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                // Ensure only numeric values remain
                                                $set('sap_id', preg_replace('/\D/', '', $state));
                                            })
                                            ->rules(['required', 'regex:/^(\d{8}|\d{13})$/']),
                                        Forms\Components\TextInput::make('npp')
                                            ->label('NPP')
                                            ->unique(ignoreRecord: true)
                                            ->live()
                                            ->type('text')
                                            ->maxLength(10)
                                            ->placeholder('19990101A')
                                            ->rules(['regex:/^\d{8}[A-Z]{1,2}$/'])
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
                                            ->label('Kode Jab SAP')
                                            ->unique(ignoreRecord: true)
                                            ->type('text')
                                            ->maxLength(50)
                                            ->required(),
                                        Forms\Components\TextInput::make('saptitle_name')
                                            ->label('Nama Jab SAP')
                                            ->placeholder('PELAKSANA PENUNJANG LAYANAN FARMASI')
                                            ->required()
                                            ->columnSpan(2)
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('date_hired')
                                            ->label('Tanggal Mulai Bekerja')
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
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        Forms\Components\TextInput::make('bpjs_id')
                                            ->label('No BPJS')
                                            ->unique(ignoreRecord: true)
                                            ->placeholder('0001234567890')
                                            ->required()
                                            ->type('text')
                                            ->reactive()
                                            ->maxLength(13)
                                            ->rules('string|max:13|min:13')
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                // Ensure only numeric values remain
                                                $set('bpjs_id', preg_replace('/\D/', '', $state));
                                            }),
                                        Forms\Components\TextInput::make('bpjs_class')
                                            ->label('Kelas BPJS')
                                            ->integer()
                                            ->minValue(1)
                                            ->maxValue(3)
                                            ->required()
                                            ->rules(['integer', 'min:1', 'max:3']),
                                        Forms\Components\TextInput::make('insured_member_count')
                                            ->label('Jumlah Tanggungan')
                                            ->integer()
                                            ->minValue(0)
                                            ->maxValue(4)
                                            ->required()
                                            ->rules(['integer', 'min:0', 'max:4']),
                                        Forms\Components\TextInput::make('bpjstk_id')
                                            ->label('No BPJSTK')
                                            ->unique(ignoreRecord: true)
                                            ->placeholder('0001234567890')
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
                            ->description('Wajib diisi untuk Pegawai PKWTT')
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
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('contract_sequence_no')
                                            ->label('Kontrak Ke-')
                                            ->integer()
                                            ->minValue(1)
                                            ->maxValue(3)
                                            ->rules(['integer', 'min:1', 'max:3']),
                                        Forms\Components\TextInput::make('contract_term')
                                            ->label('Masa Kontrak')
                                            ->integer()
                                            ->minValue(1)
                                            ->maxValue(5)
                                            ->rules(['integer', 'min:1', 'max:5']),
                                        Forms\Components\DatePicker::make('contract_start')
                                            ->label('Mulai Kontrak'),
                                        Forms\Components\DatePicker::make('contract_end')
                                            ->label('Berakhir Kontrak'),
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
                                            ->unique(ignoreRecord: true)
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
                                            ->maxLength(255)
                                            ->dehydrateStateUsing(function ($state) {
                                                // Format the state to Proper Case
                                                return ucwords(strtolower($state));
                                            }),
                                        Forms\Components\Select::make('recruitment_id')
                                            ->label('Ket Rekrutmen')
                                            ->relationship('recruitment', 'name'),
                                        Forms\Components\Select::make('pants_size')
                                            ->label('Ukuran Celana')
                                            ->required()
                                            ->options(function (callable $get) {
                                                $sex = $get('sex');

                                                $options = [];
                                                // Return different options based on the selected sex
                                                if ($sex === 'male') {
                                                    // Generate options for male (e.g., 28 - 40 inches)
                                                    for ($i = 28; $i <= 45; $i += 1) {
                                                        $options[$i] = "{$i} Inch";
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
                                            ->options(['XS' => 'XS', 'S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL', '3XL' => '3XL', '4XL' => '4XL', '5XL' => '5XL', '6XL+' => '6XL+'])
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
                    ->label('NPP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sap_id')
                    ->label('ID SAP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nama Lengkap')
                    ->getStateUsing(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_no')
                    ->label('No Telp')
                    ->getStateUsing(function ($record) {
                        return '+62' . $record->phone_no; // Concatenate prefix with the phone number
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('outlet.name')
                    ->label('Unit Kerja')
                    ->getStateUsing(fn($record) => $record->outlet->outlet_sap_id . ' - ' . $record->outlet->name)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Unit Bisnis')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sex')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama')
                    ->getStateUsing(function ($record) {
                        return $record->religion;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('blood_type')
                    ->label('Golongan Darah')
                    ->getStateUsing(function ($record) {
                        return $record->blood_type;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('postcode_id')
                    ->label('Kode Pos')
                    ->getStateUsing(function ($record) {
                        return $record->postcode_id;
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employee_status.name')
                    ->label('Status Pegawai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title.name')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subtitle.name')
                    ->label('Subjabatan')
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
                    ->label('Level Pegawai')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('saptitle_name')
                    ->label('Nama Jab SAP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->label('Tanggal Direkrut')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_promoted')
                    ->label('Tanggal Diangkat')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_last_mutated')
                    ->label('Mutasi Terakhir')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('descstatus.name')
                    ->label('Deksripsi Status')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bpjs_id')
                    ->label('No BPJS')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('insured_member_count')
                    ->label('Jumlah Tanggungan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bpjs_class')
                    ->label('Kelas BPJS')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bpjstk_id')
                    ->label('No BPJSTK')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contract_document_id')
                    ->label('No Kontrak')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rekening_no')
                    ->label('No Rekening')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rekening_name')
                    ->label('Nama Rekening')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bank.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('recruitment.name')
                    ->label('Ket Rekrutmen')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pants_size')
                    ->label('Ukuran Celana')
                    ->getStateUsing(function ($record) {
                        // Check the sex of the record
                        if ($record->sex === 'male') {
                            // Define the male size scale (28 to 45 inches)
                            $maleSizes = [];
                            for ($i = 28; $i <= 45; $i++) {
                                $maleSizes[$i] = "{$i} Inch";
                            }
                            return $maleSizes[$record->pants_size] ?? 'Not Specified';
                        } elseif ($record->sex === 'female') {
                            // Define the female size scale (UK sizes 6 to 20)
                            $femaleSizes = [];
                            for ($i = 6; $i <= 20; $i++) {
                                $femaleSizes[$i] = "UK {$i}";
                            }
                            return $femaleSizes[$record->pants_size] ?? 'Not Specified';
                        }

                        return 'Not Specified'; // Default if sex is not defined
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shirt_size')
                    ->label('Ukuran Baju')
                    ->getStateUsing(function ($record) {
                        return $record->shirt_size;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn($livewire) => Auth::user()->is_admin)
                    ->label('Unit Bisnis'),
                SelectFilter::make('outlet_id')
                    ->relationship('outlet', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Unit Kerja')
                    ->options(function (callable $get) {
                        $departmentId = $get('department_id');  // Retrieves selected department ID
                        return Outlet::where('department_id', $departmentId)
                            ->pluck('name', 'id');
                    }),
                SelectFilter::make('Jabatan')
                    ->relationship('title', 'name'),
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
                    ->exporter(EmployeeExporter::class)
                    ->columnMapping(false)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn(Export $export): string => "data-pegawai-{$export->getKey()}")
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(EmployeeExporter::class)
                        ->columnMapping(false)
                        ->formats([
                            ExportFormat::Xlsx,
                        ])
                        ->fileName(fn(Export $export): string => "data-pegawai-{$export->getKey()}"),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl('');
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
