<?php

namespace App\Filament\Resources\PostcodeResource\Pages;

use App\Filament\Resources\PostcodeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostcode extends CreateRecord
{
  protected static string $resource = PostcodeResource::class;

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
}
