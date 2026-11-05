<?php
namespace App\Filament\Resources\Categories;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class CategoryResource extends Resource {
 protected static ?string $model=Category::class;
 protected static ?string $recordTitleAttribute='name';
 protected static ?string $navigationLabel='Categories';
 protected static ?string $modelLabel='category';
 protected static ?string $pluralModelLabel='categories';
 protected static ?string $navigationGroup='Catalog';
 public static function form(Schema $schema): Schema { return $schema->components([
  TextInput::make('name')->required()->maxLength(255),
  TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord:true),
  TextInput::make('sort_order')->numeric()->integer()->minValue(0)->default(0),
  Toggle::make('is_active')->label('Active')->default(true),
  Textarea::make('description')->rows(4)->columnSpanFull(),
 ])->columns(2); }
 public static function table(Table $table): Table { return $table->columns([
  TextColumn::make('name')->searchable()->sortable(), TextColumn::make('slug')->searchable(),
  TextColumn::make('products_count')->counts('products')->label('Products')->sortable(),
  TextColumn::make('sort_order')->sortable(), IconColumn::make('is_active')->boolean()->label('Active'),
 ])->defaultSort('sort_order')->recordActions([\Filament\Actions\EditAction::make(),\Filament\Actions\DeleteAction::make()]); }
 public static function getPages(): array { return ['index'=>ListCategories::route('/'),'create'=>CreateCategory::route('/create'),'edit'=>EditCategory::route('/{record}/edit')]; }
}