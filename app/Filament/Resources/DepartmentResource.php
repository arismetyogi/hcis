<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use App\Policies\DepartmentPolicy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class DepartmentResource extends Resource
{
  protected static function getPolicy(): string
  {
    return DepartmentPolicy::class;
  }
  protected static ?string $model = Department::class;

  protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

  protected static ?string $navigationLabel = 'Unit Bisnis';

  protected static ?string $modelLabel = 'Unit Bisnis';

  protected static ?string $navigationGroup = 'System Configs';

  protected static ?string $slug = 'employees-departments';

  protected static ?int $navigationSort = 3;

  public static function getNavigationBadge(): ?string
  {
    return   static::getModel()::count();
  }
  public static function getNavigationBadgeColor(): string|array|null
  {
    return 'warning';
  }
  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->label('Nama Unit Bisnis')
          ->unique(ignoreRecord: true)
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('id')
          ->label('Kode Unit Bisnis')
          ->unique(ignoreRecord: true)
          ->required(),
        Forms\Components\TextInput::make('branch_name')
          ->label('Nama BM')
          ->required(),
      ])
      ->columns([
        'default' => 3,
        'md' => 2,
        'lg' => 3
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('Kode UB')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Unit Bisnis')
          ->sortable()
          ->searchable(),
        // Tables\Columns\TextColumn::make('branch_name')
        //   ->label('Nama BM')
        //   ->sortable()
        // ->searchable(),
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
      ])
      // ->disableDefaultAction()
      // ->rowUrl(fn($record) => route('filament.admin.resources.employees-departments.view', $record))
    ;
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
      'index' => Pages\ListDepartments::route('/'),
      'create' => Pages\CreateDepartment::route('/create'),
      // 'view' => Pages\ViewDepartment::route('/{record}'),
      'edit' => Pages\EditDepartment::route('/{record}/edit'),
    ];
  }
}
