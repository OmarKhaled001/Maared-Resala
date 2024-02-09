<?php

namespace App\Filament\Resources\EventResource\Pages;

use Filament\Actions;
use Filament\Tables\Columns\Column;
use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Resources\EventResource\Widgets\EventChart;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة حدث'),
            ExportAction::make() 
            ->label('إستخراج ملف')
            ->exports([
                ExcelExport::make()
                    ->fromTable()
                    ->withFilename(fn ($resource) => $resource::getLabel() . '-' . date('Y-m-d'))
                    ->withWriterType(\Maatwebsite\Excel\Excel::XLS)
                    ->rtl()
                    
            ]),

        ];
    }

}
