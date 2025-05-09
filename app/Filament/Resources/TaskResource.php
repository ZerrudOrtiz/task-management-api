<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
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
                TextInput::make('title')   
                ->minLength(3)
                ->maxLength(100)
                ->unique(ignoreRecord: true)
                ->required(),
                RichEditor::make('description')->required(),
                DatePicker::make('due_date')->nullable(),
                TextInput::make('order')->integer(),
                Select::make('priority')->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'High' => 'High'
                ])->required(),
                Select::make('status')->options([
                    'todo' => 'To Do',
                    'in_progress' => 'In Progress',
                    'done' => 'Done',
                ])->required(),
                Checkbox::make('visible'),
                Forms\Components\Select::make('user_id')->label('Assigned To')
                ->required()
                ->options(User::all()->pluck('name','user_id')->map(function ($name) {
                    return ucwords(strtolower($name));
                })->toArray())
                ->searchable()
                ->preload(),
                FileUpload::make('attachments')
                ->maxSize(4096)
                ->downloadable()
                ->openable()
                ->image()
                ->imageEditor()
                ->disk('public')
                ->visibility('public')
                ->directory('tasks/attachments')

            ]);
    }

    public static function table(Table $table): Table
    {
        $user_id = auth()->id();
        return $table
            ->query(Task::query()->where('user_id', $user_id))
            ->columns([
                Tables\Columns\TextColumn::make('task_id')->label('ID'),
                Tables\Columns\TextColumn::make('title')->label('Title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match($state) {
                    'todo' => 'warning',
                    'done' => 'success',
                    'in_progress' => 'danger'
                }),
                Tables\Columns\TextColumn::make('user_id')->label('Assigned To')        
                ->getStateUsing(function (Task $record): string {

                    $user = User::find($record->user_id);
                    return $user ? ucwords(strtolower($user->name)) : '';
                }),   
                Tables\Columns\ImageColumn::make('attachments')
                ->square()
                ->stacked(),
                Tables\Columns\ToggleColumn::make('visible'),
                Tables\Columns\TextColumn::make('created_by')->label('Created By')        
                ->getStateUsing(function (Task $record): string {

                    $user = User::find($record->created_by);
                    return $user ? ucwords(strtolower($user->name)) : '';
                }), 
                Tables\Columns\TextColumn::make('created_at')->label('Created Date')->sortable(), 
                
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'todo' => 'To Do',
                    'in_progress' => 'In Progress',
                    'done' => 'Done',
                ])
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
