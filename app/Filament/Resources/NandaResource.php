<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NandaResource\Pages;
use App\Filament\Resources\NandaResource\RelationManagers;
use App\Models\Nanda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NandaResource extends Resource
{
    protected static ?string $model = Nanda::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->relationship('nandaClass', 'name')
                    ->createOptionForm([
                        Forms\Components\Select::make('domain_id')
                            ->relationship('domain', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('code')->unique()->required(),
                                Forms\Components\TextInput::make('name')->required(),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('code')->required(),
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\Textarea::make('definition')->required(),
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('label')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nandaClass.domain.name')
                    ->label('Domain')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nandaClass.name')
                    ->label('Class')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_id')
                    ->relationship('nandaClass', 'name')
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
            'index' => Pages\ListNandas::route('/'),
            'create' => Pages\CreateNanda::route('/create'),
            'edit' => Pages\EditNanda::route('/{record}/edit'),
        ];
    }
}
