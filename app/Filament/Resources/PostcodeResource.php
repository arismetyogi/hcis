<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostcodeResource\Pages;
use App\Filament\Resources\PostcodeResource\RelationManagers;
use App\Models\Postcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostcodeResource extends Resource
{
  protected static ?string $model = Postcode::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

<<<<<<< HEAD
  protected static ?string $navigationLabel = 'Kode Pos';

  protected static ?string $modelLabel = 'Kode Pos';
=======
  protected static ?string $navigationLabel = 'Post Code';

  protected static ?string $modelLabel = 'Post Codes';
>>>>>>> aaafa088f7a6f3af4a2c8ae6307fe8061fe972bb

  protected static ?string $navigationGroup = 'System Configs';

  protected static ?string $slug = 'postcodes';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('urban')
          ->required()
          ->maxLength(255),
<<<<<<< HEAD
        Forms\Components\TextInput::make('district')
=======
        Forms\Components\TextInput::make('subdistrict')
>>>>>>> aaafa088f7a6f3af4a2c8ae6307fe8061fe972bb
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('city')
          ->required()
          ->maxLength(255),
        Forms\Components\Select::make('province_id')
          ->relationship('province', 'name')
<<<<<<< HEAD
          ->required(),
=======
          ->required()
          ->searchable()
          ->preload(),
>>>>>>> aaafa088f7a6f3af4a2c8ae6307fe8061fe972bb
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
<<<<<<< HEAD
        Tables\Columns\TextColumn::make('urban')
          ->searchable(),
        Tables\Columns\TextColumn::make('district')
=======

        Tables\Columns\TextColumn::make('urban')
          ->searchable(),
        Tables\Columns\TextColumn::make('subdistrict')
>>>>>>> aaafa088f7a6f3af4a2c8ae6307fe8061fe972bb
          ->searchable(),
        Tables\Columns\TextColumn::make('city')
          ->searchable(),
        Tables\Columns\TextColumn::make('province.name')
          ->numeric()
          ->sortable(),
<<<<<<< HEAD
=======
        Tables\Columns\TextColumn::make('postal_code'),
>>>>>>> aaafa088f7a6f3af4a2c8ae6307fe8061fe972bb
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
      'index' => Pages\ListPostcodes::route('/'),
      'create' => Pages\CreatePostcode::route('/create'),
      'edit' => Pages\EditPostcode::route('/{record}/edit'),
    ];
  }
}
