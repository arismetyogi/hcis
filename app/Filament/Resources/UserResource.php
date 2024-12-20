<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use App\Policies\UserPolicy;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
  protected static function getPolicy(): string
  {
    return UserPolicy::class;
  }
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';

  protected static ?string $navigationLabel = 'Users';

  protected static ?string $modelLabel = 'Users';

  protected static ?string $navigationGroup = 'User Configs';

  protected static ?string $slug = 'users';

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
        Forms\Components\TextInput::make('name')
          ->label('Nama User')
          ->placeholder('Nama User')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('email')
          ->email()
          ->placeholder('user@gmail.com')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('password')
          ->password()
          ->revealable()
          ->required()
          ->maxLength(255),
        Forms\Components\Select::make('department_id')
          ->label('Unit Kerja - UB')
          ->relationship('department', 'name')
          ->options(Department::all()->pluck('name', 'id'))
          ->preload()
          ->searchable()
          ->required(),
        Forms\Components\Toggle::make('is_admin'),
      ])
      ->columns([
        'default' => 2,
        'md' => 2,
        'lg' => 2
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\TextColumn::make('email')
          ->searchable(),
        Tables\Columns\ToggleColumn::make('is_admin')
          ->sortable(),
        Tables\Columns\TextColumn::make('department.name')
          ->label('Unit Kerja')
          ->getStateUsing(function ($record) {
            return $record->department->id . '-' . $record->department->name;
          })
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email_verified_at')
          ->dateTime()
          ->sortable()
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
      ])
        ->recordUrl('');
  }


  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      // 'view' => Pages\ViewUser::route('/{record}'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
