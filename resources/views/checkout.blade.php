<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
.table-option {
    position: relative;
}

.table-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.table-option label {
    display: flex;
    height: 65px;
    align-items: center;
    justify-content: center;
    border: 2px solid #ddd;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    background: white;
    transition: all .2s ease;
}

.table-option label:hover {
    border-color: #ff6b00;
    color: #ff6b00;
    transform: translateY(-2px);
}

.table-option input:checked + label {
    background: #ff6b00;
    color: white;
    border-color: #ff6b00;
    box-shadow: 0 5px 15px rgba(255,107,0,.25);
}

.table-option.occupied label {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
    cursor: not-allowed;
}

.table-option.occupied label::after {
    content: " 🔒";
}
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 0 0 40px 0;
        }

        .container {
            max-width: 1150px;
            margin: auto;
            padding: 30px 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 32px;
            color: #111827;
        }

        .header p {
            color: #777;
            margin-top: 7px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .card h2 {
            margin-bottom: 20px;
            color: #111827;
        }

        /* ORDER TYPE */

        .order-types {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .order-type {
            position: relative;
        }

        .order-type input {
            display: none;
        }

        .order-type label {
            display: block;
            padding: 18px 10px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: .2s;
            background: #fff;
        }

        .order-type label:hover {
            border-color: #ff6b00;
        }

        .order-type input:checked + label {
            border-color: #ff6b00;
            background: #fff7ed;
            color: #ff6b00;
            box-shadow: 0 4px 15px rgba(255,107,0,.12);
        }

        .type-icon {
            font-size: 30px;
            display: block;
            margin-bottom: 7px;
        }

        .type-name {
            font-weight: bold;
            font-size: 15px;
        }

        /* FORM */

        .form-group {
            margin-bottom: 18px;
        }

        label.form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255,107,0,.1);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* TABLES */

        #dineInSection {
            display: none;
            margin-bottom: 20px;
        }

        .table-title {
            font-weight: bold;
            margin-bottom: 12px;
        }

        .tables {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .table-option input {
            display: none;
        }

        .table-option label {
            display: flex;
            height: 65px;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            background: white;
            transition: .2s;
        }

        .table-option label:hover {
            border-color: #ff6b00;
        }

        .table-option input:checked + label {
            background: #ff6b00;
            color: white;
            border-color: #ff6b00;
        }

        .table-option.occupied label {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
            cursor: not-allowed;
        }

        .table-option.occupied label::after {
            content: " 🔒";
        }

        /* ITEMS */

        .item {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .item-name {
            font-weight: bold;
        }

        .item-info {
            color: #777;
            font-size: 14px;
            margin-top: 5px;
        }

        .item-price {
            color: #ff6b00;
            font-weight: bold;
            white-space: nowrap;
        }

        .total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-size: 22px;
            font-weight: bold;
        }

        .total span:last-child {
            color: #ff6b00;
        }

        .place-order {
            width: 100%;
            background: #ff6b00;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        .place-order:hover {
            background: #e85f00;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        @media(max-width: 800px) {

            body {
                padding: 15px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .order-types {
                grid-template-columns: 1fr;
            }

            .tables {
                grid-template-columns: repeat(4, 1fr);
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
        <span class="logo-icon"><i class="fas fa-utensils"></i></span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>
        <a href="{{ url('/') }}">
            <i class="fas fa-home"></i> Home
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

        <a href="{{ route('track.order') }}">
            <i class="fas fa-map-marker-alt"></i> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count" id="navCartCount">{{ collect(session()->get('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<div class="container">

    <div class="header">

        <h1><i class="fas fa-file-invoice"></i> Checkout</h1>

        <p>
            Choose your order type and enter your information
        </p>

    </div>


    @if($errors->any())

        <div class="error">

            <strong>Please fix these errors:</strong>

            <ul style="margin-left:20px;margin-top:8px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="grid">


        <!-- CUSTOMER -->

        <div class="card">

            <h2>🛍️ Order Type</h2>


            <form
                action="{{ route('order.place') }}"
                method="POST"
                id="checkoutForm"
            >

                @csrf


                <!-- ORDER TYPES -->

                <div class="order-types">


                    <div class="order-type">

                        <input
                            type="radio"
                            name="order_type"
                            value="Delivery"
                            id="delivery"
                            checked
                            onchange="changeOrderType()"
                        >

                        <label for="delivery">

                            <span class="type-icon">
                                🚚
                            </span>

                            <span class="type-name">
                                Delivery
                            </span>

                        </label>

                    </div>


                    <div class="order-type">

                        <input
                            type="radio"
                            name="order_type"
                            value="Dine In"
                            id="dinein"
                            onchange="changeOrderType()"
                        >

                        <label for="dinein">

                            <span class="type-icon">
                                🍽️
                            </span>

                            <span class="type-name">
                                Dine In
                            </span>

                        </label>

                    </div>


                    <div class="order-type">

                        <input
                            type="radio"
                            name="order_type"
                            value="Takeaway"
                            id="takeaway"
                            onchange="changeOrderType()"
                        >

                        <label for="takeaway">

                            <span class="type-icon">
                                🛍️
                            </span>

                            <span class="type-name">
                                Takeaway
                            </span>

                        </label>

                    </div>


                </div>


                <!-- CUSTOMER INFORMATION -->

                <h2>👤 Customer Information</h2>


                <div class="form-group">

                    <label class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="customer_name"
                        value="{{ old('customer_name', session('customer_name', '')) }}"
                        placeholder="Enter customer name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', app('\App\Http\Controllers\CustomerAuthController')->getCurrentCustomerForCheckout()) }}"
                        placeholder="0300-1234567"
                        required
                    >

                </div>

                <!-- EMAIL (optional) -->

                <div
                    class="form-group"
                >

                    <label class="form-label">
                        Email (optional — for order updates)
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', app('\App\Http\Controllers\CustomerAuthController')->getCurrentEmailForCheckout()) }}"
                        placeholder="your@email.com"
                    >

                </div>


                <!-- DELIVERY ADDRESS -->

                <div
                    class="form-group"
                    id="addressSection"
                >

                    <label class="form-label">
                        Delivery Address
                    </label>

                    <textarea
                        name="address"
                        id="address"
                        placeholder="Enter complete delivery address"
                        oninput="debounceCalcDelivery()"
                    >{{ old('address') }}</textarea>

                    <input type="hidden" name="customer_lat" id="customerLat" value="{{ old('customer_lat') }}">
                    <input type="hidden" name="customer_lng" id="customerLng" value="{{ old('customerLng') }}">

                    <!-- Delivery Info Box -->
                    <div id="deliveryInfo" style="display:none;margin-top:12px;padding:14px;border-radius:10px;background:#f0fdf4;border:1px solid #bbf7d0;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                            <i class="fas fa-truck" style="color:#16a34a;font-size:18px;"></i>
                            <strong style="color:#166534;">Delivery Details</strong>
                        </div>
                        <div id="deliveryDetails" style="font-size:13px;color:#374151;line-height:1.8;"></div>
                    </div>

                    <!-- Free delivery banner -->
                    <div id="freeDeliveryBanner" style="display:none;margin-top:8px;padding:8px 12px;border-radius:8px;background:#dcfce7;color:#166534;font-size:13px;font-weight:600;text-align:center;">
                        🎉 Free delivery within {{ \App\Services\DeliveryCalculator::FREE_DELIVERY_KM }} km!
                    </div>

                    <!-- Map Picker -->
                    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
                        <button type="button" onclick="useMyLocation()" style="padding:8px 14px;border:1px solid #d1d5db;border-radius:8px;background:#fff;cursor:pointer;font-size:13px;color:#374151;">
                            <i class="fas fa-location-crosshairs" style="color:#ff6b00;"></i> Use My Current Location
                        </button>
                        <button type="button" onclick="calcDeliveryFromAddress()" id="calcAddrBtn" style="padding:8px 14px;border:1px solid #16a34a;border-radius:8px;background:#f0fdf4;cursor:pointer;font-size:13px;color:#166534;font-weight:600;display:none;">
                            <i class="fas fa-truck"></i> Calculate Delivery
                        </button>
                    </div>

                </div>


             <div id="dineInSection" style="display:none;">

   <select
        name="table_id"
        id="table_id"
    >
        <option value="">
            -- Choose Table --
        </option>

        @forelse($tables as $table)

            <option
                value="{{ $table->id }}"
                {{ old('table_id') == $table->id ? 'selected' : '' }}
            >
                Table {{ $table->table_number }}
            </option>

        @empty

            <option value="" disabled>
                No tables are currently available
            </option>

        @endforelse

    </select>


</div>


                <!-- PAYMENT -->

                <div class="form-group">

                    <label class="form-label">
                        Payment Method
                    </label>

                    <input type="hidden" name="payment_method" value="Cash">
                    <div style="padding:12px;background:#f0fdf4;border:2px solid #16a34a;border-radius:8px;color:#166534;font-weight:600;">
                        <i class="fas fa-money-bill-wave"></i> Cash Payment
                    </div>

                </div>


                <!-- NOTES -->

                <div class="form-group">

                    <label class="form-label">
                        Order Notes
                    </label>

                    <textarea
                        name="notes"
                        placeholder="Any special instructions? (Optional)"
                    >{{ old('notes') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="place-order"
                >
                    <i class="fas fa-paper-plane"></i> Place Order
                </button>


            </form>


            <a
                href="{{ route('cart') }}"
                class="back"
            >
                ← Back to Cart
            </a>

        </div>


        <!-- ORDER SUMMARY -->

        <div class="card">

            <h2><i class="fas fa-shopping-cart"></i> Order Summary</h2>


            @foreach($cart as $item)

                <div class="item">

                    <div>

                        <div class="item-name">
                            {{ $item['name'] }}
                        </div>

                        @if(!empty($item['variant_name']))
                            <div style="font-size:12px; color:#2563eb; font-weight:bold; margin: 1px 0 3px;">
                                <i class="fas fa-tag"></i> Size: {{ $item['variant_name'] }}
                            </div>
                        @endif

                        @if($item['is_deal'] ?? false)
                            <small style="color:#16a34a; font-weight:bold;">Deal items: {{ $item['included_items'] ?? 'Complete bundle' }}</small>
                        @endif

                        <div class="item-info">

                            {{ $item['quantity'] }}
                            ×
                            Rs. {{ number_format($item['price'], 2) }}

                        </div>

                    </div>


                    <div class="item-price">

                        Rs.
                        {{ number_format(
                            $item['price'] * $item['quantity'],
                            2
                        ) }}

                    </div>

                </div>

            @endforeach


            <!-- Estimated Time (always shown for delivery) -->
            <div id="summaryTime" style="display:none;padding:10px 0;border-bottom:1px solid #eee;">
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:#6b7280;"><i class="fas fa-clock"></i> Estimated Delivery</span>
                    <span id="summaryDeliveryTime" style="color:#2563eb;font-weight:600;">35 min</span>
                </div>
                <div id="summaryReadyTime" style="font-size:12px;color:#9ca3af;margin-top:3px;">Food ready in ~15 min</div>
            </div>

            <!-- Delivery Charges -->
            <div id="summaryDelivery" style="display:none;padding:10px 0;border-bottom:1px solid #eee;">
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:#6b7280;"><i class="fas fa-truck"></i> Delivery Charges</span>
                    <span id="summaryDeliveryCharges" style="color:#16a34a;font-weight:600;">--</span>
                </div>
                <div id="summaryDeliveryDistance" style="font-size:12px;color:#9ca3af;margin-top:3px;"></div>
            </div>

            <!-- Estimated Time -->
            <div id="summaryTime" style="display:none;padding:10px 0;border-bottom:1px solid #eee;">
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:#6b7280;"><i class="fas fa-clock"></i> Estimated Delivery</span>
                    <span id="summaryDeliveryTime" style="color:#2563eb;font-weight:600;">--</span>
                </div>
                <div id="summaryReadyTime" style="font-size:12px;color:#9ca3af;margin-top:3px;"></div>
            </div>

            <div class="total">

                <span>Total</span>

                <span>
                    Rs. <span id="grandTotal">{{ number_format($total, 2) }}</span>
                </span>

            </div>

            <input type="hidden" name="delivery_charges" id="deliveryChargesInput" value="0">
            <input type="hidden" name="delivery_time_min" id="deliveryTimeInput" value="">
            <input type="hidden" name="delivery_distance_km" id="deliveryDistanceInput" value="">

        </div>


    </div>

</div>

<script>

function changeOrderType() {

    const delivery = document.getElementById('delivery').checked;
    const dinein = document.getElementById('dinein').checked;
    const takeaway = document.getElementById('takeaway').checked;

    const addressSection = document.getElementById('addressSection');
    const address = document.getElementById('address');
    const dineInSection = document.getElementById('dineInSection');

    const tableInputs =
        document.querySelectorAll('input[name="table_id"]');


    // DELIVERY
    if (delivery) {

        addressSection.style.display = 'block';
        address.required = true;

        dineInSection.style.display = 'none';

        tableInputs.forEach(input => {
            input.required = false;
            input.checked = false;
        });
    }


    // DINE IN
    if (dinein) {

        addressSection.style.display = 'none';
        address.required = false;

        dineInSection.style.display = 'block';

        tableInputs.forEach(input => {

            if (!input.disabled) {
                input.required = true;
            }

        });
    }


    // TAKEAWAY
    if (takeaway) {

        addressSection.style.display = 'none';
        address.required = false;

        dineInSection.style.display = 'none';

        tableInputs.forEach(input => {
            input.required = false;
            input.checked = false;
        });
    }

}


document.addEventListener('DOMContentLoaded', function () {

    changeOrderType();

});
    function updateOrderType() {

    const orderType = document.querySelector(
        'input[name="order_type"]:checked'
    )?.value;

    const addressGroup = document.getElementById('addressGroup');
    const address = document.getElementById('address');

    if (!addressGroup || !address) {
        return;
    }

    if (orderType === 'Delivery') {

        addressGroup.style.display = 'block';

        address.required = true;

        address.placeholder =
            'Enter complete delivery address';

    } else {

        addressGroup.style.display = 'none';

        address.required = false;

        address.value = '';

    }
}


document.addEventListener('DOMContentLoaded', function () {

    updateOrderType();

    document
        .querySelectorAll('input[name="order_type"]')
        .forEach(function (radio) {

            radio.addEventListener(
                'change',
                updateOrderType
            );

        });

});

// === DELIVERY CHARGE CALCULATION ===
var cartTotal = {{ $total }};
var deliveryDebounce = null;

function debounceCalcDelivery() {
    var address = document.getElementById('address').value.trim();
    var calcBtn = document.getElementById('calcAddrBtn');
    if (address.length >= 5) {
        calcBtn.style.display = 'inline-block';
    } else {
        calcBtn.style.display = 'none';
    }
    clearTimeout(deliveryDebounce);
    deliveryDebounce = setTimeout(calcDeliveryFromAddress, 1200);
}

function calcDeliveryFromAddress() {
    var address = document.getElementById('address').value.trim();
    if (address.length < 5) return;

    // Try Nominatim geocoding
    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address) + '&limit=1&countrycodes=pk', {
        headers: { 'Accept-Language': 'en' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data && data.length > 0) {
            var lat = parseFloat(data[0].lat);
            var lng = parseFloat(data[0].lon);
            document.getElementById('customerLat').value = lat;
            document.getElementById('customerLng').value = lng;
            fetchDeliveryCharges(lat, lng);
        } else {
            // Fallback: use default city-center estimate
            useFallbackCharges();
        }
    })
    .catch(function() {
        useFallbackCharges();
    });
}

function useFallbackCharges() {
    // Default: assume 3 km (base delivery charge)
    // Calculate rough prep time from cart items shown on page
    var items = document.querySelectorAll('.item');
    var roughPrep = 15;
    items.forEach(function(el) {
        var qtyText = el.querySelector('.item-info');
        if (qtyText) {
            var match = qtyText.textContent.match(/(\d+)\s*×/);
            if (match) {
                var qty = parseInt(match[1]);
                // Rough: batches of 3, each batch adds 40% of base 15 min
                var batches = Math.ceil(qty / 3);
                roughPrep = Math.max(roughPrep, 15 + ((batches - 1) * 6));
            }
        }
    });
    var fallbackData = {
        distance_km: 3,
        delivery_charges: 50,
        delivery_message: 'Delivery charges: Rs. 50',
        is_free_delivery: false,
        delivery_time_min: 35 + (roughPrep - 15),
        is_within_radius: true,
        max_km: 25,
        estimated_ready_min: roughPrep
    };
    document.getElementById('customerLat').value = '';
    document.getElementById('customerLng').value = '';
    fetchDeliveryCharges(fallbackData);
}

function useMyLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser.');
        return;
    }
    navigator.geolocation.getCurrentPosition(function(pos) {
        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;
        document.getElementById('customerLat').value = lat;
        document.getElementById('customerLng').value = lng;

        // Reverse geocode to fill address
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng, {
            headers: { 'Accept-Language': 'en' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.display_name) {
                document.getElementById('address').value = data.display_name;
            }
        })
        .catch(function() {});

        fetchDeliveryCharges(lat, lng);
    }, function(err) {
        alert('Unable to get your location. Please enter your address manually.');
    });
}

function fetchDeliveryCharges(latOrData, lng) {
    // If called with pre-built data object (fallback)
    if (typeof latOrData === 'object' && latOrData !== null) {
        updateDeliveryUI(latOrData);
        return;
    }
    fetch('/api/delivery-calc?lat=' + latOrData + '&lng=' + lng, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        updateDeliveryUI(data);
    })
    .catch(function() {
        useFallbackCharges();
    });
}

function updateDeliveryUI(data) {
    var infoBox = document.getElementById('deliveryInfo');
    var details = document.getElementById('deliveryDetails');
    var summaryDelivery = document.getElementById('summaryDelivery');
    var summaryTime = document.getElementById('summaryTime');
    var freeBanner = document.getElementById('freeDeliveryBanner');

    if (!data.is_within_radius) {
        infoBox.style.display = 'block';
        infoBox.style.background = '#fef2f2';
        infoBox.style.borderColor = '#fecaca';
        details.innerHTML = '<span style="color:#dc2626;">🚫 Sorry, delivery is only available within ' + data.max_km + ' km of the restaurant.</span>';
        summaryDelivery.style.display = 'none';
        summaryTime.style.display = 'none';
        freeBanner.style.display = 'none';
        return;
    }

    infoBox.style.display = 'block';
    infoBox.style.background = '#f0fdf4';
    infoBox.style.borderColor = '#bbf7d0';

    var chargesText = data.is_free_delivery
        ? '<span style="color:#16a34a;font-weight:700;">🎉 FREE Delivery!</span>'
        : '<span style="color:#ea580c;font-weight:700;">Rs. ' + data.delivery_charges + '</span>';

    details.innerHTML =
        '<div>📍 Distance: <strong>' + data.distance_km + ' km</strong></div>' +
        '<div>🚚 Delivery: ' + chargesText + '</div>' +
        '<div>⏱️ Estimated delivery: <strong>' + data.delivery_time_min + ' min</strong></div>' +
        '<div>👨‍🍳 Food ready in: <strong>' + data.estimated_ready_min + ' min</strong></div>';

    // Update summary
    summaryDelivery.style.display = 'block';
    document.getElementById('summaryDeliveryCharges').textContent = data.is_free_delivery ? 'FREE' : 'Rs. ' + data.delivery_charges;
    document.getElementById('summaryDeliveryCharges').style.color = data.is_free_delivery ? '#16a34a' : '#ea580c';
    document.getElementById('summaryDeliveryDistance').textContent = data.distance_km + ' km away';

    summaryTime.style.display = 'block';
    document.getElementById('summaryDeliveryTime').textContent = data.delivery_time_min + ' min';
    document.getElementById('summaryReadyTime').textContent = 'Food ready in ~' + data.estimated_ready_min + ' min';

    // Free delivery banner
    freeBanner.style.display = data.is_free_delivery ? 'block' : 'none';

    // Update hidden inputs
    document.getElementById('deliveryChargesInput').value = data.delivery_charges;
    document.getElementById('deliveryTimeInput').value = data.delivery_time_min;
    document.getElementById('deliveryDistanceInput').value = data.distance_km;

    // Update grand total
    var grandTotal = cartTotal + data.delivery_charges;
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}


</script>


</body>

</html>
