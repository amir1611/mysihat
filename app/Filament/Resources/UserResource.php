<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'User';

    protected static ?string $pluralLabel = 'User Management';

    public static function getEloquentQuery(): Builder
    {

        return User::query()->where('name', '!=', 'Admin')->orderBy('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->rules(fn($context) => [
                                $context === 'edit' ? 'nullable' : 'unique:users,email',
                            ])
                            ->email()
                            ->required()
                            ->disabled(fn($context) => $context === 'edit'),

                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Phone Number')
                                    ->prefix('+60')
                                    ->length(10)
                                    ->tel()
                                    ->required()
                                    ->columns(2),
                            ])->columns(2),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required()
                            ->maxDate(now()),


                        Forms\Components\TextInput::make('ic_number')
                            ->label('IC Number')
                            ->required()
                            ->unique('users', 'ic_number', ignoreRecord: true)
                            ->maxLength(255)
                            ->rules(fn($context) => [
                                $context === 'create' ? 'required' : 'nullable',
                            ])
                            ->disabledOn('edit'),

                        Forms\Components\Radio::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                // 010806070043

                Forms\Components\Section::make('Role & Password Details')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name', function ($query) {
                                $query->where('name', '!=', 'super_admin')
                                    ->where('name', '!=', 'client');
                            })
                            ->required()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => ucwords(str_replace('_', ' ', $record->name))),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255)
                            ->rules(fn($context) => [
                                $context === 'edit' ? 'nullable' : 'unique:users,email',
                            ])
                            ->visibleOn('create'),

                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Medical Details')
                    ->schema([
                        Forms\Components\TextInput::make('medical_license_number')
                            ->label('Medical License Number')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('expertise')
                            ->label('Expertise')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('medical_license_document')
                            ->label('Medical License Document'),
                    ])
                    ->columns(2)
                    ->collapsible(),


                // Forms\Components\TextInput::make('name')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('email')
                //     ->email()
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                // Forms\Components\TextInput::make('password')
                //     ->password()
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('gender')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('date_of_birth')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('phone_number')
                //     ->tel()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('medical_license_number')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('ic_number')
                //     ->maxLength(255),
                // Forms\Components\Select::make('roles')
                //     ->label('Role')
                //     ->relationship('roles', 'name', function ($query) {
                //         $query->where('name', '!=', 'super_admin')
                //             ->where('name', '!=', 'client');
                //     })
                //     ->required()
                //     ->preload()
                //     ->searchable()
                //     ->getOptionLabelFromRecordUsing(fn (Model $record) => ucwords(str_replace('_', ' ', $record->name))),
                // Forms\Components\TextInput::make('expertise')
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('medical_license_document')
                //     ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medical_license_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ic_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expertise')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('medical_license_document')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
