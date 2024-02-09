<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\EventResource\Pages\ListEvents;

class EventChart extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListEvents::class;
    }
    protected function getStats(): array
    {
        
        return [
            Stat::make('Total orders', Event::count())->label('إجمالي الاحداث'),
        ];
    }
}
