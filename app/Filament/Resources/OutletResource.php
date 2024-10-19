<?php

namespace App\Filament\Resources;

use App\Policies\OutletPolicy;
use Filament\Forms;
use Filament\Tables;
use App\Models\Outlet;
use App\Models\Postcode;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use App\Enums\StoreTypeEnums;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OutletResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OutletResource\RelationManagers;

class OutletResource extends Resource
{
  protected static function getPolicy(): string
  {
    return OutletPolicy::class;
  }
  protected static ?string $model = Outlet::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel = 'Outlet';

  protected static ?string $modelLabel = 'Outlet';

  protected static ?string $navigationGroup = 'System Configs';

  protected static ?string $slug = 'outlets';

  protected static ?int $navigationSort = 3;

  protected static function applyScope(Builder $query): Builder
  {
    $user = auth()->user();
    if (!$user->isAdmin()) {
      return $query->where('department_id', $user->department_id);
    }
    return $query;
  }
  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('department_id')
          ->relationship('department', 'name')
          ->getSearchResultsUsing(function (string $search) {
            return Department::query()
              ->where('id', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->limit(10) // Limit the number of results to avoid performance issues
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
          ->preload()
          ->searchable()
          ->required(),
        Forms\Components\TextInput::make('outlet_sap_id')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('name')
          ->required()
          ->maxLength(255),
        Forms\Components\Select::make('store_type')
          ->label('Store Type')
          ->options(StoreTypeEnums::options())
          ->required(),
        Forms\Components\DatePicker::make('operational_date')
          ->date()
          ->required(),
        Forms\Components\TextInput::make('address')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('phone')
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
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\TextColumn::make('department_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('outlet_sap_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('store_type')
          ->searchable(),
        Tables\Columns\TextColumn::make('operational_date')
          ->searchable(),
        Tables\Columns\TextColumn::make('address')
          ->searchable(),
        Tables\Columns\TextColumn::make('postcode.postal_code')
          ->searchable(),
        Tables\Columns\TextColumn::make('latitude')
          ->searchable(),
        Tables\Columns\TextColumn::make('longitude')
          ->searchable(),
        Tables\Columns\TextColumn::make('phone')
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
      'index' => Pages\ListOutlets::route('/'),
      'create' => Pages\CreateOutlet::route('/create'),
      'edit' => Pages\EditOutlet::route('/{record}/edit'),
    ];
  }
}
