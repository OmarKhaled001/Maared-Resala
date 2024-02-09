<?php

namespace App\Filament\Resources\VolunteerResource\Widgets;

use App\Models\Volunteer;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\VolunteerResource\Pages\ListVolunteers;

class volunteerChart extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListVolunteers::class;
    }
    protected function getStats(): array
    {
        
        return [
            Stat::make('Total orders', Volunteer::count())->label('إجمالي المتطوعين'),
        ];
    }
}
