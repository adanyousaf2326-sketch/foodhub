<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order #{{ $order->id }} - FoodHub Admin</title>

    <!-- Leaflet.js CSS for Delivery Tracking Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }





        .logo {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 22px;
            font-weight: bold;

            white-space: nowrap;
        }


        .logo span {
            color: #ff6b00;
        }







        .nav a:hover {
            background: #ff6b00;
            color: white;
        }




        .website-btn {
            background: #16a34a !important;
            color: white !important;
        }


        .website-btn:hover {
            background: #15803d !important;
        }



        body {
            background: #f4f6f9;
            color: #222;
        }


        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px 20px;
        }



        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }


        h1 {
            color: #222;
        }


        .subtitle {
            color: #777;
            margin-top: 6px;
        }


        .btn {
            padding: 10px 16px;

            border-radius: 8px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            display: inline-block;

            font-size: 14px;

            font-weight: bold;
        }


        .back {
            background: #374151;
            color: white;
        }


        .back:hover {
            background: #1f2937;
        }


        .update {
            background: #ff6b00;
            color: white;
        }


        .update:hover {
            background: #e85f00;
        }


        .grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 20px;
        }



        .card {
            background: white;

            border-radius: 15px;

            padding: 25px;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);

            margin-bottom: 20px;
        }


        .card h2 {
            margin-bottom: 20px;

            color: #222;

            font-size: 20px;
        }



        .info-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 18px;
        }


        .info label {
            display: block;

            font-size: 12px;

            color: #888;

            margin-bottom: 5px;

            text-transform: uppercase;
        }


        .info strong {
            color: #222;

            line-height: 1.5;
        }



        .item {
            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 15px 0;

            border-bottom: 1px solid #eee;

            gap: 20px;
        }


        .item:last-child {
            border-bottom: none;
        }


        .item-name {
            font-weight: bold;

            color: #222;
        }


        .item-details {
            color: #777;

            font-size: 13px;

            margin-top: 5px;
        }


        .item-total {
            font-weight: bold;

            color: #16a34a;

            white-space: nowrap;
        }


        .total-box {
            margin-top: 20px;

            padding-top: 18px;

            border-top: 2px solid #eee;

            display: flex;

            justify-content: space-between;

            font-size: 20px;

            font-weight: bold;
        }


        .total {
            color: #ff6b00;
        }



        .status-box {
            text-align: center;

            padding: 20px;

            border-radius: 12px;

            background: #fff7ed;

            margin-bottom: 20px;
        }


        .status-box .label {
            color: #777;

            font-size: 13px;

            margin-bottom: 8px;
        }


        .status {
            display: inline-block;

            padding: 7px 14px;

            border-radius: 20px;

            font-weight: bold;
        }


        .pending {
            background: #fef3c7;
            color: #92400e;
        }


        .confirmed {
            background: #dbeafe;
            color: #1e40af;
        }


        .preparing {
            background: #ede9fe;
            color: #6d28d9;
        }


        .delivered {
            background: #dcfce7;
            color: #166534;
        }


        .cancelled {
            background: #fee2e2;
            color: #991b1b;
        }



        .form-group {
            margin-bottom: 18px;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-weight: bold;

            color: #444;
        }


        select,
        textarea {
            width: 100%;

            padding: 11px 12px;

            border: 1px solid #ddd;

            border-radius: 8px;

            outline: none;

            background: white;

            font-size: 14px;
        }


        select:focus,
        textarea:focus {
            border-color: #ff6b00;

            box-shadow: 0 0 0 3px rgba(255,107,0,.10);
        }


        textarea {
            resize: vertical;

            min-height: 90px;
        }



        .notes {
            background: #f8f9fa;

            padding: 15px;

            border-radius: 10px;

            color: #555;

            line-height: 1.6;
        }



        .success {
            background: #dcfce7;

            color: #166534;

            padding: 14px;

            border-radius: 8px;

            margin-bottom: 20px;

            border: 1px solid #bbf7d0;
        }



        @media(max-width: 1000px) {




        }


        @media(max-width: 850px) {

            .grid {
                grid-template-columns: 1fr;
            }


            .info-grid {
                grid-template-columns: 1fr;
            }


            .top {
                gap: 15px;

                flex-direction: column;

                align-items: flex-start;
            }

        }


        @media(max-width: 700px) {

            .container {
                padding: 25px 15px;
            }





        }


        @media(max-width: 500px) {

            .logo {
                font-size: 19px;
            }




            .item {
                align-items: flex-start;

                flex-direction: column;
            }


            .item-total {
                align-self: flex-end;
            }

        }

    </style>

    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
</head>


<body>


@include('admin.partials.topbar')


<div class="container">


    <!-- HEADER -->

    <div class="top">

        <div>

            <h1>
                📦 Order #{{ $order->id }}
            </h1>


        </div>


        <a
            href="{{ route('admin.orders.index') }}"
            class="btn back"
        >
            ← Back to Orders
        </a>

    </div>


    <!-- SUCCESS MESSAGE -->

    @if(session('success'))

        <div class="success">

            <i class="fas fa-check-circle"></i> {{ session('success') }}

        </div>

    @endif


    <div class="grid">



        <div>


            <!-- CUSTOMER -->

            <div class="card">

                <h2>
                    👤 Customer Information
                </h2>


                <div class="info-grid">


                    <div class="info">

                        <label>
                            Name
                        </label>

                        <strong>
                            {{ $order->customer_name }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Phone
                        </label>

                        <strong>
                            {{ $order->phone }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Address
                        </label>

                        <strong>
                            {{ $order->address }}
                        </strong>

                    </div>


                    <div class="info">

                        <label>
                            Payment
                        </label>

                        <strong>
                            {{ $order->payment_method }}
                        </strong>

                    </div>


                </div>

            </div>

            {{-- DELIVERY TRACKING MAP (Delivery orders only) --}}
            @if($order->order_type === 'Delivery' && $order->address)
            <div style="background:white;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;margin-bottom:24px;">
                <div style="padding:14px 18px;background:#111827;color:white;font-weight:bold;font-size:15px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-map-marked-alt"></i> Delivery Location Map
                    @if($order->status === 'Out for Delivery')
                        <span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#dbeafe;color:#1d4ed8;"><i class="fas fa-motorcycle"></i> On The Way</span>
                    @endif
                </div>
                <div id="adminTrackingMap" style="height:300px;width:100%;"></div>
                <div style="padding:12px 18px;background:#f8fafc;border-top:1px solid #e5e7eb;display:flex;gap:20px;font-size:12px;color:#6b7280;">
                    <div style="display:flex;align-items:center;gap:6px;"><span style="width:10px;height:10px;border-radius:50%;background:#ff6b00;display:inline-block;"></span> Restaurant</div>
                    <div style="display:flex;align-items:center;gap:6px;"><span style="width:10px;height:10px;border-radius:50%;background:#2563eb;display:inline-block;"></span> Delivery Address</div>
                </div>
            </div>

            <script>
            (function() {
                var address = '{{ addslashes($order->address ?? '') }}';
                var restaurantLat = 33.6844;
                var restaurantLng = 73.0479;

                var map = L.map('adminTrackingMap').setView([restaurantLat, restaurantLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                    maxZoom: 18
                }).addTo(map);

                var restaurantIcon = L.divIcon({
                    html: '<div style="background:#ff6b00;color:white;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:16px;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-utensils"></i></div>',
                    iconSize: [32, 32], iconAnchor: [16, 16], className: ''
                });
                L.marker([restaurantLat, restaurantLng], {icon: restaurantIcon}).addTo(map)
                    .bindPopup('<b>FoodHub Restaurant</b>');

                if (address) {
                    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address + ', Pakistan') + '&limit=1')
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data && data.length > 0) {
                                var lat = parseFloat(data[0].lat);
                                var lng = parseFloat(data[0].lon);
                                var deliveryIcon = L.divIcon({
                                    html: '<div style="background:#2563eb;color:white;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:16px;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-map-marker-alt"></i></div>',
                                    iconSize: [32, 32], iconAnchor: [16, 16], className: ''
                                });
                                L.marker([lat, lng], {icon: deliveryIcon}).addTo(map)
                                    .bindPopup('<b>Delivery Address</b><br>' + address);
                                L.polyline([[restaurantLat, restaurantLng], [lat, lng]],
                                    {color: '#ff6b00', weight: 3, dashArray: '10, 10', opacity: 0.7}
                                ).addTo(map);
                                map.fitBounds(L.latLngBounds([[restaurantLat, restaurantLng], [lat, lng]]), {padding: [40, 40]});
                            }
                        })
                        .catch(function() {});
                }
            })();
            </script>
            @endif


            <!-- ORDER ITEMS -->

            <div class="card">

                <h2>
                    <i class="fas fa-utensils"></i> Ordered Items
                </h2>


                @forelse($order->items as $item)


                    <div class="item">


                        <div>

                            <div class="item-name">

                                {{ $item->food_name }}
                                @if($item->size_name)
                                    <span style="color:#6b7280;font-size:13px;"> — {{ $item->size_name }}</span>
                                @endif

                            </div>


                            <div class="item-details">

                                Qty:
                                {{ $item->quantity }}

                                ×

                                Rs.
                                {{ number_format($item->price, 2) }}

                            </div>

                        </div>


                        <div class="item-total">

                            Rs.
                            {{ number_format($item->price * $item->quantity, 2) }}

                        </div>


                    </div>


                @empty


                    <p style="color:#777;">
                        No items found in this order.
                    </p>


                @endforelse


                <!-- TOTAL -->

                <div class="total-box">

                    <span>
                        Total
                    </span>


                    <span class="total">

                        Rs.
                        {{ number_format($order->total_amount, 2) }}

                    </span>

                </div>


            </div>


            <!-- NOTES -->

            @if($order->notes)


                <div class="card">

                    <h2>
                        <i class="fas fa-align-left"></i> Customer Notes
                    </h2>


                    <div class="notes">

                        {{ $order->notes }}

                    </div>

                </div>


            @endif


        </div>



        <div>


            <!-- CURRENT STATUS -->

            @php

                $statusClass = strtolower($order->status);

                if ($statusClass === 'out for delivery') {
                    $statusClass = 'preparing';
                }

            @endphp


            <div class="status-box">


                <div class="label">
                    CURRENT ORDER STATUS
                </div>


                <span class="status {{ $statusClass }}">

                    {{ $order->status }}

                </span>


            </div>


            <!-- UPDATE ORDER -->

            <div class="card">


                <h2>
                    ⚙️ Update Order
                </h2>


                <form
                    action="{{ route('admin.orders.update', $order) }}"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    <!-- STATUS -->

                    <div class="form-group">

                        <label>
                            Order Status
                        </label>


                        <select name="status">


                            <option
                                value="Pending"
                                {{ $order->status == 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>


                            <option
                                value="Confirmed"
                                {{ $order->status == 'Confirmed' ? 'selected' : '' }}
                            >
                                Confirmed
                            </option>


                            <option
                                value="Preparing"
                                {{ $order->status == 'Preparing' ? 'selected' : '' }}
                            >
                                Preparing
                            </option>


                            <option
                                value="Out for Delivery"
                                {{ $order->status == 'Out for Delivery' ? 'selected' : '' }}
                            >
                                Out for Delivery
                            </option>


                            <option
                                value="Delivered"
                                {{ $order->status == 'Delivered' ? 'selected' : '' }}
                            >
                                Delivered
                            </option>


                            <option
                                value="Cancelled"
                                {{ $order->status == 'Cancelled' ? 'selected' : '' }}
                            >
                                Cancelled
                            </option>


                        </select>

                    </div>


                    <!-- NOTES -->

                    <div class="form-group">

                        <label>
                            Notes
                        </label>


                        <textarea
                            name="notes"
                            placeholder="Add order note..."
                        >{{ $order->notes }}</textarea>

                    </div>


                    <!-- UPDATE -->

                    <button
                        type="submit"
                        class="btn update"
                        style="width:100%;"
                    >

                        💾 Update Order

                    </button>                </form>

                @if(!in_array($order->status, ['Cancelled', 'Completed', 'Delivered']))
                <a href="{{ route('admin.orders.admin-edit', $order) }}" style="display:block;margin-top:10px;padding:12px;border:none;border-radius:8px;background:#7c3aed;color:white;font-weight:bold;font-size:14px;text-align:center;text-decoration:none;">
                    <i class="fas fa-pen"></i> Edit Order (Full Edit)
                </a>
                @endif




            </div>            <!-- ORDER INFORMATION -->

            <div class="card">


                <h2>
                    🕒 Order Information
                </h2>


                <div class="info">

                    <label>
                        Order Date
                    </label>


                    <strong>
                        {{ $order->created_at->format('d M Y') }}
                    </strong>

                </div>

                <br>


                <div class="info">

                    <label>
                        Order Time
                    </label>


                    <strong>
                        {{ $order->created_at->format('h:i A') }}
                    </strong>

                </div>


            </div>


            <!-- EDIT REQUESTS -->
            @php
                $pendingEditRequests = \App\Models\OrderEditRequest::where('order_id', $order->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->get();
                $acceptedEditRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
                    ->where('status', 'accepted')
                    ->where('expires_at', '>', now())
                    ->latest()
                    ->first();
            @endphp

            @if($pendingEditRequests->count() > 0 || $acceptedEditRequest)
                <div class="card" style="border:2px solid #f59e0b;background:#fffbeb;">
                    <h2 style="color:#b45309;"><i class="fas fa-pen"></i> Edit Requests</h2>

                    @if($pendingEditRequests->count() > 0)
                        @foreach($pendingEditRequests as $req)
                            <div style="padding:14px;border:1px solid #fde68a;border-radius:10px;background:white;margin-bottom:12px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <div>
                                        <strong style="color:#92400e;">{{ $req->customer_name }}</strong>
                                        <span style="color:#777;font-size:12px;margin-left:8px;">{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:bold;">PENDING</span>
                                </div>
                                @if($req->message)
                                    <p style="margin-top:8px;color:#555;font-size:14px;">{{ $req->message }}</p>
                                @endif
                                <div style="display:flex;gap:8px;margin-top:12px;">
                                    <form method="POST" action="{{ route('admin.orders.edit-requests.accept', [$order, $req]) }}" style="flex:1;">
                                        @csrf
                                        <button type="submit" style="width:100%;padding:10px;border:none;border-radius:8px;background:#16a34a;color:white;font-weight:bold;cursor:pointer;"><i class="fas fa-check"></i> Accept</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.orders.edit-requests.reject', [$order, $req]) }}" style="flex:1;">
                                        @csrf
                                        <input type="hidden" name="admin_response" value="Request rejected by admin.">
                                        <button type="submit" style="width:100%;padding:10px;border:none;border-radius:8px;background:#dc2626;color:white;font-weight:bold;cursor:pointer;"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if($acceptedEditRequest)
                        <div style="padding:14px;border:1px solid #bbf7d0;border-radius:10px;background:white;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong style="color:#166534;"><i class="fas fa-check-circle"></i> Edit Approved</strong>
                                <span style="color:#666;font-size:12px;">Expires: {{ $acceptedEditRequest->expires_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- CHAT WITH CUSTOMER -->
            @if(!in_array($order->status, ['Cancelled', 'Completed']))
                <div class="card" style="padding:0;overflow:hidden;">
                    <div style="padding:16px 20px;background:#111827;color:white;display:flex;align-items:center;gap:8px;">
                        <h2 style="color:white;margin:0;font-size:17px;"><i class="fas fa-comments"></i> Chat with {{ $order->customer_name }}</h2>
                    </div>
                    <div id="adminChatMessages" style="height:280px;overflow-y:auto;padding:14px;background:#f9fafb;">
                        <div style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;">Loading messages...</div>
                    </div>
                    <div style="display:flex;gap:8px;padding:12px;border-top:1px solid #e5e7eb;">
                        <input type="text" id="adminChatInput" placeholder="Type a message..." maxlength="500" style="flex:1;padding:10px 14px;border:1px solid #d1d5db;border-radius:20px;font-size:14px;outline:none;">
                        <button onclick="sendAdminMessage()" style="padding:10px 20px;background:#ff6b00;color:white;border:none;border-radius:20px;font-weight:bold;cursor:pointer;">Send</button>
                    </div>
                </div>

                <script>
                (function() {
                    var orderId = {{ $order->id }};
                    var lastMsgId = 0;
                    var chatBox = document.getElementById('adminChatMessages');

                    function loadAdminMessages() {
                        fetch('/track-order/' + orderId + '/messages?last_id=' + lastMsgId, {
                            credentials: 'same-origin',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data.success && data.messages.length > 0) {
                                var isAtBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox;
                                data.messages.forEach(function(msg) {
                                    appendAdminMsg(msg);
                                    lastMsgId = msg.id;
                                });
                                if (isAtBottom) chatBox.scrollTop = chatBox.scrollHeight;
                            }
                        })
                        .catch(function() {});
                    }

                    function appendAdminMsg(msg) {
                        var isCustomer = msg.sender_type === 'customer';
                        var time = new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                        var div = document.createElement('div');
                        div.style.cssText = 'margin-bottom:12px;display:flex;flex-direction:' + (isCustomer ? 'row' : 'row-reverse') + ';align-items:flex-start;gap:8px;';
                        var bubbleBg = isCustomer ? '#e5e7eb' : '#ff6b00';
                        var bubbleColor = isCustomer ? '#111827' : 'white';
                        var align = isCustomer ? 'border-bottom-left-radius:4px;' : 'border-bottom-right-radius:4px;';
                        div.innerHTML = '<div style="max-width:75%;padding:10px 14px;border-radius:14px;font-size:13px;line-height:1.5;background:' + bubbleBg + ';color:' + bubbleColor + ';' + align + 'word-wrap:break-word;">' + escapeHtml(msg.message) + '</div>';
                        var meta = document.createElement('div');
                        meta.style.cssText = 'font-size:11px;color:#9ca3af;margin-top:3px;';
                        meta.textContent = msg.sender_name + ' \u00B7 ' + time;
                        div.appendChild(meta);
                        chatBox.appendChild(div);
                    }

                    function escapeHtml(t) {
                        var d = document.createElement('div');
                        d.appendChild(document.createTextNode(t));
                        return d.innerHTML;
                    }

                    window.sendAdminMessage = function() {
                        var input = document.getElementById('adminChatInput');
                        var msg = input.value.trim();
                        if (!msg) return;

                        fetch('/admin/orders/' + orderId + '/messages', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ message: msg })
                        })
                        .then(function(r) {
                            return r.text().then(function(text) {
                                try {
                                    var json = JSON.parse(text);
                                    return { ok: r.ok, json: json };
                                } catch(e) {
                                    return { ok: false, json: { success: false, message: 'Server error.' } };
                                }
                            });
                        })
                        .then(function(res) {
                            if (res.ok && res.json.success) {
                                input.value = '';
                                loadAdminMessages();
                            } else {
                                var errMsg = res.json.message || 'Failed to send.';
                                if (res.json.errors) {
                                    var msgs = Object.values(res.json.errors).flat();
                                    errMsg = msgs.join(', ');
                                }
                                alert(errMsg);
                            }
                        })
                        .catch(function() { alert('Network error. Please try again.'); });
                    };

                    document.getElementById('adminChatInput').addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') sendAdminMessage();
                    });

                    loadAdminMessages();
                    setInterval(loadAdminMessages, 5000);
                })();
                </script>
            @endif

        </div>


    </div>


</div>


</body>

</html>
