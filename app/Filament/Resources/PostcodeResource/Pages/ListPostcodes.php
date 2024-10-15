<?php

namespace App\Filament\Resources\PostcodeResource\Pages;

use App\Filament\Resources\PostcodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostcodes extends ListRecords
{
    protected static string $resource = PostcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
