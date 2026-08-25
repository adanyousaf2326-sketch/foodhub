<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - FoodHub</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #222;
        }

        nav {
            background: #111827;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            color: #ff6b00;
            font-size: 26px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-icon {
            margin-right: 4px;
        }

        .hub-brand {
            color: white;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
            padding: 10px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        nav a:hover {
            background: #ff6b00;
        }

        .track-active {
            background: #2563eb;
        }

        .announcement-nav {
            background: #16a34a;
        }

        .cart-nav {
            background: #ff6b00;
        }

        .cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 21px;
            height: 21px;
            padding: 0 6px;
            margin-left: 4px;
            border-radius: 50%;
            background: white;
            color: #ff6b00;
            font-size: 12px;
            font-weight: bold;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title .icon {
            font-size: 55px;
            margin-bottom: 10px;
        }

        .title h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .title p {
            color: #777;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, .10);
        }

        .track-btn,
        .cancel-btn,
        .edit-order-btn,
        .request-edit-btn {
            width: 100%;
            border: none;
            color: white;
            padding: 14px;
            border-radius: 9px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .track-btn {
            background: #ff6b00;
        }

        .track-btn:hover {
            background: #e85f00;
            transform: translateY(-1px);
        }

        .edit-order-btn {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            margin-top: 15px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }

        .edit-order-btn:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
        }

        .request-edit-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            margin-top: 15px;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .request-edit-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-1px);
        }

        .cancel-btn {
            background: #dc2626;
            margin-top: 10px;
        }

        .cancel-btn:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .order-box {
            margin-top: 30px;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
        }

        .order-box.cancelled-order {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .order-box h2 {
            margin-bottom: 16px;
        }

        .order-box p {
            margin-top: 11px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-preparing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-delivered {
            background: #dcfce7;
            color: #166534;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .cancel-time {
            margin-top: 18px;
            padding: 14px;
            background: #fff7ed;
            color: #c2410c;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
            border: 1px solid #fed7aa;
        }

        .cancel-expired {
            margin-top: 18px;
            padding: 14px;
            background: #f3f4f6;
            color: #4b5563;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.6;
        }

        .countdown-box {
            margin-top: 15px;
            padding: 15px;
            background: #ffffff;
            border: 1px solid #fde68a;
            border-radius: 10px;
            text-align: center;
        }

        .countdown-label {
            display: block;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .countdown {
            font-size: 25px;
            font-weight: bold;
            color: #dc2626;
            letter-spacing: 1px;
        }

        .info {
            margin-top: 30px;
            padding: 18px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .info h3 {
            margin-bottom: 10px;
        }

        .info p {
            color: #666;
            line-height: 1.6;
        }

        .success {
            margin-bottom: 20px;
            padding: 14px;
            background: #dcfce7;
            color: #166534;
            border-radius: 10px;
        }

        .error {
            margin-bottom: 20px;
            padding: 14px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
        }

        /* Edit Request Status */
        .edit-request-status {
            margin-top: 15px;
            padding: 14px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.6;
        }

        .edit-request-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .edit-request-accepted {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .edit-request-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Chat Widget */
        .chat-widget {
            margin-top: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: white;
        }

        .chat-header {
            padding: 14px 18px;
            background: #111827;
            color: white;
            font-weight: bold;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-messages {
            height: 250px;
            overflow-y: auto;
            padding: 14px;
            background: #f9fafb;
        }

        .chat-msg {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
        }

        .chat-msg.customer {
            align-items: flex-end;
        }

        .chat-msg.admin {
            align-items: flex-start;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .chat-msg.customer .chat-bubble {
            background: #ff6b00;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-msg.admin .chat-bubble {
            background: #e5e7eb;
            color: #111827;
            border-bottom-left-radius: 4px;
        }

        .chat-msg-meta {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 3px;
        }

        .chat-input-box {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .chat-input-box input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
        }

        .chat-input-box input:focus {
            border-color: #ff6b00;
        }

        .chat-send-btn {
            padding: 10px 20px;
            background: #ff6b00;
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .chat-send-btn:hover {
            background: #e85f00;
        }

        .chat-disabled {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-size: 13px;
        }

        @media(max-width:700px) {
            nav {
                padding: 15px 5%;
                flex-direction: column;
                gap: 15px;
            }

            nav div:last-child {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            nav a {
                margin: 4px;
                font-size: 13px;
            }

            .container {
                margin: 30px auto;
            }

            .card {
                padding: 25px 20px;
            }

            .title h1 {
                font-size: 27px;
            }

            .countdown {
                font-size: 22px;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
</head>

<body>

<nav>

    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon">&#127828;</span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div>

        <a href="{{ url('/') }}">
            <span>&#127968;</span> Home
        </a>

        <a href="{{ url('/#categories') }}">
            <span>&#128194;</span> Categories
        </a>

        <a href="{{ url('/#full-menu') }}">
            <span>&#128203;</span> Menu
        </a>

        <a href="{{ url('/#announcement') }}" class="announcement-nav">
            <span>&#128227;</span> New Deals
        </a>

        <a href="{{ route('track.order') }}" class="track-active">
            <span>&#128205;</span> Track Order
        </a>

        <a href="{{ route('cart') }}" class="cart-nav">
            <span>&#128722;</span>
            Cart
            <span class="cart-count" id="navCartCount">
                {{ collect(session()->get('cart', []))->sum('quantity') }}
            </span>
        </a>

    </div>

</nav>

<div class="container">

    <div class="card">

        <div class="title">

            <div class="icon">
                &#128230;
            </div>

            <h1>
                Track Your Order
            </h1>

            <p>
                Enter your order number to check your order status.
            </p>

        </div>

        @if(session('success'))

            <div class="success">
                &#9989; {{ session('success') }}
            </div>

        @endif

        @if(session('error'))

            <div class="error">
                &#10060; {{ session('error') }}
            </div>

        @endif

        <form
            action="{{ route('track.order.search') }}"
            method="GET"
        >

            <div class="form-group">

                <label>
                    Order Number
                </label>

                <input
                    type="text"
                    name="order_number"
                    value="{{ request('order_number') }}"
                    placeholder="Enter order number e.g. 5"
                    required
                >

            </div>

            <button
                type="submit"
                class="track-btn"
            >
                &#128269; Track Order
            </button>

        </form>

        @if(request()->has('order_number'))

            @if($order)

                @php
                    $isCancelled = $order->status === 'Cancelled';
                    $isCompleted = $order->status === 'Completed';
                    $isDelivered = $order->status === 'Delivered';

                    // Check for approved edit request
                    $approvedEditRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
                        ->where('status', 'accepted')
                        ->where('expires_at', '>', now())
                        ->latest()
                        ->first();

                    // Check for pending edit request
                    $pendingEditRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
                        ->where('status', 'pending')
                        ->latest()
                        ->first();

                    // Check for rejected edit request (most recent)
                    $rejectedEditRequest = \App\Models\OrderEditRequest::where('order_id', $order->id)
                        ->where('status', 'rejected')
                        ->latest()
                        ->first();

                    $orderDeadline = $order->created_at
                        ->copy()
                        ->addMinutes(15);

                    $canModifyOrder =
                        !$isCancelled
                        && !$isCompleted
                        && !$isDelivered
                        && now()->lt($orderDeadline);

                    // Can edit if admin approved AND within edit window
                    $canEditNow = $approvedEditRequest && now()->lt($approvedEditRequest->expires_at);

                    // Can send edit request if order is active and no pending request
                    $canSendRequest =
                        !$isCancelled
                        && !$isCompleted
                        && !$isDelivered
                        && !$pendingEditRequest
                        && !$approvedEditRequest;

                    $statusClass = match($order->status) {
                        'Preparing' => 'status-preparing',
                        'Delivered' => 'status-delivered',
                        'Completed' => 'status-completed',
                        'Cancelled' => 'status-cancelled',
                        default => 'status-pending',
                    };
                @endphp

                <div class="order-box {{ $isCancelled ? 'cancelled-order' : '' }}">

                    <h2>
                        &#128230; Order #{{ $order->id }}
                    </h2>

                    <p>
                        <strong>Status:</strong>

                        <span class="status {{ $statusClass }}">
                            {{ $order->status }}
                        </span>
                    </p>

                    <p>
                        <strong>Order Type:</strong>
                        {{ $order->order_type }}
                    </p>

                    <p>
                        <strong>Total:</strong>
                        Rs. {{ number_format($order->total_amount, 2) }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </p>

                    @if($isCancelled)

                        <div class="cancel-expired">
                            &#10060; This order has been cancelled.
                        </div>

                    @elseif($isDelivered)

                        <div class="cancel-expired">
                            &#128666; This order has already been delivered.
                            <br>
                            You can no longer update or cancel this order.
                        </div>

                    @elseif($isCompleted)

                        <div class="cancel-expired">
                            &#9989; This bill is closed and cannot be updated or cancelled.
                        </div>

                    @elseif($canEditNow)

                        {{-- Admin approved edit request - show edit button --}}
                        <div class="edit-request-status edit-request-accepted">
                            &#9989; Your edit request has been approved! You can now edit the order.
                        </div>

                        <div class="countdown-box">

                            <span class="countdown-label">
                                Time remaining to edit
                            </span>

                            <span
                                class="countdown"
                                id="orderCountdown"
                                data-deadline="{{ $approvedEditRequest->expires_at->timestamp * 1000 }}"
                            >
                                Loading...
                            </span>

                        </div>

                        <a
                            href="{{ route('track.order.edit', $order) }}"
                            class="edit-order-btn"
                        >
                            &#9998; Edit My Order
                        </a>

                        <form
                            method="POST"
                            action="{{ route('track.order.cancel', $order) }}"
                            onsubmit="return confirm('Are you sure you want to cancel this order?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="cancel-btn"
                            >
                                &#10060; Cancel My Order
                            </button>

                        </form>

                    @elseif($pendingEditRequest)

                        {{-- Pending edit request --}}
                        <div class="edit-request-status edit-request-pending">
                            &#9203; Your edit request is pending admin approval.
                            <br>
                            <small>Requested: {{ $pendingEditRequest->created_at->format('h:i A') }}</small>
                            @if($pendingEditRequest->message)
                                <br><small>Message: {{ $pendingEditRequest->message }}</small>
                            @endif
                        </div>

                    @elseif($rejectedEditRequest && !$canSendRequest)

                        {{-- Rejected edit request --}}
                        <div class="edit-request-status edit-request-rejected">
                            &#10060; Your edit request was rejected.
                            @if($rejectedEditRequest->admin_response)
                                <br><small>Reason: {{ $rejectedEditRequest->admin_response }}</small>
                            @endif
                        </div>

                    @elseif($canModifyOrder)

                        <div class="cancel-time">
                            &#9200; You can request to update or cancel this order until
                            <strong>
                                {{ $orderDeadline->format('h:i A') }}
                            </strong>.
                        </div>

                        <div class="countdown-box">

                            <span class="countdown-label">
                                Time remaining to request changes
                            </span>

                            <span
                                class="countdown"
                                id="orderCountdown"
                                data-deadline="{{ $orderDeadline->timestamp * 1000 }}"
                            >
                                Loading...
                            </span>

                        </div>

                        <form method="POST" action="{{ route('track.order.edit-request', $order) }}">
                            @csrf
                            <div class="form-group" style="margin-top:15px;">
                                <input
                                    type="text"
                                    name="message"
                                    placeholder="Why do you want to edit? (optional)"
                                    style="border:1px solid #d1d5db;border-radius:9px;padding:12px;font-size:14px;width:100%;"
                                >
                            </div>
                            <button type="submit" class="request-edit-btn">
                                &#9998; Request Edit
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('track.order.cancel', $order) }}"
                            onsubmit="return confirm('Are you sure you want to cancel this order?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="cancel-btn"
                            >
                                &#10060; Cancel My Order
                            </button>

                        </form>

                    @else

                        <div class="cancel-expired">
                            &#9200; The 15-minute modification time has expired.
                            <br>
                            This order can no longer be updated or cancelled.
                        </div>

                    @endif

                </div>

                {{-- CHAT WIDGET --}}
                @if(!$isCancelled && !$isCompleted)

                    <div class="chat-widget" id="chatWidget">
                        <div class="chat-header">
                            &#128172; Chat with Restaurant
                        </div>
                        <div class="chat-messages" id="chatMessages">
                            <div style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;">
                                Loading messages...
                            </div>
                        </div>
                        <div class="chat-input-box" id="chatInputBox">
                            <input
                                type="text"
                                id="chatInput"
                                placeholder="Type your message..."
                                maxlength="500"
                            >
                            <button class="chat-send-btn" id="chatSendBtn" onclick="sendChatMessage()">
                                Send
                            </button>
                        </div>
                    </div>

                @endif

            @else

                <div
                    class="error"
                    style="margin-top:30px;"
                >
                    &#10060; Order #{{ request('order_number') }} not found.
                    Please check your order number.
                </div>

            @endif

        @endif

        <div class="info">

            <h3>
                &#128203; How does tracking work?
            </h3>

            <p>
                Enter your order number to see its current status.
                Within 15 minutes of placing an order, you can request changes.
                After admin approval, you get 15 minutes to edit.
                You can also chat with the restaurant while your order is active.
                Cancelled orders will not be delivered or added to sales.
            </p>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    // Countdown Timer
    var countdown = document.getElementById("orderCountdown");

    if (countdown) {
        var deadline = Number(countdown.dataset.deadline);

        function updateCountdown() {
            var now = new Date().getTime();
            var remaining = deadline - now;

            if (remaining <= 0) {
                countdown.textContent = "00:00";
                var editButton = document.querySelector(".edit-order-btn");
                if (editButton) {
                    editButton.style.pointerEvents = "none";
                    editButton.style.opacity = "0.5";
                    editButton.textContent = "Edit Time Expired";
                }
                var cancelButton = document.querySelector(".cancel-btn");
                if (cancelButton) {
                    cancelButton.disabled = true;
                    cancelButton.style.opacity = "0.5";
                    cancelButton.style.cursor = "not-allowed";
                    cancelButton.textContent = "Cancel Time Expired";
                }
                clearInterval(timer);
                return;
            }

            var totalSeconds = Math.floor(remaining / 1000);
            var minutes = Math.floor(totalSeconds / 60);
            var seconds = totalSeconds % 60;
            countdown.textContent =
                String(minutes).padStart(2, "0") +
                ":" +
                String(seconds).padStart(2, "0");
        }

        updateCountdown();
        var timer = setInterval(updateCountdown, 1000);
    }

    // Chat functionality
    var orderId = {{ $order->id ?? 'null' }};
    var lastMessageId = 0;
    var chatPolling = null;

    if (orderId) {
        loadMessages();
        chatPolling = setInterval(loadMessages, 5000);
    }

    function loadMessages() {
        if (!orderId) return;

        fetch("/track-order/" + orderId + "/messages?last_id=" + lastMessageId, {
            credentials: "same-origin"
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.messages.length > 0) {
                var chatBox = document.getElementById("chatMessages");
                var isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox;

                data.messages.forEach(function(msg) {
                    appendMessage(msg);
                    lastMessageId = msg.id;
                });

                if (isScrolledToBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }
        })
        .catch(function(err) {
            console.log("Chat polling error:", err);
        });
    }

    function appendMessage(msg) {
        var chatBox = document.getElementById("chatMessages");
        var div = document.createElement("div");
        div.className = "chat-msg " + msg.sender_type;

        var time = new Date(msg.created_at).toLocaleTimeString([], {hour: "2-digit", minute: "2-digit"});

        div.innerHTML =
            '<div class="chat-bubble">' + escapeHtml(msg.message) + '</div>' +
            '<div class="chat-msg-meta">' + escapeHtml(msg.sender_name) + ' &middot; ' + time + '</div>';

        chatBox.appendChild(div);
    }

    function escapeHtml(text) {
        var div = document.createElement("div");
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    window.sendChatMessage = function() {
        var input = document.getElementById("chatInput");
        var msg = input.value.trim();

        if (!msg || !orderId) return;

        var customerName = {{ Js::from($order->customer_name ?? 'Customer') }};

        fetch("/track-order/" + orderId + "/message", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content ||
                    document.querySelector('input[name="_token"]')?.value || "",
                "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "same-origin",
            body: JSON.stringify({
                message: msg,
                customer_name: customerName
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                input.value = "";
                loadMessages();
            } else {
                alert(data.message || "Failed to send message.");
            }
        })
        .catch(function(err) {
            console.log("Send message error:", err);
            alert("Failed to send message. Please try again.");
        });
    };

    // Send on Enter key
    var chatInput = document.getElementById("chatInput");
    if (chatInput) {
        chatInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                window.sendChatMessage();
            }
        });
    }
});

</script>

</body>

</html>
