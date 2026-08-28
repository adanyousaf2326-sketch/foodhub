<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - FoodHub</title>

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
        <span class="logo-icon">🍔</span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>
        <a href="{{ url('/') }}">
            <span>🏠</span> Home
        </a>

        <a href="{{ url('/#categories') }}">
            <span>📂</span> Categories
        </a>

        <a href="{{ url('/#full-menu') }}">
            <span>📋</span> Menu
        </a>

        <a href="{{ url('/#announcement') }}" class="announcement-nav">
            <span>📣</span> New Deals
        </a>

        <a href="{{ route('track.order') }}">
            <span>📍</span> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <span>🛒</span> Cart
            <span class="cart-count" id="navCartCount">{{ collect(session()->get('cart', []))->sum('quantity') }}</span>
        </a>
    </div>
</nav>

<div class="container">

    <div class="header">

        <h1>🧾 Checkout</h1>

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
                        value="{{ old('customer_name') }}"
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
                        value="{{ old('phone') }}"
                        placeholder="0300-1234567"
                        required
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
                    >{{ old('address') }}</textarea>

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

                    <select
                        name="payment_method"
                        required
                    >

                        <option value="Cash on Delivery">
                            Cash
                        </option>

                        <option value="Cash">
                            Cash
                        </option>

                    </select>

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
                    🚀 Place Order
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

            <h2>🛒 Order Summary</h2>


            @foreach($cart as $item)

                <div class="item">

                    <div>

                        <div class="item-name">
                            {{ $item['name'] }}
                        </div>

                        @if($item['is_deal'] ?? false)
                            <small>Deal items: {{ $item['included_items'] ?? 'Complete bundle' }}</small>
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


            <div class="total">

                <span>Total</span>

                <span>
                    Rs. {{ number_format($total, 2) }}
                </span>

            </div>

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


</script>


</body>

</html>
