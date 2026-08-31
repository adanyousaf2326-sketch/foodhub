<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Payment - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', Arial, sans-serif; }
        body { background: #f0f2f5; color: #222; min-height: 100vh; display: flex; flex-direction: column; }
        
        nav { background: #111827; padding: 18px 7%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        .logo { color: #ff6b00; font-size: 26px; font-weight: bold; text-decoration: none; }
        .logo-icon { margin-right: 4px; }
        .hub-brand { color: white; }
        nav a { color: white; text-decoration: none; margin-left: 18px; padding: 10px 14px; border-radius: 8px; transition: all 0.2s ease; }
        nav a:hover { background: #ff6b00; }
        .cart-count { display: inline-flex; align-items: center; justify-content: center; min-width: 21px; height: 21px; padding: 0 6px; margin-left: 4px; border-radius: 50%; background: white; color: #ff6b00; font-size: 12px; font-weight: bold; }
        
        .container { max-width: 600px; margin: 40px auto; padding: 20px; flex: 1; }
        
        .payment-card { background: white; border-radius: 18px; box-shadow: 0 8px 30px rgba(0,0,0,0.08); overflow: hidden; }
        
        .payment-header { background: linear-gradient(135deg, #111827 0%, #1f2937 100%); padding: 30px; text-align: center; color: white; }
        .payment-header h1 { font-size: 24px; margin-bottom: 8px; }
        .payment-header .order-id { font-size: 14px; color: #9ca3af; }
        .payment-header .amount { font-size: 36px; font-weight: bold; color: #ff6b00; margin-top: 12px; }
        
        .payment-body { padding: 30px; }
        
        .order-summary { background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .order-summary h3 { font-size: 16px; margin-bottom: 12px; color: #374151; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #555; border-bottom: 1px solid #e5e7eb; }
        .summary-row:last-child { border-bottom: none; font-weight: bold; color: #111; font-size: 16px; }
        
        .payment-methods { margin-bottom: 24px; }
        .payment-methods h3 { font-size: 16px; margin-bottom: 16px; color: #374151; }
        
        .method-option { display: flex; align-items: center; gap: 14px; padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; margin-bottom: 12px; cursor: pointer; transition: all 0.2s; }
        .method-option:hover { border-color: #ff6b00; background: #fff7ed; }
        .method-option.selected { border-color: #ff6b00; background: #fff7ed; box-shadow: 0 0 0 3px rgba(255,107,0,0.1); }
        .method-option input[type="radio"] { display: none; }
        .method-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; }
        .method-icon.jazzcash { background: #dc2626; color: white; }
        .method-icon.easypaisa { background: #16a34a; color: white; }
        .method-icon.card { background: #2563eb; color: white; }
        .method-icon.cod { background: #f59e0b; color: white; }
        .method-info h4 { font-size: 15px; color: #111; margin-bottom: 2px; }
        .method-info p { font-size: 12px; color: #6b7280; }
        
        .card-form { display: none; margin-top: 16px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e5e7eb; }
        .card-form.visible { display: block; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 13px; color: #555; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 15px; outline: none; }
        .form-group input:focus { border-color: #ff6b00; box-shadow: 0 0 0 3px rgba(255,107,0,0.1); }
        .form-row { display: flex; gap: 12px; }
        .form-row .form-group { flex: 1; }
        
        .pay-btn { width: 100%; padding: 16px; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: all 0.2s; margin-top: 12px; }
        .pay-btn.primary { background: linear-gradient(135deg, #ff6b00 0%, #e85f00 100%); color: white; box-shadow: 0 4px 15px rgba(255,107,0,0.3); }
        .pay-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,0,0.4); }
        .pay-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        
        .security-note { text-align: center; margin-top: 20px; padding: 12px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0; }
        .security-note p { font-size: 12px; color: #166534; }
        
        .processing { display: none; text-align: center; padding: 40px; }
        .processing.show { display: block; }
        .spinner { width: 50px; height: 50px; border: 4px solid #e5e7eb; border-top-color: #ff6b00; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 16px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .success-view { display: none; text-align: center; padding: 40px; }
        .success-view.show { display: block; }
        .success-icon { font-size: 60px; margin-bottom: 16px; }
        .success-view h2 { color: #166534; margin-bottom: 8px; }
        .success-view p { color: #6b7280; margin-bottom: 24px; }
        
        .error-view { display: none; text-align: center; padding: 40px; }
        .error-view.show { display: block; }
        .error-icon { font-size: 60px; margin-bottom: 16px; }
        .error-view h2 { color: #991b1b; margin-bottom: 8px; }
        .error-view p { color: #6b7280; margin-bottom: 24px; }
        
        @media(max-width:700px) {
            nav { padding: 15px 5%; flex-direction: column; gap: 15px; }
            nav div:last-child { display: flex; flex-wrap: wrap; justify-content: center; }
            nav a { margin: 4px; font-size: 13px; }
            .container { margin: 20px auto; }
            .payment-header .amount { font-size: 28px; }
            .form-row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon">🍔</span>
        Food<span class="hub-brand">Hub</span>
    </a>
    <div>
        <a href="{{ url('/') }}">🏠 Home</a>
        <a href="{{ route('cart') }}" style="background:#ff6b00;">🛒 Cart</a>
    </div>
</nav>

<div class="container">
    <div class="payment-card">
        
        <!-- PAYMENT FORM -->
        <div id="paymentForm">
            <div class="payment-header">
                <h1>💳 Online Payment</h1>
                <div class="order-id">Order #{{ $order->id }}</div>
                <div class="amount">Rs. {{ number_format($order->total_amount, 2) }}</div>
            </div>
            
            <div class="payment-body">
                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>📋 Order Summary</h3>
                    @foreach($order->items as $item)
                    <div class="summary-row">
                        <span>{{ $item->quantity }}x {{ $item->food_name }}</span>
                        <span>Rs. {{ number_format($item->subtotal, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="summary-row" style="margin-top:8px;padding-top:12px;border-top:2px solid #d1d5db;">
                        <span>Total</span>
                        <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h3>💰 Select Payment Method</h3>
                    
                    <div class="method-option" onclick="selectMethod('jazzcash')">
                        <input type="radio" name="payment_method" value="JazzCash">
                        <div class="method-icon jazzcash">JC</div>
                        <div class="method-info">
                            <h4>JazzCash</h4>
                            <p>Pay via JazzCash mobile account</p>
                        </div>
                    </div>
                    
                    <div class="method-option" onclick="selectMethod('easypaisa')">
                        <input type="radio" name="payment_method" value="EasyPaisa">
                        <div class="method-icon easypaisa">EP</div>
                        <div class="method-info">
                            <h4>EasyPaisa</h4>
                            <p>Pay via EasyPaisa mobile account</p>
                        </div>
                    </div>
                    
                    <div class="method-option" onclick="selectMethod('card')">
                        <input type="radio" name="payment_method" value="Card">
                        <div class="method-icon card">💳</div>
                        <div class="method-info">
                            <h4>Credit/Debit Card</h4>
                            <p>Visa, MasterCard, UnionPay</p>
                        </div>
                    </div>
                    
                    <div class="method-option" onclick="selectMethod('cod')">
                        <input type="radio" name="payment_method" value="Cash on Delivery">
                        <div class="method-icon cod">💵</div>
                        <div class="method-info">
                            <h4>Cash on Delivery</h4>
                            <p>Pay when you receive your order</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card Form (shown when Card is selected) -->
                <div class="card-form" id="cardForm">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)">
                    </div>
                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" id="cardName" placeholder="JOHN DOE">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)">
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="password" id="cardCvv" placeholder="123" maxlength="4">
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Account Form (shown when JazzCash/EasyPaisa is selected) -->
                <div class="card-form" id="mobileForm">
                    <div class="form-group">
                        <label>Mobile Account Number</label>
                        <input type="text" id="mobileNumber" placeholder="03XX-XXXXXXX" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label>PIN / Password</label>
                        <input type="password" id="mobilePin" placeholder="Enter your PIN">
                    </div>
                </div>
                
                <button class="pay-btn primary" id="payBtn" onclick="processPayment()" disabled>
                    💳 Pay Rs. {{ number_format($order->total_amount, 2) }}
                </button>
                
                <div class="security-note">
                    <p>🔒 Your payment is secured with 256-bit SSL encryption</p>
                </div>
            </div>
        </div>
        
        <!-- PROCESSING VIEW -->
        <div class="processing" id="processingView">
            <div class="spinner"></div>
            <h2>Processing Payment...</h2>
            <p>Please wait while we verify your payment</p>
        </div>
        
        <!-- SUCCESS VIEW -->
        <div class="success-view" id="successView">
            <div class="success-icon">✅</div>
            <h2>Payment Successful!</h2>
            <p>Your order #{{ $order->id }} has been confirmed.</p>
            <a href="{{ route('track.order.search', ['order_number' => $order->id]) }}" class="pay-btn primary" style="display:inline-block;text-decoration:none;max-width:300px;margin:0 auto;">
                📍 Track Your Order
            </a>
        </div>
        
        <!-- ERROR VIEW -->
        <div class="error-view" id="errorView">
            <div class="error-icon">❌</div>
            <h2>Payment Failed</h2>
            <p id="errorMessage">Something went wrong. Please try again.</p>
            <button class="pay-btn primary" onclick="retryPayment()" style="max-width:300px;margin:0 auto;">
                🔄 Try Again
            </button>
        </div>
        
    </div>
</div>

<script>
var selectedMethod = null;

function selectMethod(method) {
    selectedMethod = method;
    
    // Update UI
    document.querySelectorAll('.method-option').forEach(function(el) {
        el.classList.remove('selected');
        el.querySelector('input[type="radio"]').checked = false;
    });
    
    var selected = document.querySelector('.method-option input[value="' + (method === 'cod' ? 'Cash on Delivery' : method.charAt(0).toUpperCase() + method.slice(1)) + '"]');
    if (selected) {
        selected.checked = true;
        selected.closest('.method-option').classList.add('selected');
    }
    
    // Show/hide forms
    document.getElementById('cardForm').classList.remove('visible');
    document.getElementById('mobileForm').classList.remove('visible');
    
    if (method === 'card') {
        document.getElementById('cardForm').classList.add('visible');
    } else if (method === 'jazzcash' || method === 'easypaisa') {
        document.getElementById('mobileForm').classList.add('visible');
    }
    
    // Enable pay button
    document.getElementById('payBtn').disabled = false;
}

function formatCardNumber(input) {
    var value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    var matches = value.match(/\d{4,16}/g);
    var match = (matches && matches[0]) || '';
    var parts = [];
    for (var i = 0, len = match.length; i < len; i += 4) {
        parts.push(match.substring(i, i + 4));
    }
    input.value = parts.length ? parts.join(' ') : value;
}

function formatExpiry(input) {
    var value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    input.value = value;
}

function processPayment() {
    if (!selectedMethod) return;
    
    // Validate inputs
    if (selectedMethod === 'card') {
        var cardNum = document.getElementById('cardNumber').value.replace(/\s/g, '');
        var cardName = document.getElementById('cardName').value.trim();
        var cardExpiry = document.getElementById('cardExpiry').value.trim();
        var cardCvv = document.getElementById('cardCvv').value.trim();
        
        if (cardNum.length < 13 || !cardName || cardExpiry.length < 5 || cardCvv.length < 3) {
            alert('Please fill all card details correctly.');
            return;
        }
    } else if (selectedMethod === 'jazzcash' || selectedMethod === 'easypaisa') {
        var mobileNum = document.getElementById('mobileNumber').value.trim();
        var mobilePin = document.getElementById('mobilePin').value.trim();
        
        if (!mobileNum || mobileNum.length < 11 || !mobilePin) {
            alert('Please enter valid mobile account details.');
            return;
        }
    }
    
    // Show processing
    document.getElementById('paymentForm').style.display = 'none';
    document.getElementById('processingView').classList.add('show');
    
    // Simulate payment processing
    setTimeout(function() {
        // 90% success rate for demo
        var success = Math.random() < 0.9;
        
        if (success) {
            document.getElementById('processingView').classList.remove('show');
            document.getElementById('successView').classList.add('show');
            
            // Update order payment method via AJAX
            fetch('/admin/orders/{{ $order->id }}/complete-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    payment_method: selectedMethod === 'cod' ? 'Cash on Delivery' : (selectedMethod.charAt(0).toUpperCase() + selectedMethod.slice(1)),
                    paid_amount: {{ $order->total_amount }}
                })
            }).then(function(r) { return r.json(); });
        } else {
            document.getElementById('processingView').classList.remove('show');
            document.getElementById('errorView').classList.add('show');
            document.getElementById('errorMessage').textContent = 'Payment declined. Please check your details and try again.';
        }
    }, 3000);
}

function retryPayment() {
    document.getElementById('errorView').classList.remove('show');
    document.getElementById('paymentForm').style.display = 'block';
}
</script>

</body>
</html>
