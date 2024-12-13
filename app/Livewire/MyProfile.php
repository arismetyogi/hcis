<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Concerns\EvaluatesClosures;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class MyProfile extends MyProfileComponent
{
    use EvaluatesClosures;

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

    public function getAvatarUploadComponent()
    {
        $fileUpload = FileUpload::make('avatar_url')
            ->label(__('filament-breezy::default.fields.avatar'))->avatar();

        return is_null($this->avatarUploadComponent) ? $fileUpload : $this->evaluate($this->avatarUploadComponent, namedInjections: [
            'fileUpload' => $fileUpload,
        ]);
    }

    public function getProfileFormSchema(): array
    {
        $groupFields = Group::make([
            TextInput::make('name')->required(),
            TextInput::make('email')->required(),
        ])->columnSpan(2);

        return ($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields]
            : [$groupFields] ;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getProfileFormSchema())->columns(3)
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
