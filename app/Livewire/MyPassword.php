<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class MyPassword extends MyProfileComponent
{
    protected string $view = 'livewire.my-password';

    public ?array $data = [];

    public $user;

    public static $sort = 20;

    public function mount(): void
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('current_password')
                    ->label(__('Current Password'))
                    ->required()
                    ->password()
                    ->rule('current_password')
                    ->visible(filament('filament-breezy')->getPasswordUpdateRequiresCurrent()),
               TextInput::make('new_password')
                    ->label(__('New Password'))
                    ->password()
                    ->rules(filament('filament-breezy')->getPasswordUpdateRules())
                    ->required(),
               TextInput::make('new_password_confirmation')
                    ->label(__('New Password Confirmation'))
                    ->password()
                    ->same('new_password')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only('new_password')->all();
        $this->user->update([
            'password' => Hash::make($data['new_password']),
        ]);
        session()->forget('password_hash_'.Filament::getCurrentPanel()->getAuthGuard());
        Filament::auth()->login($this->user);
        $this->reset(['data']);
        Notification::make()
            ->success()
            ->title(__('Password changed successfully!'))
            ->send();
    }
}
