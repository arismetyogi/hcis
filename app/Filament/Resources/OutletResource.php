<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletResource\Pages;
use App\Filament\Resources\OutletResource\RelationManagers;
use App\Models\Outlet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutletResource extends Resource
{
  protected static ?string $model = Outlet::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('branch_id')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('branch_name')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('outlet_sap_id')
          ->required()
          ->maxLength(255),
        Forms\Components\Select::make('store_type')
          ->label('Store Type')
          ->options(Outlet::all()->pluck('type_store', 'outlet_id'))
          ->required(),
        Forms\Components\DatePicker::make('operational_date')
          ->format('d/m/Y')
          ->required(),
        Forms\Components\TextInput::make('address')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('post_code')
          ->integer()
          ->required()
          ->maxLength(5),
        Forms\Components\TextInput::make('district_name')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('outlet_sap_id')
          ->required()
          ->maxLength(255),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\TextColumn::make('branch_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('branch_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('outlet_sap_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('store_type')
          ->searchable(),
        Tables\Columns\TextColumn::make('operational_date')
          ->searchable(),
        Tables\Columns\TextColumn::make('address')
          ->searchable(),
        Tables\Columns\TextColumn::make('post_code')
          ->searchable(),
        Tables\Columns\TextColumn::make('district_name')
          ->searchable(),
        Tables\Columns\TextColumn::make('city_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('state_id')
          ->searchable(),
        Tables\Columns\TextColumn::make('latitude')
          ->searchable(),
        Tables\Columns\TextColumn::make('longitude')
          ->searchable(),
        Tables\Columns\TextColumn::make('phone_no')
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
