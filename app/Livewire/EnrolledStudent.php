<?php

namespace App\Livewire;

use App\Models\Enrollment;
use App\Models\Student;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable; // Corrected import
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Component;

class EnrolledStudent extends Component implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $classroomId = null;

    protected $listeners = [
        'classroom-changed' => 'updateClassroom',
        'student-enrolled' => '',
    ];

    public function updateClassroom($classroomId)
    {
        $this->classroomId = $classroomId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => Enrollment::query()->where('classroom_id', $this->classroomId))
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(fn($record) => \Carbon\Carbon::parse($record->student->date_of_birth)->age),
                TextColumn::make('student.sex'),
                TextColumn::make('student.user.name')
                    ->label('Parent')
                    ->sortable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('remove')
                    ->label('Remove')
                    ->action(function ($record) {
                        $record->delete();
                    })
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.enrolled-student');
    }
}
