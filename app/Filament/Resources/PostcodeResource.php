<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostcodeResource\Pages;
use App\Filament\Resources\PostcodeResource\RelationManagers;
use App\Models\Postcode;
use App\Policies\PostcodePolicy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostcodeResource extends Resource
{
  protected static function getPolicy(): string
  {
    return PostcodePolicy::class;
  }
  protected static ?string $model = Postcode::class;

  protected static ?string $navigationIcon = 'heroicon-o-map-pin';

  protected static ?string $navigationLabel = 'Post Code';

  protected static ?string $modelLabel = 'Post Codes';

  protected static ?string $navigationGroup = 'System Configs';

  protected static ?string $slug = 'postcodes';

  protected static ?int $navigationSort = 1;

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
        Forms\Components\Select::make('province_code')
          ->relationship('province', 'name')
          ->required()
          ->searchable()
          ->preload(),
        Forms\Components\TextInput::make('city')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('subdistrict')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('urban')
          ->required()
          ->maxLength(255),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([

        Tables\Columns\TextColumn::make('province.name_en')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('city')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('subdistrict')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('urban')
          ->searchable(),
        Tables\Columns\TextColumn::make('postal_code')
          ->label('Zip Code')
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
        ActionGroup::make([
          ViewAction::make(),
          EditAction::make(),
          DeleteAction::make(),
        ])->icon('heroicon-m-ellipsis-horizontal')->color('warning')
      ], position: ActionsPosition::BeforeColumns)
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
      'index' => Pages\ListPostcodes::route('/'),
      'create' => Pages\CreatePostcode::route('/create'),
      'edit' => Pages\EditPostcode::route('/{record}/edit'),
    ];
  }
}
