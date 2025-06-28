<?php

namespace App\Filament\Pages;

use App\Models\Classroom;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class EnrollmentPage extends Page
{
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.enrollment-page';

    //add to navigation group academic settings
    protected static ?string $navigationGroup = 'Academic Settings';
    protected static ?string $navigationLabel = 'Enrollment';

    //add navigation sort 6
    protected static ?int $navigationSort = 6;


    public $data = [];
    public $classroomId = null;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('classroom_id')
                ->label('Select Classroom')
                ->options(Classroom::with(['level', 'academicYear'])->get()->mapWithKeys(function ($classroom) {
                    return [$classroom->id => "{$classroom->level->name} {$classroom->name} ({$classroom->academicYear->year})"];
                }))
                ->placeholder('Select a classroom')
                ->searchable()
                ->getSearchResultsUsing(function (string $query) {
                    return DB::table('classrooms')
                        ->join('levels', 'classrooms.level_id', '=', 'levels.id')
                        ->join('academic_years', 'classrooms.academic_year_id', '=', 'academic_years.id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('levels.name', 'like', "%{$query}%")
                                ->orWhere('classrooms.name', 'like', "%{$query}%")
                                ->orWhere('academic_years.year', 'like', "%{$query}%");
                        })
                        ->select(
                            'classrooms.id',
                            DB::raw("CONCAT(levels.name, ' ', classrooms.name, ' (', academic_years.year, ')') as display_name")
                        )
                        ->get()
                        ->pluck('display_name', 'id');
                })
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->classroomId = $state;
                    $this->dispatch('classroom-changed', classroomId: $state);
                })
                ->columnSpanFull(),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
