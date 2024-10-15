<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
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
                    Forms\Components\TextInput::make('NIK')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('first_name')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('middle_name')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('city_of_birth')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\DatePicker::make('date_of_birth')
                      ->required(),
                    Forms\Components\TextInput::make('phone_no')
                      ->tel()
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('sex')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('zip_code')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('npwp')
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
                    Forms\Components\TextInput::make('department_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('payroll_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('employee_status')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('title_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('subtitle_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('band')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('outlet_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('npp')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('gradeeselon_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('area_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('emplevel_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('saptitle_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\DatePicker::make('date_hired')
                      ->required(),
                    Forms\Components\DatePicker::make('date_promoted')
                      ->required(),
                    Forms\Components\DatePicker::make('date_last_mutated')
                      ->required(),
                    Forms\Components\TextInput::make('descstatus_id')
                      ->required()
                      ->numeric(),
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
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('insured_member_count')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('bpjs_class')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('bpjstk_id')
                      ->required()
                      ->numeric(),
                  ])
              ]),
            Forms\Components\Section::make()
              ->schema([
                Forms\Components\Grid::make()
                  ->columns([
                    'default' => 2,
                    'md' => 3,
                    'lg' => 3,
                  ])
                  ->schema([
                    Forms\Components\TextInput::make('contract_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('tax_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('honorarium')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('rekening_no')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('rekening_name')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('bank_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('recruitment_id')
                      ->required()
                      ->numeric(),
                    Forms\Components\TextInput::make('pants_size')
                      ->required()
                      ->maxLength(255),
                    Forms\Components\TextInput::make('shirt_size')
                      ->required()
                      ->maxLength(255),
                  ])
              ])
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
