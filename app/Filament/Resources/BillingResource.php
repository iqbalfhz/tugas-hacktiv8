<?php

namespace App\Filament\Resources;

use App\Filament\Pages\PaymentConfirmation;
use App\Filament\Resources\BillingResource\Pages;
use App\Filament\Resources\BillingResource\RelationManagers;
use App\Models\Billing;
use App\Models\Enrollment;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Billing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    //add to finance menu group
    protected static ?string $navigationGroup = 'Finance';
    //navigation sort
    protected static ?int $navigationSort = 1;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'pay',
        ];
    }
    public static function getModelLabel(): string
    {
        return 'Billing';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Billing')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(1000000)
                            ->placeholder('Enter amount'),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->placeholder('Select date'),
                        RichEditor::make('note')
                            ->label('Note')
                            ->required()
                            ->placeholder('Enter note'),
                        Forms\Components\Select::make('enrollment_id')
                            ->label('Student Enrollment')
                            ->relationship('enrollment', 'id')
                            //show options with student name
                            ->options(
                                Enrollment::with(['student', 'classroom'])->get()->mapWithKeys(function ($enrollment) {
                                    return [$enrollment->id => "{$enrollment->student->name} -{$enrollment->classroom->level->name} - {$enrollment->classroom->name} - {$enrollment->classroom->academicYear->year}"];
                                })
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Select enrollment'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enrollment.id')
                    ->label('Siswa')
                    ->getStateUsing(
                        fn($record) => "{$record->enrollment->student->name} - {$record->enrollment->classroom->level->name} - {$record->enrollment->classroom->name} ({$record->enrollment->classroom->academicYear->year})"
                    )
                    ->numeric()
                    ->sortable(),
                TextColumn::make('enrollment.student.user.name')
                    ->label('Parent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                //if role is not admin, only show billings for the logged in user
                if (auth()->user()->hasRole('super_admin')) {
                    return $query;
                } else {
                    return $query->whereHas('enrollment.student.user', function (Builder $query) {
                        $query->where('user_id', auth()->id());
                    });
                }
            })
            ->filters([
                // //add filter for student name
                Tables\Filters\SelectFilter::make('enrollment.student.name')
                    ->relationship('enrollment.student', 'name')
                    ->label('Student Name')
                    ->searchable()
                    ->options(
                        function (Builder $query) {
                            return $query->get()->mapWithKeys(function ($student) {
                                return [$student->id => $student->name];
                            });
                        }
                    )
                    ->preload()
                    ->placeholder('Select Student'),
                // //add filter for user name
                Tables\Filters\SelectFilter::make('enrollment.student.user.name')
                    ->relationship('enrollment.student.user', 'name')
                    ->label('Parent Name')
                    ->options(
                        function (Builder $query) {
                            return $query->get()->mapWithKeys(function ($user) {
                                return [$user->id => $user->name];
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Select User'),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                //add custom action for midtrans payment
                Tables\Actions\Action::make('Bayar')
                    ->url(fn(Billing $record) => PaymentConfirmation::getUrl([
                        'billing_id' => $record->id,
                    ]))
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn(Billing $record) => auth()->user()->can('pay', $record, Billing::class)),
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
            'index' => Pages\ListBillings::route('/'),
            'create' => Pages\CreateBilling::route('/create'),
            'edit' => Pages\EditBilling::route('/{record}/edit'),
        ];
    }
}
