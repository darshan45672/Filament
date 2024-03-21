<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Create Post')
                ->description('Create a new post')
                ->collapsible()
                ->schema([
                    TextInput::make('title')->label('Title')->required(),
                    TextInput::make('slug')->unique(ignoreRecord:true)->label('Slug')->required(),

                    // Select::make('category_id')->label('Category')->options(
                    //     fn() => Category::all()->pluck('name', 'id')
                    // )->required(),
                    // or 
                    // Select::make('category_id')->label('Category')->relationship('category','name')->required(),

                    ColorPicker::make('color')->label('Color')->required(),

                    MarkdownEditor::make('content')->label('Content')->required()->columnSpan('full'),
                ])->columns(2)->columnSpan(1),

            Section::make('Post Detials')
                ->description('Add post details')
                ->collapsible()
                ->schema([
                    FileUpload::make('thumbnail')->label('Image')->required()->disk('public')->directory('thumbnails'),

                    TagsInput::make('tags')->label('Tags')->required(),
                    Checkbox::make('is_published')->label('Is Published'),
                ])->columns(1)->columnSpan(1),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                TextColumn::make('slug'),
                CheckboxColumn::make('is_published'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
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
}
