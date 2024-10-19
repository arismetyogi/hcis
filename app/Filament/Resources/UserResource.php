<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Department;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';

  protected static ?string $navigationLabel = 'Users';

  protected static ?string $modelLabel = 'Users';

  protected static ?string $navigationGroup = 'User Configs';

  protected static ?string $slug = 'users';

  protected static ?int $navigationSort = 1;
  protected function getFormSchema(): array
  {
    return [
      Select::make('department_id')
        ->label('Department')
        ->options(Department::all()->pluck('name', 'id'))
        ->required()
        ->visible(fn($livewire) => auth()->user()->isAdmin()),
      TextInput::make('name')->required(),
      TextInput::make('email')->email()->required(),
      TextInput::make('password')->required(),
    ];
  }

  // public static function form(Form $form): Form
  // {
  //   return $form
  //     ->schema([
  //       Forms\Components\TextInput::make('name')
  //         ->label('Nama User')
  //         ->required()
  //         ->maxLength(255),
  //       Forms\Components\TextInput::make('email')
  //         ->email()
  //         ->required()
  //         ->maxLength(255),
  //       Forms\Components\TextInput::make('password')
  //         ->password()
  //         ->required()
  //         ->maxLength(255),
  //       Forms\Components\Toggle::make('is_admin'),
  //       Forms\Components\Select::make('department_id')
  //         ->relationship('department', 'name')
  //         ->getSearchResultsUsing(function (string $search) {
  //           return Department::query()
  //             ->where('name', 'like', "%{$search}%")
  //             ->limit(10) // Limit the number of results to avoid performance issues
  //             ->get()
  //             ->mapWithKeys(function ($department) {
  //               return [
  //                 $department->id => "{$department->name}",
  //               ];
  //             });
  //         })
  //         ->getOptionLabelUsing(
  //           fn($value) => optional(Department::find($value))->name
  //         )
  //         ->searchable()
  //         ->preload()
  //         ->required(),
  //     ]);
  // }

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
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email_verified_at')
          ->dateTime()
          ->sortable(),
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
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'view' => Pages\ViewUser::route('/{record}'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
