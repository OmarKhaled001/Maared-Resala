<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Event;
use Filament\Forms\Form;
use App\Models\Volunteer;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Count;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EventResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationLabel = 'الاحداث';

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('volunteer_id')
                ->createOptionForm([
                    TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                    TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->required(),
                    DatePicker::make('birthdate')
                    ->label('تاريخ الميلاد'),
                    DatePicker::make('voldate')
                    ->label('تاريخ التطوع'),
                ])
                ->relationship('volunteers','name')
                ->label('أسم المتطوع')
                ->placeholder('اختر اسماءالمتطوعين')
                ->searchable(['name', 'phone'])
                ->preload()
                ->required(),
                Select::make('type')
                ->options([
                    '1 معرض' => '1 معرض',
                    '2 معرض' => '2 معرض',
                    '3 معرض' => '3 معرض',
                    '4 معرض' => '4 معرض',
                    '5 معرض' => '5 معرض',
                    '6 معرض' => '6 معرض',
                    '7 معرض' => '7 معرض',
                    '8 معرض' => '8 معرض',
                    'عائلي'  => 'عائلي',
                    'اخر'    => 'اخر',
                ])->label('نوع المشاركة')
                ->placeholder('اختر النوع')
                ->required(),
                Textarea::make('note')
                ->label('الملاحظات'),
                SpatieMediaLibraryFileUpload::make('vol_scren')
                ->collection('screns')
                ->multiple()
                ->downloadable()
                ->label('صورة الحدث'),
                Checkbox::make('tshirt')
                ->label('تيشرت رسالة'),
                Checkbox::make('food')
                ->label('وجبة'),
                Checkbox::make('new')
                ->label('جديد'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('volunteers.name')
                ->label('الاسم')
                ->toggleable(isToggledHiddenByDefault: true)
                ->listWithLineBreaks()
                ->limitList(3)
                ->expandableLimitedList(),
                TextColumn::make('volunteers.phone')
                ->label('الرقم')
                ->toggleable(isToggledHiddenByDefault: true)
                ->listWithLineBreaks()
                ->limitList(3)
                ->expandableLimitedList(),
                SpatieMediaLibraryImageColumn::make('vol_scren')
                ->collection('screns')
                ->label('صورة الحدث')
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                ->options([
                    '1 معرض' => '1 معرض',
                    '2 معرض' => '2 معرض',
                    '3 معرض' => '3 معرض',
                    '4 معرض' => '4 معرض',
                    '5 معرض' => '5 معرض',
                    '6 معرض' => '6 معرض',
                    '7 معرض' => '7 معرض',
                    '8 معرض' => '8 معرض',
                    'عائلي'  => 'عائلي',
                    'اخر'    => 'اخر',
                ])->label('نوع المشاركة')
                ->placeholder('اختر النوع')
                ->searchable()
                ->multiple(),
                Filter::make('date')->label('الاجازات')
                ->form([
                    DatePicker::make('created_from')->label('من'),
                    DatePicker::make('created_until')->label('الي'),
                ])->label('التاريخ')
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                        );
                })
            ])
            ->actions([
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
