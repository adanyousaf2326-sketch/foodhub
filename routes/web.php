<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FoodController;
use App\Models\Category;
use App\Models\Food;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Services\DeliveryCalculator;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {

    // Cache categories for 5 minutes (reduces DB hits with many users)
    $categories = \Cache::remember('home_categories', 300, function () {
        return Category::where('is_active', true)->orderBy('name')->get();
    });

    // Cache foods for 2 minutes — show all items including disabled ones
    $foods = \Cache::remember('home_foods', 120, function () {
        return Food::where('is_available', true)
            ->with(['category', 'variations'])
            ->latest()
            ->get();
    });

    // Cache announcements for 5 minutes
    $announcements = \Cache::remember('home_announcements', 300, function () {
        return Announcement::with('foods')->visible()->latest()->get();
    });

    return view('home', compact('categories', 'foods', 'announcements'));

})->name('home');


Route::get('/cart', function () {

    $cart = session()->get('cart', []);

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    return view('cart', compact('cart', 'total'));

})->name('cart');


Route::delete('/cart/clear', function () {

    session()->forget('cart');

    return back()->with('success', 'Cart cleared!');

})->name('cart.clear');
Route::get('/checkout', function () {

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()
            ->route('cart')
            ->with('success', 'Your cart is empty.');
    }

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $tables = \App\Models\RestaurantTable::where('status', 'available')
        ->orderBy('table_number')
        ->get();

    return view('checkout', compact(
        'cart',
        'total',
        'tables'
    ));

})->name('checkout');
Route::post('/order/place', function (Request $request) {
    // Rate limit: max 5 orders per minute per IP to prevent abuse
    if (throttle('order-place', 5, 1)->check() === false) {
        return back()->withInput()->with('error', 'Too many orders! Please wait a moment.');
    }

    $request->validate([

        'order_type' => 'required|in:Delivery,Dine In,Takeaway',

        'customer_name' => 'required|string|max:255',

        'phone' => 'required|string|max:30',

        'email' => 'nullable|email|max:255',

        'address' => 'required_if:order_type,Delivery|nullable|string',

        'table_id' => 'required_if:order_type,Dine In|nullable|exists:restaurant_tables,id',

        'payment_method' => 'required|string',

        'notes' => 'nullable|string',

    ]);


    $cart = session()->get('cart', []);


    if (empty($cart)) {

        return redirect()
            ->route('cart')
            ->with('success', 'Your cart is empty.');

    }


    $total = collect($cart)->sum(function ($item) {

        return $item['price'] * $item['quantity'];

    });


    try {

        $order = DB::transaction(function () use (
            $request,
            $cart,
            $total
        ) {


            /*
             * DINE IN TABLE LOCK
             */

            $table = null;


            if ($request->order_type === 'Dine In') {

                $table = \App\Models\RestaurantTable::where(
                    'id',
                    $request->table_id
                )
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();


                if (!$table) {

                    throw new \Exception(
                        'Sorry, this table has just been booked by another customer.'
                    );

                }


                $table->update([
                    'status' => 'occupied'
                ]);

            }


            /*
             * CREATE ORDER
             */

            // Calculate delivery charges
            $deliveryCharges = 0;
            $deliveryDistance = null;
            $deliveryTime = null;
            $customerLat = null;
            $customerLng = null;

            if ($request->order_type === 'Delivery' && $request->filled('customer_lat') && $request->filled('customer_lng')) {
                $customerLat = (float) $request->customer_lat;
                $customerLng = (float) $request->customer_lng;
                $deliveryResult = \App\Services\DeliveryCalculator::calculate($customerLat, $customerLng);
                $deliveryCharges = $deliveryResult['delivery_charges'];
                $deliveryDistance = $deliveryResult['distance_km'];
                $deliveryTime = $deliveryResult['delivery_time_min'];
            }

            $grandTotal = $total + $deliveryCharges;

            $order = \App\Models\Order::create([

                'customer_name' => $request->customer_name,

                'phone' => $request->phone,

                'email' => $request->email,

                'address' => $request->order_type === 'Delivery'
                    ? $request->address
                    : null,

                'total_amount' => $grandTotal,

                'payment_method' => $request->payment_method,

                'status' => 'Pending',

                'notes' => $request->notes,

                'order_type' => $request->order_type,

                'table_id' => $table?->id,
                'delivery_charges' => $deliveryCharges,
                'delivery_distance_km' => $deliveryDistance,
                'delivery_time_min' => $deliveryTime,
                'customer_lat' => $customerLat,
                'customer_lng' => $customerLng,

            ]);


            /*
             * ORDER ITEMS
             */

            foreach ($cart as $item) {

                \App\Models\OrderItem::create([

                    'order_id' => $order->id,

                    'food_id' => ($item['is_deal'] ?? false)
                        ? null
                        : ($item['food_id'] ?? (is_numeric($item['id']) ? $item['id'] : null)),

                    'food_name' => ($item['is_deal'] ?? false)
                        ? $item['name'] . ' (' . ($item['included_items'] ?? 'Bundle deal') . ')'
                        : $item['name'],

                    'variant_name' => $item['variant_name'] ?? null,

                    'price' => $item['price'],

                    'quantity' => $item['quantity'],

                    'subtotal' =>
                        $item['price'] * $item['quantity'],

                ]);

            }        return $order;

        });



        session()->forget('cart');



        /* SEND NOTIFICATION + EMAIL */

        \App\Models\Notification::create([

            'order_id' => $order->id,

            'type' => 'order_placed',

            'title' => 'New Order #' . $order->id,

            'message' => 'New order from ' . $order->customer_name . ' — Rs. ' . number_format($order->total_amount, 2) . ' (' . $order->order_type . ')',

            'email' => $order->email,

            'email_sent' => false,

        ]);

        /* AUTO-ASSIGN RIDER for delivery orders */
        if ($order->order_type === 'Delivery') {
            \App\Http\Controllers\RiderController::autoAssignRider($order);
        }


        /* Send email if provided */

        if ($order->email) {

            try {

                \Illuminate\Support\Facades\Mail::raw(

                    "Hello {$order->customer_name},\n\n"

                    . "Your order #{$order->id} has been placed successfully!\n\n"

                    . "Order Type: {$order->order_type}\n"

                    . "Total: Rs. " . number_format($order->total_amount, 2) . "\n"

                    . "Payment: {$order->payment_method}\n\n"

                    . "You can track your order at: " . route('track.order.search', ['order_number' => $order->id]) . "\n\n"

                    . "Thank you for choosing FoodHub!",

                    function ($message) use ($order) {

                        $message->to($order->email)

                            ->subject('FoodHub — Order #' . $order->id . ' Confirmed');

                    }

                );

                $order->notifications()->where('type', 'order_placed')->update(['email_sent' => true]);

            } catch (\Exception $e) {

                // Email failed — log it but don't break the flow

            }

        }



        return redirect()

            ->route('order.success', $order)

            ->with(

                'success',

                'Your order has been placed successfully!'

            );


    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->withErrors([
                'table_id' => $e->getMessage()
            ]);

    }

})->name('order.place');
Route::get('/order/success/{order}', function (Order $order) {

    return view('order-success', compact('order'));

})->name('order.success');

Route::get('/track-order', [OrderController::class, 'track'])
    ->name('track.order');

Route::get('/track-order/search', [OrderController::class, 'trackSearch'])
    ->name('track.order.search');

/*
 * ORDER HISTORY (Customer)
 */
Route::get('/order-history', [OrderController::class, 'orderHistory'])
    ->name('order.history');


Route::post('/cart/add/{food}', function (Food $food, Request $request) {

    $cart = session()->get('cart', []);

    /*
    |--------------------------------------------------------------------------
    | Get selected variation
    |--------------------------------------------------------------------------
    */
    $variation = null;

    if ($request->filled('variation_id')) {

        $variation = $food->variations()
            ->where('id', (int) $request->variation_id)
            ->where('is_available', true)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate price
    |--------------------------------------------------------------------------
    */

    // IMPORTANT:
    // If a size/variation is selected, ALWAYS use its price.
    // Deal price should only be used when there is NO variation selected.

    if ($variation) {

        $cartPrice = (float) $variation->discounted_price;

    } else {

        $dealPrice = null;

        if ($request->filled('announcement_id')) {

            $announcement = Announcement::visible()
                ->whereKey($request->announcement_id)
                ->with('foods')
                ->first();

            if ($announcement) {

                $dealFood = $announcement->foods
                    ->firstWhere('id', $food->id);

                $dealPrice = $dealFood?->pivot?->deal_price;
            }
        }

        if ($dealPrice !== null) {
            $cartPrice = (float) $dealPrice;
        } else {
            $cartPrice = (float) $food->discounted_price;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cart key
    |--------------------------------------------------------------------------
    */

    $cartKey = $variation
        ? $food->id . '_var_' . $variation->id
        : (string) $food->id;

    /*
    |--------------------------------------------------------------------------
    | Display name
    |--------------------------------------------------------------------------
    */

    $displayName = $variation
        ? $food->name . ' (' . $variation->name . ')'
        : $food->name;

    /*
    |--------------------------------------------------------------------------
    | Add / Update cart
    |--------------------------------------------------------------------------
    */

    if (isset($cart[$cartKey])) {

        $cart[$cartKey]['quantity']++;

        // Make sure the latest selected variation price is used
        $cart[$cartKey]['price'] = $cartPrice;

    } else {

        $cart[$cartKey] = [

            'id' => $cartKey,

            'cart_key' => $cartKey,

            'food_id' => $food->id,

            'variant_id' => $variation?->id,

            'variation_id' => $variation?->id,

            'variant_name' => $variation?->name,

            'variation_name' => $variation?->name,

            'name' => $displayName,

            'price' => $cartPrice,

            'image' => $food->image,

            'quantity' => 1,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Save cart
    |--------------------------------------------------------------------------
    */

    session()->put('cart', $cart);

    /*
    |--------------------------------------------------------------------------
    | Calculate totals
    |--------------------------------------------------------------------------
    */

    $total = collect($cart)->sum(function ($item) {

        return (float) $item['price'] * (int) $item['quantity'];

    });

    $count = collect($cart)->sum('quantity');

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'message' => $variation
            ? $food->name . ' (' . $variation->name . ') added!'
            : $food->name . ' added!',

        'cart' => $cart,

        'total' => $total,

        'count' => $count,

    ]);

})->name('cart.add');


Route::post('/cart/add-deal/{announcement}', function (Announcement $announcement) {

    abort_unless(
        Announcement::visible()->whereKey($announcement->id)->exists(),
        404
    );

    $announcement->load('foods');

    if ($announcement->foods->isEmpty() || $announcement->deal_total === null) {
        return response()->json([
            'success' => false,
            'message' => 'This deal is not available.',
        ], 422);
    }

    $cart = session()->get('cart', []);
    $cartKey = 'deal_' . $announcement->id;
    $includedItems = $announcement->foods
        ->map(function ($food) {
            $quantity = (int) ($food->pivot->quantity ?? 1);

            return $quantity . ' × ' . $food->name;
        })
        ->implode(', ');

    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity']++;
    } else {
        $cart[$cartKey] = [
            'id' => $cartKey,
            'cart_key' => $cartKey,
            'name' => $announcement->title,
            'price' => (float) $announcement->deal_total,
            'image' => $announcement->deal_image,
            'quantity' => 1,
            'is_deal' => true,
            'included_items' => $includedItems,
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => 'Deal added to cart!',
        'cart' => $cart,
    ]);

})->name('cart.add-deal');



Route::post('/cart/update/{id}', function ($id, Request $request) {

    $cart = session()->get('cart', []);

    $quantity = max(0, (int) $request->quantity);

    if (isset($cart[$id])) {

        if ($quantity <= 0) {

            unset($cart[$id]);

        } else {

            $cart[$id]['quantity'] = $quantity;

        }

    }

    session()->put('cart', $cart);

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $count = collect($cart)->sum('quantity');

    return response()->json([
        'success' => true,
        'cart' => $cart,
        'total' => $total,
        'count' => $count,
    ]);

})->name('cart.update');


Route::post('/cart/update-json', function (Request $request) {

    $cart = session()->get('cart', []);
    $id = (string) $request->input('id');
    $quantity = max(0, (int) $request->input('quantity'));

    if (isset($cart[$id])) {
        if ($quantity === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['quantity'] = $quantity;
        }
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'cart' => $cart,
    ]);

})->name('cart.update-json');



Route::delete('/cart/remove/{id}', function ($id) {

    $cart = session()->get('cart', []);

    unset($cart[$id]);

    session()->put('cart', $cart);

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $count = collect($cart)->sum('quantity');

    return response()->json([
        'success' => true,
        'cart' => $cart,
        'total' => $total,
        'count' => $count,
    ]);

})->name('cart.remove');


Route::post('/cart/remove-json', function (Request $request) {

    $cart = session()->get('cart', []);
    $id = (string) $request->input('id');

    unset($cart[$id]);
    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'cart' => $cart,
    ]);

})->name('cart.remove-json');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
 * CUSTOMER AUTH
 */
Route::get('/customer/register', [\App\Http\Controllers\CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [\App\Http\Controllers\CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::get('/customer/login', [\App\Http\Controllers\CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [\App\Http\Controllers\CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::post('/customer/logout', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::get('/customer/profile', [\App\Http\Controllers\CustomerAuthController::class, 'profile'])->name('customer.profile');
Route::put('/customer/profile', [\App\Http\Controllers\CustomerAuthController::class, 'updateProfile'])->name('customer.update-profile');

/*
 * WISHLIST / FAVORITES
 */
Route::get('/api/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/api/wishlist/{foodId}', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::delete('/api/wishlist/{foodId}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

/*
 * LIVE TRACKING MAP
 */
Route::get('/track/{orderId}/map', [\App\Http\Controllers\TrackingMapController::class, 'customerMap'])->name('tracking.map');
Route::get('/api/tracking/{orderId}/rider-location', [\App\Http\Controllers\TrackingMapController::class, 'riderLocation']);
Route::post('/api/rider/update-location', [\App\Http\Controllers\TrackingMapController::class, 'updateRiderLocation']);
Route::get('/api/admin/rider-locations', [\App\Http\Controllers\TrackingMapController::class, 'allRiderLocations']);

/*
 * KITCHEN PRINTER API
 */
Route::get('/api/kitchen/new-orders', function (Request $request) {
    $lastId = $request->input('last_id', 0);
    $orders = Order::where('id', '>', $lastId)
        ->where('status', 'Pending')
        ->with(['items.food', 'table'])
        ->orderBy('id')
        ->limit(10)
        ->get()
        ->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'address' => $order->address,
                'order_type' => $order->order_type,
                'total_amount' => $order->total_amount,
                'notes' => $order->notes,
                'table' => $order->table ? ['table_number' => $order->table->table_number] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'food_name' => $item->food_name,
                        'variant_name' => $item->variant_name,
                    ];
                }),
            ];
        });
    return response()->json(['orders' => $orders]);
})->middleware('web');

/*
 * CUSTOMER CHAT
 */
Route::get('/track-order/{order}/chat', function (Order $order) {
    $messages = \App\Models\Message::where('order_id', $order->id)
        ->orderBy('created_at')
        ->get();
    return view('customer.chat', compact('order', 'messages'));
})->name('customer.chat');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/kitchen', [DashboardController::class, 'kitchen'])
            ->name('kitchen');

        // Admin: Close All / New Day Reset
        Route::post('/close-all', [DashboardController::class, 'closeAll'])
            ->name('close-all');

    Route::get('/dashboard/orders-json', [DashboardController::class, 'ordersJson'])->name('dashboard.orders-json');

    Route::resource('categories', CategoryController::class);

    Route::resource('food', FoodController::class);

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show']);

    // Change Password (any logged-in user)
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [UserController::class, 'updatePassword'])->name('update-password');

    // Admin Only Routes
    Route::middleware([\App\Http\Middleware\AdminOnly::class])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        // Rider Management
        Route::get('/riders', [\App\Http\Controllers\RiderController::class, 'adminIndex'])->name('riders.index');
        Route::get('/riders/{id}/approve', [\App\Http\Controllers\RiderController::class, 'approveRider'])->name('riders.approve');
        Route::get('/riders/{id}/reject', [\App\Http\Controllers\RiderController::class, 'rejectRider'])->name('riders.reject');
        Route::get('/riders/{id}/toggle-duty', [\App\Http\Controllers\RiderController::class, 'toggleRiderDuty'])->name('riders.toggle-duty');
        Route::get('/riders/{id}/delete', [\App\Http\Controllers\RiderController::class, 'deleteRider'])->name('riders.delete');

        // Rider Cash Collection
        Route::get('/riders/cash', [\App\Http\Controllers\RiderController::class, 'adminCashCollection'])->name('riders.cash');
        Route::post('/riders/{id}/receive-cash', [\App\Http\Controllers\RiderController::class, 'receiveCash'])->name('riders.receive-cash');
        Route::post('/riders/order/{id}/receive-cash', [\App\Http\Controllers\RiderController::class, 'receiveSingleCash'])->name('riders.receive-single-cash');

        // Live Rider Tracking Map
        Route::get('/rider-map', [\App\Http\Controllers\TrackingMapController::class, 'adminRiderMap'])->name('rider-map');

        // Customer Management (Admin only)
        Route::get('/customers', [\App\Http\Controllers\CustomerAuthController::class, 'adminIndex'])->name('customers.index');
        Route::get('/customers/{id}', [\App\Http\Controllers\CustomerAuthController::class, 'adminShow'])->name('customers.show');
        Route::post('/customers/{id}/delete', [\App\Http\Controllers\CustomerAuthController::class, 'adminDelete'])->name('customers.delete');
    });

    Route::resource('orders', OrderController::class)
        ->only(['index', 'show', 'update', 'destroy']);

    Route::post('/orders/{order}/close', [OrderController::class, 'close'])
        ->name('orders.close');

    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::get('/orders/{order}/bill', [OrderController::class, 'bill'])
        ->name('orders.bill');

    // Admin Edit Order (unlimited before delivery/completion)
    Route::get('/orders/{order}/admin-edit', [OrderController::class, 'adminEdit'])->name('orders.admin-edit');
    Route::post('/orders/{order}/admin-edit-save', [OrderController::class, 'adminEditSave'])->name('orders.admin-edit-save');

    Route::get('/orders/export/csv', [OrderController::class, 'exportCsv'])
        ->name('orders.export.csv');

    Route::get('/orders/export/pdf', [OrderController::class, 'exportPdf'])
        ->name('orders.export.pdf');

    // Edit Requests
    Route::post('/orders/{order}/edit-requests/{editRequest}/accept', [OrderController::class, 'acceptEditRequest'])
        ->name('orders.edit-requests.accept');

    Route::post('/orders/{order}/edit-requests/{editRequest}/reject', [OrderController::class, 'rejectEditRequest'])
        ->name('orders.edit-requests.reject');

    // Messages (Admin)
    Route::post('/orders/{order}/messages', [OrderController::class, 'adminSendMessage'])
        ->name('orders.messages.send');

    // Notifications JSON for bell
    Route::get('/notifications-json', [DashboardController::class, 'notificationsJson'])
        ->name('notifications-json');    // Analytics JSON for charts
    Route::get('/analytics-json', [DashboardController::class, 'analyticsJson'])->name('analytics-json');        // Real-time updates (SSE + polling)
        Route::get('/stream-updates', [DashboardController::class, 'streamUpdates'])->name('stream-updates');
        Route::get('/latest-orders-json', [DashboardController::class, 'latestOrdersJson'])->name('latest-orders-json');



        // Inventory Management
        Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory');
        Route::get('/inventory-json', function () {
            // Auto-create columns if missing (SQLite fix)
            $columns = DB::select('PRAGMA table_info(food)');
            $columnNames = array_column($columns, 'name');
            if (!in_array('is_in_stock', $columnNames)) {
                DB::statement('ALTER TABLE food ADD COLUMN stock_quantity INTEGER NOT NULL DEFAULT -1');
                DB::statement('ALTER TABLE food ADD COLUMN is_in_stock BOOLEAN NOT NULL DEFAULT 1');
                DB::statement('ALTER TABLE food ADD COLUMN low_stock_threshold INTEGER NOT NULL DEFAULT 5');
                DB::statement('ALTER TABLE food ADD COLUMN available_at TIMESTAMP NULL DEFAULT NULL');
            }

            $foods = \App\Models\Food::select('id', 'name', 'is_in_stock', 'stock_quantity', 'low_stock_threshold', 'available_at')
                ->orderBy('name')
                ->get();
            return response()->json(['foods' => $foods]);
        });
        Route::post('/food/{id}/stock', [\App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('food.update-stock');
        Route::post('/food/{id}/toggle-stock', [\App\Http\Controllers\Admin\InventoryController::class, 'toggleInStock'])->name('food.toggle-stock');

});

Route::post(
    '/track-order/{order}/cancel',
    [\App\Http\Controllers\Admin\OrderController::class, 'cancelFromTracking']
)->name('track.order.cancel');

Route::get(
    '/track-order/{order}/edit',
    [\App\Http\Controllers\Admin\OrderController::class, 'trackEdit']
)->name('track.order.edit');

Route::post(
    '/track-order/{order}/update',
    [\App\Http\Controllers\Admin\OrderController::class, 'trackUpdate']
)->name('track.order.update');

/*
 * EDIT REQUEST (Customer sends request to admin)
 */
Route::post(
    '/track-order/{order}/edit-request',
    [\App\Http\Controllers\Admin\OrderController::class, 'storeEditRequest']
)->name('track.order.edit-request');

/*
 * MESSAGES (Customer ↔ Admin chat)
 */
Route::get(
    '/track-order/{order}/messages',
    [\App\Http\Controllers\Admin\OrderController::class, 'getMessages']
)->name('track.order.messages');

Route::post(
    '/track-order/{order}/message',
    [\App\Http\Controllers\Admin\OrderController::class, 'sendMessage']
)->name('track.order.send-message');

Route::get(
    '/track-order/{order}/edit-status',
    [\App\Http\Controllers\Admin\OrderController::class, 'editStatus']
)->name('track.order.edit-status');

/*
 * CUSTOMER RATING
 */
Route::post(
    '/track-order/{order}/rate',
    [\App\Http\Controllers\Admin\OrderController::class, 'rateOrder']
)->name('track.order.rate');

/*
 * DELIVERY CHARGE CALCULATION API
 */
Route::get('/api/delivery-calc', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'lat' => 'required|numeric|between:-90,90',
        'lng' => 'required|numeric|between:-180,180',
    ]);

    // Calculate realistic prep time from cart using PrepTimeCalculator
    $cart = session()->get('cart', []);
    $prepTime = \App\Services\PrepTimeCalculator::estimateFromCart($cart);

    $result = DeliveryCalculator::calculate(
        (float) $request->lat,
        (float) $request->lng,
        $prepTime
    );

    return response()->json($result);
})->middleware('web');

Route::get('/sql', function () {
    $path = database_path('foodhub.sql');
    abort_unless(is_file($path), 404);

    return view('sql', [
        'sql' => file_get_contents($path),
    ]);
})->name('sql');

Route::get('/sql/download', function () {
    $path = database_path('foodhub.sql');
    abort_unless(is_file($path), 404);

    return response()->download($path, 'foodhub.sql');
})->name('sql.download');

/*
 * QR CODE TABLE SCAN
 */
Route::get('/scan/{tableNumber}', function ($tableNumber) {
    $table = \App\Models\RestaurantTable::where('table_number', $tableNumber)->first();

    if (!$table) {
        abort(404);
    }

    // If table is available, store it in session and redirect to menu
    if ($table->status === 'available') {
        session()->put('scanned_table_id', $table->id);
        session()->put('scanned_table_number', $table->table_number);
        return redirect()->route('home')->with('table_message', 'Table #' . $table->table_number . ' selected! Choose your food.');
    }

    // Table is booked — show available tables
    $availableTables = \App\Models\RestaurantTable::where('status', 'available')
        ->orderBy('table_number')
        ->get();

    return view('scan', compact('table', 'availableTables'));
})->name('scan.table');

/*
 * RIDER ROUTES
 */
Route::get('/rider/register', [\App\Http\Controllers\RiderController::class, 'showRegister'])->name('rider.register');
Route::post('/rider/register', [\App\Http\Controllers\RiderController::class, 'register'])->name('rider.register.submit');
Route::get('/rider/login', [\App\Http\Controllers\RiderController::class, 'showLogin'])->name('rider.login');
Route::post('/rider/login', [\App\Http\Controllers\RiderController::class, 'login'])->name('rider.login.submit');
Route::get('/rider/logout', [\App\Http\Controllers\RiderController::class, 'logout'])->name('rider.logout');
Route::get('/rider/dashboard', [\App\Http\Controllers\RiderController::class, 'dashboard'])->name('rider.dashboard');
Route::post('/rider/toggle-duty', [\App\Http\Controllers\RiderController::class, 'toggleDuty'])->name('rider.toggle-duty');
Route::get('/rider/accept-order/{id}', [\App\Http\Controllers\RiderController::class, 'acceptOrder'])->name('rider.accept-order');
Route::get('/rider/pick-up/{id}', [\App\Http\Controllers\RiderController::class, 'pickUp'])->name('rider.pick-up');
Route::get('/rider/return-to-kitchen/{id}', [\App\Http\Controllers\RiderController::class, 'returnToKitchen'])->name('rider.return-to-kitchen');
Route::get('/rider/mark-delivered/{id}', [\App\Http\Controllers\RiderController::class, 'markDelivered'])->name('rider.mark-delivered');
Route::get('/rider/cash-summary', [\App\Http\Controllers\RiderController::class, 'cashSummary'])->name('rider.cash-summary');

Route::get('/scan-table/{tableNumber}', function ($tableNumber) {
    $table = \App\Models\RestaurantTable::where('table_number', $tableNumber)->first();

    if (!$table || $table->status !== 'available') {
        return back()->with('error', 'This table is not available.');
    }

    session()->put('scanned_table_id', $table->id);
    session()->put('scanned_table_number', $table->table_number);

    return redirect()->route('home')->with('table_message', 'Table #' . $table->table_number . ' selected! Choose your food.');
})->name('scan.select');