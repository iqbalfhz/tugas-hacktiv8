<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseSessionResource\Pages;
use App\Filament\Resources\CourseSessionResource\RelationManagers;
use App\Models\Classroom;
use App\Models\CourseSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseSessionResource extends Resource
{
    protected static ?string $model = CourseSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    //add to learning activities group
    protected static ?string $navigationGroup = 'Learning Activities';
    protected static ?string $navigationLabel = 'Course Sessions';
    //add navigation sorting
    protected static ?int $navigationSort = 1;


    //get model label
    public static function getModelLabel(): string
    {
        return 'Course Session';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Course and Classroom Details')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->relationship('course', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('classroom_id')
                            ->relationship('classroom', 'name')
                            ->options(Classroom::with(['level', 'academicYear'])->get()->mapWithKeys(function ($classroom) {
                                return [$classroom->id => "{$classroom->level->name} {$classroom->name} ({$classroom->academicYear->year})"];
                            }))
                            // ->getSearchResultsUsing(function (string $query) {
                            //     return Classroom::with(['level', 'academicYear'])
                            //         ->whereHas('level', function ($q) use ($query) {
                            //             $q->where('name', 'like', "%{$query}%");
                            //         })
                            //         ->orWhere('name', 'like', "%{$query}%")
                            //         ->orWhereHas('academicYear', function ($q) use ($query) {
                            //             $q->where('year', 'like', "%{$query}%");
                            //         })
                            //         ->get()
                            //         ->mapWithKeys(function ($classroom) {
                            //             return [
                            //                 $classroom->id => "{$classroom->level->name} - {$classroom->name} - {$classroom->academicYear->year}",
                            //             ];
                            //         });
                            // })
                            // ->getOptionLabelUsing(function ($value) {
                            //     $classroom = Classroom::with(['level', 'academicYear'])->find($value);
                            //     return $classroom
                            //         ? "{$classroom->level->name} - {$classroom->name} - {$classroom->academicYear->year}"
                            //         : null;
                            // })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Forms\Components\Section::make('Course Session Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Learning Topics')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('session_date')
                            ->required(),
                        Forms\Components\RichEditor::make('session_note')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->html(),
                Tables\Columns\TextColumn::make('session_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('session_note')
                    ->html(),
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('Classroom')
                    ->formatStateUsing(function ($state, $record) {
                        return "{$record->classroom->level->name} - {$record->classroom->name} - {$record->classroom->academicYear->year}";
                    })
                    ->sortable(),
            ])
            ->filters([], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                //add custom action to go to AssessmentPage
                Tables\Actions\Action::make('Go to Assessment')
                    ->url(fn(CourseSession $record): string => url('/assessment-page?session_id=' . $record->id))
                    ->icon('heroicon-o-arrow-right')
                    ->color('success'),
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
            'index' => Pages\ListCourseSessions::route('/'),
            'create' => Pages\CreateCourseSession::route('/create'),
            'edit' => Pages\EditCourseSession::route('/{record}/edit'),
        ];
    }
}
