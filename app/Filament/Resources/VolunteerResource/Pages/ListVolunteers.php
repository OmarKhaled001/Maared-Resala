<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use Filament\Actions;
use App\Models\Volunteer;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VolunteerResource;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;
use App\Filament\Resources\VolunteerResource\Widgets\volunteerChart;

class ListVolunteers extends ListRecords
{
    protected static string $resource = VolunteerResource::class;

    protected function getHeaderActions(): array
    {

        return [
            CreateAction::make()
            ->label('إضافة متطوع'),

        ];
        
    }

}
