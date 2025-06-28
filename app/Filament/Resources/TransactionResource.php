<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    //add to finance menu group
    protected static ?string $navigationGroup = 'Finance';
    //navigation sort
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('order_id')
                    ->maxLength(255),
                Forms\Components\Select::make('billing_id')
                    ->relationship('billing', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing.enrollment.student.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing.enrollment.student.user.name')
                    ->sortable(),
            ])
            ->filters([
                //add status filter
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failure' => 'Failure',
                    ])
                    ->default('paid'),
                //add filter for student name
                Tables\Filters\SelectFilter::make('billing.enrollment.student.name')
                    ->relationship('billing.enrollment.student', 'name')
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
                //add filter for user name
                Tables\Filters\SelectFilter::make('billing.enrollment.student.user.name')
                    ->relationship('billing.enrollment.student.user', 'name')
                    ->label('User Name')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
