<?php
namespace App\Filament\Resources\Orders;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class OrderResource extends Resource {
 protected static ?string $model=Order::class;
 protected static ?string $recordTitleAttribute='order_number';
 protected static ?string $navigationLabel='Orders';
 protected static ?string $modelLabel='order';
 protected static ?string $pluralModelLabel='orders';
 protected static string|\UnitEnum|null $navigationGroup='Sales';
 protected static string|\BackedEnum|null $navigationIcon='heroicon-o-shopping-bag';
 public static function form(Schema $schema): Schema {
  return $schema->components([
   TextInput::make('order_number')->disabled(),
   Select::make('status')->options(['pending_review'=>'Pending review','approved'=>'Approved','processing'=>'Processing','shipped'=>'Shipped','completed'=>'Completed','cancelled'=>'Cancelled'])->required()->disabled(fn (?Order $record): bool => $record?->status === 'cancelled'),
   Select::make('payment_status')->options(['not_paid'=>'Not paid','awaiting_confirmation'=>'Awaiting confirmation','paid'=>'Paid','refunded'=>'Refunded'])->required(),
   TextInput::make('customer_name')->disabled(),TextInput::make('customer_email')->disabled(),TextInput::make('customer_phone')->disabled(),
   TextInput::make('address_line_1')->disabled()->columnSpanFull(),TextInput::make('address_line_2')->disabled()->columnSpanFull(),
   TextInput::make('city')->disabled(),TextInput::make('state_region')->disabled(),TextInput::make('postal_code')->disabled(),TextInput::make('country')->disabled(),
   Textarea::make('customer_note')->disabled()->columnSpanFull(),TextInput::make('total')->prefix('USD')->disabled(),
  ])->columns(2);
 }
 public static function table(Table $table): Table {
  return $table->columns([
   TextColumn::make('order_number')->searchable()->sortable(),
   TextColumn::make('customer_name')->searchable(),
   TextColumn::make('customer_email')->searchable(),
   TextColumn::make('total')->money('USD')->sortable(),
   TextColumn::make('status')->badge()->color(fn (string $state): string => match($state){'completed'=>'success','cancelled'=>'danger','pending_review'=>'warning',default=>'info'}),
   TextColumn::make('payment_status')->badge(),
   TextColumn::make('created_at')->dateTime()->sortable(),
  ])->defaultSort('created_at','desc')->recordActions([\Filament\Actions\EditAction::make()]);
 }
 public static function getPages(): array { return ['index'=>ListOrders::route('/'),'edit'=>EditOrder::route('/{record}/edit')]; }
}