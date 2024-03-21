<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Faker\Core\Color;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
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
                        Select::make('category_id')->label('Category')->relationship('category','name')->required(),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')->label('Image'),
                ColorColumn::make('color')->label('Color')->sortable(),
                TextColumn::make('title')->label('Title')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category')->searchable()->sortable(),
                TextColumn::make('tags')->label('Tags')->searchable(),
                CheckboxColumn::make('is_published')->label('Is Published')->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
