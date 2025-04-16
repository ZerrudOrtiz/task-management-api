<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                RichEditor::make('description')->nullable(),
                DatePicker::make('due_date')->nullable(),
                TextInput::make('order')->required()->integer(),
                Select::make('priority')->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'High' => 'High'
                ])->required(),
                Select::make('status')->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled'
                ])->required(),
                Forms\Components\Select::make('user_id')->label('Assigned To')
                ->required()
                ->options(User::all()->pluck('name','user_id')->map(function ($name) {
                    return ucwords(strtolower($name));
                })->toArray())
                ->searchable()
                ->preload(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_id')->label('ID'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('user_id')->label('Assigned To')        
                ->getStateUsing(function (Task $record): string {

                    $user = User::find($record->user_id);
                    return $user ? ucwords(strtolower($user->name)) : '';
                }),   
                Tables\Columns\TextColumn::make('created_by')->label('Created By')        
                ->getStateUsing(function (Task $record): string {

                    $user = User::find($record->created_by);
                    return $user ? ucwords(strtolower($user->name)) : '';
                }), 
            ])
            ->filters([
                //
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
