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
                DatePicker::make('date')
                ->label('التاريخ'),
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
                ->label('المتطوعين')
                ->placeholder('اختر اسماءالمتطوعين')
                ->searchable(['name', 'phone'])
                ->multiple()
                ->preload()
                ->maxItems(10)
                ->required(),
                Select::make('type')
                ->options([
                    'اطعام' => 'اطعام',
                    'معرض' => 'معرض',
                    'اتصالات' => 'اتصالات',
                    'دعاية' => 'دعاية',
                    'اجتماع' => 'اجتماع',
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
                TextInput::make('tshirt')
                ->numeric()
                ->label('تيشرت رسالة'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                ->label('التاريخ')
                ->date('d-m'),
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
                TextColumn::make('type')
                ->label('المشاركة')
                ->searchable(),
                TextColumn::make('volunteers_count')
                ->counts('volunteers')
                ->label('العدد'),
                SpatieMediaLibraryImageColumn::make('vol_scren')
                ->collection('screns')
                ->label('صورة الحدث')
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                ->options([
                    'اطعام' => 'اطعام',
                    'معرض' => 'معرض',
                    'اتصالات' => 'اتصالات',
                    'دعاية' => 'دعاية',
                    'اجتماع' => 'اجتماع',
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
