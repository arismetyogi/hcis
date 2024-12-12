<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class MyProfile extends MyProfileComponent
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected string $view = 'livewire.my-profile';

    public array $only = ['name','email'];
    public array $data;
    public $user;
    public $userClass;
    public bool $hasAvatars;

    public function mount(): void
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);
        $this->hasAvatars = filament('filament-breezy')->hasAvatars();

        if ($this->hasAvatars) {
            $this->only[] = filament('filament-breezy')->getAvatarUploadComponent()->getStatePath(false);
        }

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                ->required(),
            ])->columns(2)
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title(__('User info updated successfully'))
            ->send();
    }

}
