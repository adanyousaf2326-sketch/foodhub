<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order #{{ $order->id }} - FoodHub</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #222;
            padding: 0 0 50px 0;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 30px 20px;
        }

        /* TIMER BANNER */
        .timer-card {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border: 2px solid #fdba74;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(255, 107, 0, 0.1);
        }

        .timer-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .timer-icon {
            font-size: 36px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .timer-text h2 {
            font-size: 18px;
            color: #9a3412;
            margin-bottom: 4px;
        }

        .timer-text p {
            font-size: 13px;
            color: #c2410c;
            line-height: 1.4;
        }

        .timer-countdown {
            background: #ffffff;
            border: 2px solid #ea580c;
            padding: 10px 18px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.15);
            min-width: 140px;
        }

        .timer-countdown .label {
            font-size: 11px;
            font-weight: bold;
            color: #78716c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .timer-countdown .time {
            font-size: 26px;
            font-weight: 900;
            color: #ea580c;
            font-variant-numeric: tabular-nums;
        }

        .timer-expired-alert {
            display: none;
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fecaca;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: bold;
        }

        /* GRID LAYOUT */
        .layout-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 25px;
            align-items: start;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .card h3 {
            font-size: 18px;
            color: #111827;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 12px;
        }

        /* ITEMS TABLE */
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.2s ease;
        }

        .item-row:hover {
            border-color: #cbd5e1;
        }

        .item-info {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-weight: bold;
            color: #1e293b;
            font-size: 14.5px;
            margin-bottom: 2px;
        }

        .item-price {
            font-size: 12.5px;
            color: #64748b;
        }

        .qty-control {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            font-size: 16px;
            font-weight: bold;
            color: #334155;
            cursor: pointer;
            transition: background 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #ffedd5;
            color: #ea580c;
        }

        .qty-input {
            width: 38px;
            text-align: center;
            border: none;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            outline: none;
        }

        .item-subtotal {
            font-weight: bold;
            color: #ea580c;
            font-size: 14.5px;
            min-width: 80px;
            text-align: right;
        }

        .item-remove-btn {
            border: none;
            background: #fee2e2;
            color: #dc2626;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .item-remove-btn:hover {
            background: #dc2626;
            color: #ffffff;
        }

        /* ADD ITEM BOX */
        .add-item-box {
            background: #f0fdf4;
            border: 1px dashed #86efac;
            border-radius: 12px;
            padding: 16px;
            margin-top: 15px;
        }

        .add-item-box h4 {
            font-size: 14px;
            color: #166534;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .add-item-row {
            display: flex;
            gap: 10px;
        }

        .add-item-select {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            outline: none;
        }

        .add-item-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: #16a34a;
            color: white;
            font-weight: bold;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease;
        }

        .add-item-btn:hover {
            background: #15803d;
        }

        /* TOTALS SUMMARY */
        .total-summary-card {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: white;
            border-radius: 14px;
            padding: 18px 20px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-summary-card .label {
            font-size: 15px;
            color: #9ca3af;
        }

        .total-summary-card .amount {
            font-size: 26px;
            font-weight: 900;
            color: #ff6b00;
        }

        /* FORM CONTROLS */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13.5px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14.5px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.12);
        }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        /* ORDER TYPE SELECTOR */
        .order-type-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .order-type-tab {
            position: relative;
        }

        .order-type-tab input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .order-type-tab label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 12px 6px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            color: #475569;
            transition: all 0.2s ease;
        }

        .order-type-tab input:checked + label {
            border-color: #ff6b00;
            background: #fff7ed;
            color: #ea580c;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.15);
        }

        /* DINE IN TABLES */
        .table-select-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-height: 180px;
            overflow-y: auto;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            margin-top: 6px;
        }

        .table-radio-item {
            position: relative;
        }

        .table-radio-item input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .table-radio-item label {
            display: block;
            padding: 10px 6px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            text-align: center;
            background: white;
            font-size: 12.5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .table-radio-item input:checked + label {
            background: #ff6b00;
            color: white;
            border-color: #ff6b00;
        }

        /* BUTTONS */
        .actions-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 25px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #ff6b00 0%, #ea580c 100%);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(255, 107, 0, 0.35);
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(255, 107, 0, 0.45);
        }

        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-link {
            display: block;
            text-align: center;
            padding: 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border-radius: 8px;
            transition: background 0.15s ease;
        }

        .back-link:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .error-alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }

        @media (max-width: 860px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
            .timer-card {
                flex-direction: column;
                text-align: center;
            }
            .timer-left {
                flex-direction: column;
            }
            .timer-countdown {
                width: 100%;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>

<body>

<nav>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon">🍔</span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>
        <a href="{{ url('/') }}">
            <span>🏠</span> Home
        </a>

        <a href="{{ url('/#categories') }}">
            <i class="fas fa-th-large"></i> Categories
        </a>

        <a href="{{ url('/#full-menu') }}">
            <i class="fas fa-utensils"></i> Menu
        </a>

        <a href="{{ url('/#announcement') }}" class="announcement-nav">
            <i class="fas fa-tags"></i> New Deals
        </a>

        <a href="{{ route('track.order') }}" class="track-active">
            <i class="fas fa-map-marker-alt"></i> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count" id="navCartCount">{{ collect(session()->get('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<div class="container">

    <!-- TIMER CARD -->
    <div class="timer-card" id="timerCard">
        <div class="timer-left">
            <div class="timer-icon">⏳</div>
            <div class="timer-text">
                <h2>15-Minute Edit Window Active</h2>
                <p>
                    Order #{{ $order->id }} was placed at <strong>{{ $order->created_at->format('h:i A') }}</strong>.
                    You can modify items, quantities, and delivery details within 15 minutes of initial placement.
                    <br>
                    <small>⚠️ Note: Updating items does not reset the 15-minute timer.</small>
                </p>
            </div>
        </div>

        <div class="timer-countdown">
            <div class="label">Time Remaining</div>
            <div class="time" id="countdownTimer">--:--</div>
        </div>
    </div>

    <!-- EXPIRED ALERT -->
    <div class="timer-expired-alert" id="timerExpiredAlert">
        ⏰ The 15-minute update window has expired. Orders can only be modified within 15 minutes of placement.
    </div>

    @if($errors->any())
        <div class="error-alert">
            <strong>Please fix the following issues:</strong>
            <ul style="margin-top: 6px; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('track.order.update', $order) }}" id="orderUpdateForm">
        @csrf

        <div class="layout-grid">

            <!-- LEFT COLUMN: ORDER ITEMS -->
            <div>
                <div class="card">
                    <h3><i class="fas fa-utensils"></i> Order Items & Quantities</h3>

                    <div class="items-list" id="itemsListContainer">
                        @foreach($order->items as $index => $item)
                            <div class="item-row" data-index="{{ $index }}">
                                <input type="hidden" name="items[{{ $index }}][food_id]" value="{{ $item->food_id }}">
                                <input type="hidden" name="items[{{ $index }}][food_name]" value="{{ $item->food_name }}">
                                <input type="hidden" name="items[{{ $index }}][price]" class="item-price-val" value="{{ $item->price }}">

                                <div class="item-info">
                                    <div class="item-name">{{ $item->food_name }}</div>
                                    <div class="item-price">Rs. {{ number_format($item->price, 2) }} each</div>
                                </div>

                                <div class="qty-control">
                                    <button type="button" class="qty-btn btn-minus">−</button>
                                    <input type="number" name="items[{{ $index }}][quantity]" class="qty-input" value="{{ $item->quantity }}" min="1" max="99" readonly>
                                    <button type="button" class="qty-btn btn-plus">+</button>
                                </div>

                                <div class="item-subtotal">Rs. <span class="subtotal-val">{{ number_format($item->price * $item->quantity, 2) }}</span></div>

                                <button type="button" class="item-remove-btn" title="Remove item"><i class="fas fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>

                    <!-- ADD MORE ITEMS BOX -->
                    <div class="add-item-box">
                        <h4>➕ Add More Food to Order</h4>
                        <div class="add-item-row">
                            <select id="foodSelector" class="add-item-select">
                                <option value="">-- Choose Food Item --</option>
                                @foreach($availableFoods as $food)
                                    <option value="{{ $food->id }}" data-name="{{ $food->name }}" data-price="{{ $food->discounted_price }}">
                                        {{ $food->name }} ({{ $food->category->name ?? 'Menu' }}) - Rs. {{ number_format($food->discounted_price, 2) }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" class="add-item-btn" id="btnAddFood">
                                ＋ Add
                            </button>
                        </div>
                    </div>

                    <!-- TOTAL SUMMARY -->
                    <div class="total-summary-card">
                        <span class="label">Updated Order Total:</span>
                        <span class="amount">Rs. <span id="grandTotalText">{{ number_format($order->total_amount, 2) }}</span></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: DETAILS -->
            <div>
                <div class="card">
                    <h3><i class="fas fa-clipboard"></i> Customer & Order Details</h3>

                    <!-- CUSTOMER NAME -->
                    <div class="form-group">
                        <label for="customer_name">Customer Name *</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name', $order->customer_name) }}" required>
                    </div>

                    <!-- PHONE -->
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $order->phone) }}" required>
                    </div>

                    <!-- ORDER TYPE -->
                    <div class="form-group">
                        <label>Order Type *</label>
                        <div class="order-type-tabs">
                            <div class="order-type-tab">
                                <input type="radio" id="type_dinein" name="order_type" value="Dine In" {{ old('order_type', $order->order_type) === 'Dine In' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_dinein">
                                    <i class="fas fa-utensils"></i>
                                    <span>Dine In</span>
                                </label>
                            </div>

                            <div class="order-type-tab">
                                <input type="radio" id="type_delivery" name="order_type" value="Delivery" {{ old('order_type', $order->order_type) === 'Delivery' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_delivery">
                                    <span>🛵</span>
                                    <span>Delivery</span>
                                </label>
                            </div>

                            <div class="order-type-tab">
                                <input type="radio" id="type_takeaway" name="order_type" value="Takeaway" {{ old('order_type', $order->order_type) === 'Takeaway' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_takeaway">
                                    <span>🛍️</span>
                                    <span>Takeaway</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- DINE IN TABLE FIELD -->
                    <div class="form-group" id="tableFieldContainer" style="{{ old('order_type', $order->order_type) === 'Dine In' ? '' : 'display:none;' }}">
                        <label>Select Table *</label>
                        <div class="table-select-grid">
                            @foreach($tables as $tbl)
                                <div class="table-radio-item">
                                    <input type="radio" id="tbl_{{ $tbl->id }}" name="table_id" value="{{ $tbl->id }}" {{ old('table_id', $order->table_id) == $tbl->id ? 'checked' : '' }}>
                                    <label for="tbl_{{ $tbl->id }}">
                                        Table #{{ $tbl->table_number }}
                                        <br><small style="color:#64748b;">({{ $tbl->capacity }} seats)</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- DELIVERY ADDRESS FIELD -->
                    <div class="form-group" id="addressFieldContainer" style="{{ old('order_type', $order->order_type) === 'Delivery' ? '' : 'display:none;' }}">
                        <label for="address">Delivery Address *</label>
                        <textarea id="address" name="address" class="form-control" placeholder="House/Street, Area, City...">{{ old('address', $order->address) }}</textarea>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div class="form-group">
                        <label for="payment_method">Payment Method *</label>
                        <select id="payment_method" name="payment_method" class="form-control" required>
                            <option value="Cash" {{ old('payment_method', $order->payment_method) === 'Cash' ? 'selected' : '' }}>💵 Cash on Delivery / Pay at Counter</option>
                            <option value="Online" {{ old('payment_method', $order->payment_method) === 'Online' ? 'selected' : '' }}>💳 Online Payment / Card</option>
                        </select>
                    </div>

                    <!-- SPECIAL NOTES -->
                    <div class="form-group">
                        <label for="notes">Special Notes / Instructions</label>
                        <textarea id="notes" name="notes" class="form-control" placeholder="e.g. Less spicy, extra sauce...">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <!-- ACTIONS -->
                    <div class="actions-box">
                        <button type="submit" class="submit-btn" id="btnSubmitOrder">
                            💾 Save & Update Order
                        </button>

                        <a href="{{ route('track.order.search', ['order_number' => $order->id]) }}" class="back-link">
                            ← Cancel & Return to Tracking
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
    // 15-minute countdown logic (strictly based on original created_at deadline)
    let remainingSeconds = {{ (int) $remainingSeconds }};
    const countdownTimerEl = document.getElementById('countdownTimer');
    const btnSubmitOrder = document.getElementById('btnSubmitOrder');
    const timerExpiredAlert = document.getElementById('timerExpiredAlert');
    const timerCard = document.getElementById('timerCard');

    function updateCountdown() {
        if (remainingSeconds <= 0) {
            countdownTimerEl.textContent = '00:00';
            btnSubmitOrder.disabled = true;
            btnSubmitOrder.textContent = '⛔ Update Window Expired';
            timerExpiredAlert.style.display = 'block';
            return;
        }

        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        countdownTimerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        remainingSeconds--;
        setTimeout(updateCountdown, 1000);
    }

    updateCountdown();

    // Toggle Dine In / Delivery fields
    function toggleOrderTypeFields() {
        const isDineIn = document.getElementById('type_dinein').checked;
        const isDelivery = document.getElementById('type_delivery').checked;

        document.getElementById('tableFieldContainer').style.display = isDineIn ? 'block' : 'none';
        document.getElementById('addressFieldContainer').style.display = isDelivery ? 'block' : 'none';
    }

    // Dynamic Items calculation and management
    const itemsListContainer = document.getElementById('itemsListContainer');
    const grandTotalText = document.getElementById('grandTotalText');
    let itemIndexCounter = {{ count($order->items) + 100 }};

    function recalculateTotal() {
        let grandTotal = 0;
        const rows = itemsListContainer.querySelectorAll('.item-row');

        rows.forEach(row => {
            const price = parseFloat(row.querySelector('.item-price-val').value) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            const subtotal = price * qty;
            row.querySelector('.subtotal-val').textContent = subtotal.toFixed(2);
            grandTotal += subtotal;
        });

        grandTotalText.textContent = grandTotal.toFixed(2);

        if (rows.length === 0) {
            btnSubmitOrder.disabled = true;
        } else if (remainingSeconds > 0) {
            btnSubmitOrder.disabled = false;
        }
    }

    // Attach event listeners to row controls
    function attachRowListeners(row) {
        const btnMinus = row.querySelector('.btn-minus');
        const btnPlus = row.querySelector('.btn-plus');
        const qtyInput = row.querySelector('.qty-input');
        const btnRemove = row.querySelector('.item-remove-btn');

        btnMinus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) {
                qtyInput.value = val - 1;
                recalculateTotal();
            }
        });

        btnPlus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val < 99) {
                qtyInput.value = val + 1;
                recalculateTotal();
            }
        });

        btnRemove.addEventListener('click', () => {
            const rows = itemsListContainer.querySelectorAll('.item-row');
            if (rows.length <= 1) {
                alert('An order must contain at least 1 item. If you want to cancel the entire order, please use the Cancel option on the Track Order page.');
                return;
            }
            row.remove();
            recalculateTotal();
        });
    }

    // Attach listeners to initial rows
    document.querySelectorAll('.item-row').forEach(attachRowListeners);

    // Add new food item to order
    document.getElementById('btnAddFood').addEventListener('click', () => {
        const selector = document.getElementById('foodSelector');
        const selectedOption = selector.options[selector.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            alert('Please select a food item to add.');
            return;
        }

        const foodId = selectedOption.value;
        const foodName = selectedOption.dataset.name;
        const price = parseFloat(selectedOption.dataset.price) || 0;

        // Check if item is already in list
        let existingRow = null;
        itemsListContainer.querySelectorAll('.item-row').forEach(row => {
            const hiddenFoodId = row.querySelector('input[name*="[food_id]"]')?.value;
            if (hiddenFoodId === foodId) {
                existingRow = row;
            }
        });

        if (existingRow) {
            const qtyInput = existingRow.querySelector('.qty-input');
            qtyInput.value = (parseInt(qtyInput.value) || 1) + 1;
            recalculateTotal();
        } else {
            const idx = itemIndexCounter++;
            const newRow = document.createElement('div');
            newRow.className = 'item-row';
            newRow.dataset.index = idx;
            newRow.innerHTML = `
                <input type="hidden" name="items[${idx}][food_id]" value="${foodId}">
                <input type="hidden" name="items[${idx}][food_name]" value="${foodName}">
                <input type="hidden" name="items[${idx}][price]" class="item-price-val" value="${price}">

                <div class="item-info">
                    <div class="item-name">${foodName}</div>
                    <div class="item-price">Rs. ${price.toFixed(2)} each</div>
                </div>

                <div class="qty-control">
                    <button type="button" class="qty-btn btn-minus">−</button>
                    <input type="number" name="items[${idx}][quantity]" class="qty-input" value="1" min="1" max="99" readonly>
                    <button type="button" class="qty-btn btn-plus">+</button>
                </div>

                <div class="item-subtotal">Rs. <span class="subtotal-val">${price.toFixed(2)}</span></div>

                <button type="button" class="item-remove-btn" title="Remove item"><i class="fas fa-trash"></i></button>
            `;

            itemsListContainer.appendChild(newRow);
            attachRowListeners(newRow);
            recalculateTotal();
        }

        // Reset selector
        selector.value = '';
    });
</script>

</body>
</html>
