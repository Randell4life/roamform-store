<?php
namespace App\Filament\Resources\Products;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class ProductResource extends Resource {
 protected static ?string $model=Product::class;
 protected static ?string $recordTitleAttribute='name';
 protected static ?string $navigationLabel='Products';
 protected static ?string $modelLabel='product';
 protected static ?string $pluralModelLabel='products';
 protected static string|\UnitEnum|null $navigationGroup='Catalog';
 public static function form(Schema $schema): Schema { return $schema->components([
  TextInput::make('name')->label('Product name')->required()->maxLength(255),
  TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord:true),
  Select::make('category_id')->relationship('category','name')->searchable()->preload()->nullable(),
  TextInput::make('sku')->label('SKU')->maxLength(255)->unique(ignoreRecord:true),
  TextInput::make('price')->numeric()->prefix('$')->required()->minValue(0),
  TextInput::make('compare_at_price')->label('Compare-at price')->numeric()->prefix('$')->minValue(0),
  FileUpload::make('image_path')->label('Product image')->image()->disk('public')->directory('products')->visibility('public')->imageEditor()->maxSize(5120),
  TextInput::make('image_url')->label('Fallback image URL')->url()->maxLength(2048),
  Textarea::make('short_description')->label('Short description')->rows(2)->maxLength(500)->columnSpanFull(),
  Textarea::make('description')->label('Full description')->rows(8)->columnSpanFull(),
  TextInput::make('stock_quantity')->label('Stock quantity')->numeric()->integer()->minValue(0)->default(0)->required(),
  TextInput::make('low_stock_threshold')->label('Low-stock alert at')->numeric()->integer()->minValue(0)->default(5)->required(),
  TextInput::make('supplier')->label('Supplier')->maxLength(255),
  TextInput::make('supplier_product_id')->label('Supplier product ID')->maxLength(255),
  TextInput::make('supplier_url')->label('Supplier product URL')->url()->maxLength(2048),
  Toggle::make('is_active')->label('Published on storefront')->default(false),
 ])->columns(2); }
 public static function table(Table $table): Table { return $table->columns([
  ImageColumn::make('image_path')->disk('public')->square(),
  TextColumn::make('name')->searchable()->sortable()->limit(40),
  TextColumn::make('category.name')->label('Category')->placeholder('Uncategorized')->sortable(),
  TextColumn::make('sku')->searchable()->placeholder('—'),
  TextColumn::make('price')->formatStateUsing(fn($state): string => '$'.number_format((float)$state,2))->sortable(),
  TextColumn::make('stock_quantity')->label('Stock')->sortable()->badge()->color(fn(Product $record): string => $record->is_low_stock?'danger':'success'),
  TextColumn::make('supplier')->placeholder('—')->toggleable(),
  IconColumn::make('is_active')->label('Published')->boolean(),
  TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault:true),
 ])->defaultSort('created_at','desc')->recordActions([\Filament\Actions\EditAction::make(),\Filament\Actions\DeleteAction::make()]); }
 public static function getPages(): array { return ['index'=>ListProducts::route('/'),'create'=>CreateProduct::route('/create'),'edit'=>EditProduct::route('/{record}/edit')]; }
}