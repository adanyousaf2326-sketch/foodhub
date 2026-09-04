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


Route::get('/', function () {

    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();

    $foods = Food::where('is_available', true)
        ->with(['category', 'variations'])
        ->latest()
        ->get();

    $announcements = Announcement::with('foods')
        ->visible()
        ->latest()
        ->get();

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

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/kitchen', [DashboardController::class, 'kitchen'])
            ->name('kitchen');

    Route::get('/dashboard/orders-json', [DashboardController::class, 'ordersJson'])->name('dashboard.orders-json');

    Route::resource('categories', CategoryController::class);

    Route::resource('food', FoodController::class);

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show']);

    Route::resource('users', UserController::class)
        ->except(['show']);

    // Change Password (any logged-in user)
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [UserController::class, 'updatePassword'])->name('update-password');

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

        // Print Receipt
        Route::get('/orders/{order}/print', function (Order $order) {
            $order->load(['items.food', 'table', 'rider']);
            return view('admin.print-receipt', compact('order'));
        })->name('orders.print');

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

    // Calculate max prep time from cart
    $cart = session()->get('cart', []);
    $maxPrep = 15;
    foreach ($cart as $item) {
        if (!empty($item['food_id'])) {
            $food = \App\Models\Food::find($item['food_id']);
            if ($food) {
                $maxPrep = max($maxPrep, ($food->prep_time ?? 15) * ($item['quantity'] ?? 1));
            }
        }
    }

    $result = DeliveryCalculator::calculate(
        (float) $request->lat,
        (float) $request->lng,
        $maxPrep
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