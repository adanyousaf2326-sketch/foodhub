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

    $foods = Food::where('is_available', true)
        ->with('category')
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

                    'price' => $item['price'],

                    'quantity' => $item['quantity'],

                    'subtotal' =>
                        $item['price'] * $item['quantity'],

                ]);

            }


            return $order;

        });


        session()->forget('cart');


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



Route::get('/track-order', function () {
    return view('track-order');
})->name('track.order');


Route::get('/track-order', [OrderController::class, 'track'])
    ->name('track.order');

Route::get('/track-order/search', [OrderController::class, 'trackSearch'])
    ->name('track.order.search');


Route::post('/cart/add/{food}', function (Food $food, Request $request) {

    $cart = session()->get('cart', []);

    $id = $food->id;

    $dealPrice = null;

    if ($request->filled('announcement_id')) {
        $announcement = Announcement::visible()
            ->whereKey($request->announcement_id)
            ->with('foods')
            ->first();

        $dealFood = $announcement?->foods->firstWhere('id', $food->id);
        $dealPrice = $dealFood?->pivot?->deal_price;
    }

    $cartPrice = $dealPrice !== null
        ? (float) $dealPrice
        : $food->discounted_price;

    if (isset($cart[$id])) {

        $cart[$id]['quantity']++;
        $cart[$id]['price'] = $cartPrice;

    } else {

        $cart[$id] = [
            'id' => $food->id,
            'name' => $food->name,
            'price' => $cartPrice,
            'image' => $food->image,
            'quantity' => 1,
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

    Route::post('/orders/{order}/complete-payment', [OrderController::class, 'completePayment'])
        ->name('orders.complete-payment');

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
        ->name('notifications-json');

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