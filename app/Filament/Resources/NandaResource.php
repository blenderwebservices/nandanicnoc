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
                Forms\Components\Section::make('Classification & Metadata')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('class_id')
                                    ->relationship('nandaClass', 'name')
                                    ->searchable()
                                    ->required()
                                    ->label('Class'),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('approval_year')
                                    ->numeric(),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('evidence_level'),
                                Forms\Components\TextInput::make('year_revised'),
                                Forms\Components\TextInput::make('mesh_term'),
                            ]),
                    ]),

                Forms\Components\Tabs::make('Translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Spanish (ES)')
                            ->schema([
                                Forms\Components\TextInput::make('label_es')
                                    ->required()
                                    ->label('Name (ES)'),
                                Forms\Components\Textarea::make('description_es')
                                    ->label('Definition (ES)')
                                    ->rows(3),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('focus_es')->label('Focus (ES)'),
                                        Forms\Components\TextInput::make('judgment_es')->label('Judgment (ES)'),
                                        Forms\Components\TextInput::make('diagnosis_status_es')->label('Status (ES)'),
                                    ]),
                                Forms\Components\TagsInput::make('defining_characteristics_es')
                                    ->label('Defining Characteristics (ES)'),
                                Forms\Components\TagsInput::make('related_factors_es')
                                    ->label('Related Factors (ES)'),
                                Forms\Components\TagsInput::make('risk_factors_es')
                                    ->label('Risk Factors (ES)'),
                                Forms\Components\TagsInput::make('at_risk_population_es')
                                    ->label('At Risk Population (ES)'),
                                Forms\Components\TagsInput::make('associated_conditions_es')
                                    ->label('Associated Conditions (ES)'),
                            ]),
                        Forms\Components\Tabs\Tab::make('English (EN)')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->label('Name (EN)'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Definition (EN)')
                                    ->rows(3),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('focus')->label('Focus (EN)'),
                                        Forms\Components\TextInput::make('judgment')->label('Judgment (EN)'),
                                        Forms\Components\TextInput::make('diagnosis_status')->label('Status (EN)'),
                                    ]),
                                Forms\Components\TagsInput::make('defining_characteristics')
                                    ->label('Defining Characteristics (EN)'),
                                Forms\Components\TagsInput::make('related_factors')
                                    ->label('Related Factors (EN)'),
                                Forms\Components\TagsInput::make('risk_factors')
                                    ->label('Risk Factors (EN)'),
                                Forms\Components\TagsInput::make('at_risk_population')
                                    ->label('At Risk Population (EN)'),
                                Forms\Components\TagsInput::make('associated_conditions')
                                    ->label('Associated Conditions (EN)'),
                            ]),
                    ])
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
