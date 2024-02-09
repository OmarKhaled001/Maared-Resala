<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Volunteer;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use function Laravel\Prompts\text;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Symfony\Component\Console\Input\Input;
use Filament\Forms\Components\DateTimePicker;

use App\Filament\Resources\VolunteerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\VolunteerResource\RelationManagers;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use App\Filament\Resources\VolunteerResource\RelationManagers\EventsRelationManager;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;
    protected static ?string $navigationLabel = 'المتطوعـين';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form

                ->schema([
                    
                    Section::make('البيانات الاساسية')
                    ->schema([
                        TextInput::make('name')
                        ->label('الاسم')
                        ->placeholder('ادخل اسم المتطوع')
                        ->required()
                        ->columnSpan(1),
                        TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->placeholder('ادخل رقم المتطوع')
                        ->required()
                        ->columnSpan(1),
                        DatePicker::make('birthdate')
                        ->displayFormat('d/m/y')
                        ->label('تاريخ الميلاد'),
                        DatePicker::make('voldate')
                        ->displayFormat('d/m/y')
                        ->label('تاريخ التطوع'),
                    ])->columnSpan( ['sm' => 1,'lg' => 2,]),
                    Section::make('بيانات اضافية')
                    ->schema([
                        Select::make('status')
                        ->options([
                            'مسئول' => 'مسئول',
                            'مشروع مسئول' => 'مشروع مسئول',
                            'داخل المتابعة' => 'داخل المتابعة',
                            'أشبال' => 'أشبال',
                        ])->label('التصنيف')
                        ->placeholder('اختر التصنيف'),
                        Textarea::make('note')
                        ->label('الملاحظات'),
                        Checkbox::make('meni_camp')
                        ->label('مني كامب'),
                        Checkbox::make('tshirt')
                        ->label('تيشرت رسالة'),
                    ])->columnSpan( ['sm' => 1,'lg' => 2,]),
                    Section::make('الصور')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('vol_images')
                        ->collection('volunteers')
                        ->multiple()
                        ->label('صورة شخصية'),
                        SpatieMediaLibraryFileUpload::make('vol_card')
                        ->collection('cards')
                        ->multiple()
                        ->label('البطاقة الشخصية'),
                    ])->columnSpan( ['sm' => 1,'lg' => 4,]),
            ])->columns([
                'sm' => 1,
                'lg' => 4,
            ]);


            

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('الاسم')
                ->searchable()
                ->copyable()
                ->sortable(),
                TextColumn::make('phone')
                ->label('رقم الهاتف')
                ->searchable()
                ->copyable(),
                TextColumn::make('status')
                ->label('التصنيف')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'شبل' => 'warning',
                    'داخل المتابعة' => 'warning',
                    'مشروع مسئول' => 'success',
                    'مسئول' => 'success',
                    'خارج المتابعة' => 'danger',
                })
                ->sortable(),
                TextColumn::make('age')
                ->label('العمر')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                TextColumn::make('events_count')
                ->counts('events')
                ->label('العدد')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('events.date')
                ->label('المشاركات الاخيرة')
                ->toggleable(isToggledHiddenByDefault: true)
                ->listWithLineBreaks()
                ->limitList(3)
                ->copyable()
                ->expandableLimitedList(),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'مسئول' => 'مسئول',
                    'مشروع مسئول' => 'مشروع مسئول',
                    'داخل المتابعة' => 'داخل المتابعة',
                    'أشبال' => 'أشبال',
                ])->label('التصنيف')
                ->placeholder('اختر التصنيف')
                ->multiple(),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            EventsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
        ];
    }

}
