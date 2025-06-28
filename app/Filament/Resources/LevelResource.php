<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Filament\Resources\LevelResource\RelationManagers;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    //change navigation label
    protected static ?string $navigationGroup = 'Academic Settings';
    protected static ?string $navigationLabel = 'Levels';
    //navigation sort
    protected static ?int $navigationSort = 2;


    public static function getModelLabel(): string
    {
        return 'Level';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Level')
                    ->description('Input level or grade of the student here')
                    ->icon('heroicon-o-numbered-list')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Level')
                            ->placeholder('Enter Level')
                            ->maxLength(1)
                            ->integer()
                            ->autofocus()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
