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
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;


Route::get('/', function () {

    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();

    $hasFoodSizes = \Illuminate\Support\Facades\Schema::hasTable('food_sizes');

    $foods = Food::where('is_available', true)
        ->with($hasFoodSizes ? ['category', 'foodSizes'] : ['category'])
        ->latest()
        ->get();

    $announcements = Announcement::with('foods')
        ->visible()
        ->latest()
        ->get();

    return view('home', compact('categories', 'foods', 'announcements', 'hasFoodSizes'));

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

            $order = \App\Models\Order::create([

                'customer_name' => $request->customer_name,

                'phone' => $request->phone,

                'email' => $request->email,

                'address' => $request->order_type === 'Delivery'
                    ? $request->address
                    : null,

                'total_amount' => $total,

                'payment_method' => $request->payment_method,

                'status' => 'Pending',

                'notes' => $request->notes,

                'order_type' => $request->order_type,

                'table_id' => $table?->id,

            ]);


            /*
             * ORDER ITEMS
             */

            foreach ($cart as $item) {

                \App\Models\OrderItem::create([

                    'order_id' => $order->id,

                    'food_id' => ($item['is_deal'] ?? false)
                        ? null
                        : $item['id'],

                    'food_name' => ($item['is_deal'] ?? false)
                        ? $item['name'] . ' (' . ($item['included_items'] ?? 'Bundle deal') . ')'
                        : $item['name'],

                    'size_name' => $item['size_name'] ?? null,

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

    // Handle size selection
    $sizeName = null;
    $sizeId = null;
    $foodSize = null;
    if ($request->filled('size_id')) {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('food_sizes')) {
                $foodSize = \App\Models\FoodSize::where('food_id', $food->id)
                    ->where('id', $request->size_id)
                    ->first();
                if ($foodSize) {
                    $sizeName = $foodSize->name;
                    $sizeId = $foodSize->id;
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet — ignore
        }
    }

    // Cart key: food_id (no size) or food_id_sizeid (with size)
    $cartKey = $sizeId ? $food->id . '_' . $sizeId : $food->id;

    $dealPrice = null;

    if ($request->filled('announcement_id')) {
        $announcement = Announcement::visible()
            ->whereKey($request->announcement_id)
            ->with('foods')
            ->first();

        $dealFood = $announcement?->foods->firstWhere('id', $food->id);
        $dealPrice = $dealFood?->pivot?->deal_price;
    }

    // Determine price: deal > size > base
    if ($dealPrice !== null) {
        $cartPrice = (float) $dealPrice;
    } elseif ($sizeName && $foodSize) {
        $cartPrice = (float) $foodSize->price;
        if ((float) $foodSize->discount_percentage > 0) {
            $cartPrice = round($cartPrice * (1 - ((float) $foodSize->discount_percentage / 100)), 2);
        }
    } else {
        $cartPrice = $food->discounted_price;
    }



    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity']++;
        $cart[$cartKey]['price'] = $cartPrice;
    } else {
        $cart[$cartKey] = [
            'id' => $food->id,
            'cart_key' => $cartKey,
            'name' => $food->name,
            'price' => $cartPrice,
            'image' => $food->image,
            'quantity' => 1,
            'size_name' => $sizeName,
            'size_id' => $sizeId,
        ];
    }

    session()->put('cart', $cart);

    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    $count = collect($cart)->sum('quantity');

    return response()->json([
        'success' => true,
        'message' => 'Food added!',
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

})->whereNumber('id')->name('cart.update');


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

})->whereNumber('id')->name('cart.remove');


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

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/orders-json', [DashboardController::class, 'ordersJson'])->name('dashboard.orders-json');

    Route::resource('categories', CategoryController::class);

    Route::resource('food', FoodController::class);

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show']);

    Route::resource('users', UserController::class)
        ->except(['show']);

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
    Route::get('/analytics-json', [DashboardController::class, 'analyticsJson'])->name('analytics-json');

    // Real-time updates (SSE + polling)
    Route::get('/stream-updates', [DashboardController::class, 'streamUpdates'])->name('stream-updates');
    Route::get('/latest-orders-json', [DashboardController::class, 'latestOrdersJson'])->name('latest-orders-json');

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