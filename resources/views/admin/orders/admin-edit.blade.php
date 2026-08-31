<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Edit Order #{{ $order->id }} - FoodHub</title>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f6f9; color: #222; padding: 0 0 50px 0; }
        .container { max-width: 1100px; margin: auto; padding: 30px 20px; }
        .admin-header { background: linear-gradient(135deg, #1e40af, #3b82f6); color: white; padding: 20px 24px; border-radius: 16px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 20px; }
        .admin-header a { color: #bfdbfe; text-decoration: none; font-size: 14px; }
        .admin-header a:hover { color: white; }
        .reason-box { background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
        .reason-box label { font-weight: bold; color: #92400e; font-size: 14px; display: block; margin-bottom: 6px; }
        .reason-box textarea { width: 100%; padding: 10px; border: 2px solid #f59e0b; border-radius: 8px; font-size: 13px; resize: vertical; min-height: 50px; font-family: inherit; }
        .layout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .card { background: white; border-radius: 14px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #e5e7eb; }
        .card h3 { font-size: 17px; margin-bottom: 16px; color: #1f2937; }
        .items-list { display: flex; flex-direction: column; gap: 10px; }
        .item-row { display: flex; align-items: center; gap: 10px; padding: 12px; background: #f9fafb; border-radius: 10px; border: 1px solid #e5e7eb; }
        .item-info { flex: 1; min-width: 0; }
        .item-name { font-weight: 600; font-size: 14px; color: #1f2937; }
        .item-price { font-size: 12px; color: #6b7280; }
        .qty-control { display: flex; align-items: center; gap: 0; }
        .qty-btn { width: 32px; height: 32px; border: 2px solid #d1d5db; background: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .qty-btn:first-child { border-radius: 8px 0 0 8px; }
        .qty-btn:last-child { border-radius: 0 8px 8px 0; }
        .qty-btn:hover { background: #f3f4f6; }
        .qty-input { width: 44px; height: 32px; border: 2px solid #d1d5db; border-left: none; border-right: none; text-align: center; font-size: 14px; font-weight: bold; }
        .item-subtotal { font-weight: 700; color: #059669; font-size: 13px; white-space: nowrap; }
        .item-remove-btn { background: none; border: none; font-size: 18px; cursor: pointer; opacity: .5; transition: opacity .2s; }
        .item-remove-btn:hover { opacity: 1; }
        .add-item-box { margin-top: 16px; padding: 14px; border: 2px dashed #d1d5db; border-radius: 10px; }
        .add-item-box h4 { font-size: 13px; color: #6b7280; margin-bottom: 8px; }
        .add-item-row { display: flex; gap: 8px; }
        .add-item-select { flex: 1; padding: 8px 10px; border: 2px solid #d1d5db; border-radius: 8px; font-size: 13px; }
        .add-item-btn { padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; white-space: nowrap; }
        .add-item-btn:hover { background: #1d4ed8; }
        .total-summary-card { margin-top: 16px; padding: 14px 18px; background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .total-summary-card .label { font-weight: 600; font-size: 14px; color: #065f46; }
        .total-summary-card .amount { font-size: 22px; font-weight: 800; color: #059669; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 5px; color: #374151; }
        .form-control { width: 100%; padding: 10px 12px; border: 2px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        .form-control:focus { border-color: #2563eb; outline: none; }
        .order-type-tabs { display: flex; gap: 8px; }
        .order-type-tab { flex: 1; }
        .order-type-tab input { display: none; }
        .order-type-tab label { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 12px; border: 2px solid #d1d5db; border-radius: 10px; cursor: pointer; text-align: center; transition: all .2s; }
        .order-type-tab label span:first-child { font-size: 22px; }
        .order-type-tab label span:last-child { font-size: 12px; font-weight: 600; }
        .order-type-tab input:checked + label { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; }
        .table-select-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px; }
        .table-radio-item input { display: none; }
        .table-radio-item label { display: block; padding: 10px; border: 2px solid #d1d5db; border-radius: 8px; text-align: center; cursor: pointer; font-size: 13px; transition: all .2s; }
        .table-radio-item input:checked + label { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; font-weight: bold; }
        .actions-box { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .submit-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; transition: all .3s; }
        .submit-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }
        .submit-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
        .back-link { display: block; text-align: center; padding: 10px; color: #6b7280; text-decoration: none; font-size: 13px; }
        .back-link:hover { color: #2563eb; }
        @media (max-width: 768px) { .layout-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="container">

    <div class="admin-header">
        <h1><i class="fas fa-pen"></i> Admin Edit Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.show', $order) }}">← Back to Order</a>
    </div>

    {{-- REASON BOX --}}
    <div class="reason-box">
        <label><i class="fas fa-align-left"></i> Reason for editing this order (required):</label>
        <textarea name="edit_reason" id="editReason" placeholder="e.g. Customer called to change items, wrong order type, etc.">{{ old('edit_reason') }}</textarea>
    </div>

    @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;color:#991b1b;">
            <ul style="margin-left:18px;">
                @foreach($errors->all() as $error)
                    <li style="font-size:13px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.orders.admin-edit-save', $order) }}" id="adminEditForm">
        @csrf

        <input type="hidden" name="edit_reason" id="editReasonHidden">

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
                                <button type="button" class="item-remove-btn" title="Remove"><i class="fas fa-trash"></i></button>
                            </div>
                        @endforeach
                    </div>

                    <div class="add-item-box">
                        <h4>➕ Add Food Item</h4>
                        <div class="add-item-row">
                            <select id="foodSelector" class="add-item-select">
                                <option value="">-- Choose Food --</option>
                                @foreach($availableFoods as $food)
                                    <option value="{{ $food->id }}" data-name="{{ $food->name }}" data-price="{{ $food->discounted_price }}">
                                        {{ $food->name }} - Rs. {{ number_format($food->discounted_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="add-item-btn" id="btnAddFood">＋ Add</button>
                        </div>
                    </div>

                    <div class="total-summary-card">
                        <span class="label">Updated Total:</span>
                        <span class="amount">Rs. <span id="grandTotalText">{{ number_format($order->total_amount, 2) }}</span></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: DETAILS -->
            <div>
                <div class="card">
                    <h3><i class="fas fa-clipboard"></i> Customer & Order Details</h3>

                    <div class="form-group">
                        <label>Customer Name *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $order->customer_name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $order->phone) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Order Type *</label>
                        <div class="order-type-tabs">
                            <div class="order-type-tab">
                                <input type="radio" id="type_dinein" name="order_type" value="Dine In" {{ old('order_type', $order->order_type) === 'Dine In' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_dinein"><i class="fas fa-utensils"></i><span>Dine In</span></label>
                            </div>
                            <div class="order-type-tab">
                                <input type="radio" id="type_delivery" name="order_type" value="Delivery" {{ old('order_type', $order->order_type) === 'Delivery' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_delivery"><i class="fas fa-motorcycle"></i><span>Delivery</span></label>
                            </div>
                            <div class="order-type-tab">
                                <input type="radio" id="type_takeaway" name="order_type" value="Takeaway" {{ old('order_type', $order->order_type) === 'Takeaway' ? 'checked' : '' }} onchange="toggleOrderTypeFields()">
                                <label for="type_takeaway"><span>🛍️</span><span>Takeaway</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="tableFieldContainer" style="{{ old('order_type', $order->order_type) === 'Dine In' ? '' : 'display:none;' }}">
                        <label>Select Table *</label>
                        <div class="table-select-grid">
                            @foreach($tables as $tbl)
                                <div class="table-radio-item">
                                    <input type="radio" id="tbl_{{ $tbl->id }}" name="table_id" value="{{ $tbl->id }}" {{ old('table_id', $order->table_id) == $tbl->id ? 'checked' : '' }}>
                                    <label for="tbl_{{ $tbl->id }}">Table #{{ $tbl->table_number }}<br><small>({{ $tbl->capacity }} seats)</small></label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group" id="addressFieldContainer" style="{{ old('order_type', $order->order_type) === 'Delivery' ? '' : 'display:none;' }}">
                        <label>Delivery Address *</label>
                        <textarea name="address" class="form-control" placeholder="Address...">{{ old('address', $order->address) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash" {{ old('payment_method', $order->payment_method) === 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="Online" {{ old('payment_method', $order->payment_method) === 'Online' ? 'selected' : '' }}>💳 Online</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Special Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Notes...">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <div class="actions-box">
                        <button type="submit" class="submit-btn" id="btnSubmit" onclick="return confirmAdminEdit()"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="back-link">← Cancel & Go Back</a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function confirmAdminEdit() {
    var reason = document.getElementById('editReason').value.trim();
    if (!reason) {
        alert('Please provide a reason for editing this order.');
        document.getElementById('editReason').focus();
        return false;
    }
    document.getElementById('editReasonHidden').value = reason;
    return confirm('Save changes to Order #{{ $order->id }}?');
}

function toggleOrderTypeFields() {
    var type = document.querySelector('input[name="order_type"]:checked').value;
    document.getElementById('tableFieldContainer').style.display = type === 'Dine In' ? '' : 'none';
    document.getElementById('addressFieldContainer').style.display = type === 'Delivery' ? '' : 'none';
}

function recalcTotal() {
    var total = 0;
    document.querySelectorAll('#itemsListContainer .item-row').forEach(function(row) {
        var price = parseFloat(row.querySelector('.item-price-val').value) || 0;
        var qty = parseInt(row.querySelector('.qty-input').value) || 0;
        var sub = price * qty;
        row.querySelector('.subtotal-val').textContent = sub.toFixed(2);
        total += sub;
    });
    document.getElementById('grandTotalText').textContent = total.toFixed(2);
}

// Qty buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-plus')) {
        var input = e.target.parentElement.querySelector('.qty-input');
        var val = parseInt(input.value) || 1;
        if (val < 99) { input.value = val + 1; recalcTotal(); }
    }
    if (e.target.classList.contains('btn-minus')) {
        var input = e.target.parentElement.querySelector('.qty-input');
        var val = parseInt(input.value) || 1;
        if (val > 1) { input.value = val - 1; recalcTotal(); }
    }
    if (e.target.classList.contains('item-remove-btn')) {
        var row = e.target.closest('.item-row');
        if (row && document.querySelectorAll('#itemsListContainer .item-row').length > 1) {
            row.remove();
            recalcTotal();
        }
    }
});

// Add food item
document.getElementById('btnAddFood').addEventListener('click', function() {
    var sel = document.getElementById('foodSelector');
    if (!sel.value) return;
    var opt = sel.options[sel.selectedIndex];
    var name = opt.getAttribute('data-name');
    var price = parseFloat(opt.getAttribute('data-price'));
    var idx = document.querySelectorAll('#itemsListContainer .item-row').length;
    var html = '<div class="item-row" data-index="' + idx + '">';
    html += '<input type="hidden" name="items[' + idx + '][food_id]" value="' + sel.value + '">';
    html += '<input type="hidden" name="items[' + idx + '][food_name]" value="' + name + '">';
    html += '<input type="hidden" name="items[' + idx + '][price]" class="item-price-val" value="' + price + '">';
    html += '<div class="item-info"><div class="item-name">' + name + '</div><div class="item-price">Rs. ' + price.toFixed(2) + ' each</div></div>';
    html += '<div class="qty-control"><button type="button" class="qty-btn btn-minus">−</button><input type="number" name="items[' + idx + '][quantity]" class="qty-input" value="1" min="1" max="99" readonly><button type="button" class="qty-btn btn-plus">+</button></div>';
    html += '<div class="item-subtotal">Rs. <span class="subtotal-val">' + price.toFixed(2) + '</span></div>';
    html += '<button type="button" class="item-remove-btn" title="Remove"><i class="fas fa-trash"></i></button>';
    html += '</div>';
    document.getElementById('itemsListContainer').insertAdjacentHTML('beforeend', html);
    sel.value = '';
    recalcTotal();
});
</script>
</div>
</div>
    <script src="{{ asset('js/scroll-animations.js') }}"></script>
</body>
</html>
