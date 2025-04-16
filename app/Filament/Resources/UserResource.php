<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('')
            ->schema([
                Grid::make([
                    'default' => 1
                ])
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('email')
                    ->unique(ignoreRecord: true)
                    ->rules([
                        'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                    ])
                    ->placeholder('@gmail.com')
                    ->required(),
                    TextInput::make('password')
                    ->unique(ignoreRecord: true)
                    ->minValue(12)
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->rules([
                        'regex:/[A-Z]/', // must contain at least one upper-case letter
                        'regex:/[a-z]/', // must contain at least one lower-case letter
                        'regex:/\d/',    // must contain at least one digit
                        'regex:/[@$!%*?&]/', // must contain at least one special character
                    ]),
                    TextInput::make('password_confirmation')->label('Password Confirmation')
                    ->revealable()
                    ->password(),
                ])->columns(2),
                Select::make('roles')
                    ->relationship('roles')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable()->multiple()
            ]) ->extraAttributes(['style' => 'margin: 0 auto; max-width: 45rem; justify-self: center;'])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
