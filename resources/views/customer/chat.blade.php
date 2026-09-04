<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Order #{{ $order->id }}</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff6b00">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; height: 100vh; display: flex; flex-direction: column; }
        .chat-header { background: linear-gradient(135deg, #ff6b00, #ff8c33); color: white; padding: 14px 20px; display: flex; align-items: center; gap: 12px; }
        .chat-header .back { color: white; text-decoration: none; font-size: 18px; }
        .chat-header .title { flex: 1; }
        .chat-header .title h2 { font-size: 16px; }
        .chat-header .title p { font-size: 12px; opacity: 0.9; }
        .online-dot { width: 8px; height: 8px; background: #4ade80; border-radius: 50%; display: inline-block; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; }
        .msg { max-width: 75%; padding: 10px 14px; border-radius: 16px; font-size: 14px; line-height: 1.4; position: relative; }
        .msg.customer { align-self: flex-end; background: #ff6b00; color: white; border-bottom-right-radius: 4px; }
        .msg.admin { align-self: flex-start; background: white; color: #111; border-bottom-left-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .msg .time { font-size: 10px; opacity: 0.7; margin-top: 4px; display: block; }
        .msg.admin .sender { font-size: 11px; font-weight: 700; color: #ff6b00; margin-bottom: 2px; }
        .typing-indicator { align-self: flex-start; background: white; padding: 10px 16px; border-radius: 16px; display: none; }
        .typing-indicator span { display: inline-block; width: 6px; height: 6px; background: #9ca3af; border-radius: 50%; margin: 0 2px; animation: typing 1.2s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%,60%,100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }
        .chat-input { background: white; padding: 12px 16px; display: flex; gap: 10px; border-top: 1px solid #e5e7eb; }
        .chat-input input { flex: 1; padding: 10px 16px; border: 2px solid #e5e7eb; border-radius: 20px; font-size: 14px; outline: none; }
        .chat-input input:focus { border-color: #ff6b00; }
        .chat-input button { background: #ff6b00; color: white; border: none; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; font-size: 16px; transition: .2s; }
        .chat-input button:hover { background: #e85f00; transform: scale(1.05); }
        .system-msg { text-align: center; color: #9ca3af; font-size: 12px; padding: 8px; }
        @media (max-width: 600px) {
            .msg { max-width: 85%; }
        }
    </style>
</head>
<body>

<div class="chat-header">
    <a href="{{ url('/') }}" class="back"><i class="fas fa-arrow-left"></i></a>
    <div class="title">
        <h2>💬 Chat with Restaurant</h2>
        <p>Order #{{ $order->id }} · <span class="online-dot"></span> Online</p>
    </div>
</div>

<div class="chat-messages" id="chatMessages">
    @forelse($messages as $msg)
        <div class="msg {{ $msg->sender_type === 'customer' ? 'customer' : 'admin' }}">
            @if($msg->sender_type !== 'customer')
                <div class="sender">🍽️ {{ $msg->sender_name }}</div>
            @endif
            {{ $msg->message }}
            <span class="time">{{ $msg->created_at->format('h:i A') }}</span>
        </div>
    @empty
        <div class="system-msg">💬 Start chatting with the restaurant about your order!</div>
    @endforelse

    <div class="typing-indicator" id="typingIndicator">
        <span></span><span></span><span></span>
    </div>
</div>

<div class="chat-input">
    <input type="text" id="messageInput" placeholder="Type a message..." onkeypress="if(event.key==='Enter')sendMessage()">
    <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
</div>

<script>
var orderId = {{ $order->id }};
var lastMessageId = {{ $messages->last()->id ?? 0 }};

function scrollToBottom() {
    var chat = document.getElementById('chatMessages');
    chat.scrollTop = chat.scrollHeight;
}

scrollToBottom();

function sendMessage() {
    var input = document.getElementById('messageInput');
    var text = input.value.trim();
    if (!text) return;

    // Add message to UI immediately
    var chat = document.getElementById('chatMessages');
    var typing = document.getElementById('typingIndicator');
    chat.insertBefore(typing, null);

    var msgDiv = document.createElement('div');
    msgDiv.className = 'msg customer';
    msgDiv.innerHTML = text + '<span class="time">Just now</span>';
    chat.insertBefore(msgDiv, typing);
    input.value = '';
    scrollToBottom();

    // Send to server
    fetch('/track-order/' + orderId + '/message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message: text,
            sender_type: 'customer',
            sender_name: '{{ session("customer_name", "Customer") }}',
        }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) lastMessageId = data.last_id || lastMessageId;
    })
    .catch(function() {});
}

// Poll for new messages every 3 seconds
function pollMessages() {
    fetch('/track-order/' + orderId + '/messages')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var chat = document.getElementById('chatMessages');
            var typing = document.getElementById('typingIndicator');

            (data.messages || []).forEach(function(msg) {
                if (msg.id <= lastMessageId) return;
                lastMessageId = msg.id;

                var div = document.createElement('div');
                div.className = 'msg ' + (msg.sender_type === 'customer' ? 'customer' : 'admin');
                var sender = msg.sender_type !== 'customer'
                    ? '<div class="sender">🍽️ ' + msg.sender_name + '</div>'
                    : '';
                div.innerHTML = sender + msg.message + '<span class="time">' + msg.time + '</span>';
                chat.insertBefore(div, typing);
            });
            scrollToBottom();
        })
        .catch(function() {});
}

setInterval(pollMessages, 3000);

// PWA Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(function() {});
}
</script>

</body>
</html>
