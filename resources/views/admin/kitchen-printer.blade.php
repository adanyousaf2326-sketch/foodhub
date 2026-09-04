<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Printer - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #1a1a2e; color: white; height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: #16213e; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar h1 { font-size: 18px; }
        .topbar .status { color: #4ade80; font-size: 13px; }
        .topbar .status.offline { color: #ef4444; }
        .printer-area { flex: 1; padding: 20px; overflow-y: auto; }
        .order-ticket { background: white; color: #111; border-radius: 4px; padding: 20px; max-width: 400px; margin: 0 auto 15px; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; box-shadow: 0 4px 15px rgba(0,0,0,.3); animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .ticket-header { text-align: center; border-bottom: 2px dashed #333; padding-bottom: 10px; margin-bottom: 10px; }
        .ticket-header h2 { font-size: 18px; letter-spacing: 2px; }
        .ticket-header .order-num { font-size: 24px; font-weight: 900; color: #ff6b00; }
        .ticket-row { display: flex; justify-content: space-between; padding: 2px 0; }
        .ticket-items { border-top: 1px dashed #333; padding-top: 8px; margin-top: 8px; }
        .ticket-item { padding: 3px 0; }
        .ticket-item .qty { font-weight: 900; color: #ff6b00; }
        .ticket-total { border-top: 2px dashed #333; padding-top: 8px; margin-top: 8px; text-align: right; font-size: 16px; font-weight: 900; }
        .ticket-footer { text-align: center; margin-top: 10px; font-size: 11px; color: #666; }
        .ticket-notes { background: #fff7ed; border: 1px solid #fed7aa; padding: 8px; border-radius: 4px; margin-top: 8px; font-style: italic; }
        .print-status { text-align: center; color: #16a34a; font-size: 11px; margin-top: 5px; }
        .settings { padding: 16px 24px; background: #16213e; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
        .settings label { font-size: 13px; color: #94a3b8; }
        .settings select, .settings input { padding: 6px 10px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white; font-size: 13px; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px; }
        .btn-green { background: #16a34a; color: white; }
        .btn-orange { background: #ff6b00; color: white; }
        .empty { text-align: center; padding: 60px 20px; color: #64748b; }
        .empty i { font-size: 48px; display: block; margin-bottom: 12px; }
    </style>
</head>
<body>

<div class="topbar">
    <h1><i class="fas fa-print"></i> Kitchen Printer</h1>
    <span class="status" id="connectionStatus"><span class="status offline">●</span> Connecting...</span>
</div>

<div class="settings">
    <label>Auto-print:</label>
    <select id="autoPrint">
        <option value="1">Enabled</option>
        <option value="0">Disabled</option>
    </select>
    <label>Paper size:</label>
    <select id="paperSize">
        <option value="80">80mm</option>
        <option value="58">58mm</option>
        <option value="a4">A4</option>
    </select>
    <label>Sound:</label>
    <select id="soundEnabled">
        <option value="1">🔔 On</option>
        <option value="0">🔕 Off</option>
    </select>
    <button class="btn btn-green" onclick="testPrint()">🖨️ Test Print</button>
    <button class="btn btn-orange" onclick="clearAll()">🗑️ Clear All</button>
</div>

<div class="printer-area" id="printerArea">
    <div class="empty">
        <i class="fas fa-print"></i>
        <h3>Waiting for orders...</h3>
        <p>New orders will appear here and auto-print</p>
    </div>
</div>

<script>
var lastOrderId = 0;
var beepSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVggoKIeGBGP3+IkoZxWk9ijI6JdF1MXZCRi3VcTF2QkYt1XExdkJGLdVxMXZCRi3VcTF2QkYt1XExdkA==');

function pollOrders() {
    fetch('/api/kitchen/new-orders?last_id=' + lastOrderId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('connectionStatus').innerHTML = '<span style="color:#4ade80;">●</span> Connected';

            (data.orders || []).forEach(function(order) {
                if (order.id <= lastOrderId) return;
                lastOrderId = order.id;
                printOrder(order);
            });
        })
        .catch(function() {
            document.getElementById('connectionStatus').innerHTML = '<span class="status offline">●</span> Reconnecting...';
        });
}

function printOrder(order) {
    var area = document.getElementById('printerArea');
    var autoPrint = document.getElementById('autoPrint').value === '1';
    var soundEnabled = document.getElementById('soundEnabled').value === '1';

    // Build ticket
    var ticket = document.createElement('div');
    ticket.className = 'order-ticket';

    var typeEmoji = order.order_type === 'Delivery' ? '🚚' : (order.order_type === 'Dine In' ? '🍽️' : '🛍️');
    var items = (order.items || []).map(function(item) {
        return '<div class="ticket-item"><span class="qty">' + item.quantity + '×</span> ' + item.food_name + (item.variant_name ? ' (' + item.variant_name + ')' : '') + '</div>';
    }).join('');

    var tableInfo = order.table ? ' · Table #' + order.table.table_number : '';
    var addressInfo = order.address ? '<div style="font-size:11px;color:#666;">📍 ' + order.address + '</div>' : '';

    ticket.innerHTML = '<div class="ticket-header">' +
        '<h2>🍽️ FOODHUB</h2>' +
        '<div class="order-num">#' + order.id + '</div>' +
        '<div style="font-size:12px;">' + typeEmoji + ' ' + order.order_type + tableInfo + '</div>' +
        '</div>' +
        '<div class="ticket-row"><span>Customer:</span><strong>' + order.customer_name + '</strong></div>' +
        '<div class="ticket-row"><span>Phone:</span><span>' + order.phone + '</span></div>' +
        addressInfo +
        '<div class="ticket-row"><span>Time:</span><span>' + new Date().toLocaleTimeString() + '</span></div>' +
        (order.notes ? '<div class="ticket-notes">📝 ' + order.notes + '</div>' : '') +
        '<div class="ticket-items">' + items + '</div>' +
        '<div class="ticket-total">TOTAL: Rs. ' + Number(order.total_amount).toLocaleString() + '</div>' +
        '<div class="ticket-footer">Thank you for ordering! 🙏</div>' +
        '<div class="print-status" id="printStatus-' + order.id + '"></div>';

    // Remove empty state
    var empty = area.querySelector('.empty');
    if (empty) empty.remove();

    area.insertBefore(ticket, area.firstChild);

    // Auto-print
    if (autoPrint) {
        setTimeout(function() {
            window.print();
            document.getElementById('printStatus-' + order.id).textContent = '✅ Printed at ' + new Date().toLocaleTimeString();
        }, 500);
    }

    // Play sound
    if (soundEnabled) {
        beepSound.play().catch(function() {});
    }
}

function testPrint() {
    printOrder({
        id: 'TEST-' + Date.now(),
        order_type: 'Delivery',
        customer_name: 'Test Customer',
        phone: '0300-0000000',
        address: 'Test Address',
        total_amount: 999,
        notes: 'This is a test print',
        items: [
            { quantity: 2, food_name: 'Cheese Pizza', variant_name: 'Large' },
            { quantity: 1, food_name: 'Zinger Burger', variant_name: null },
        ],
        table: null,
    });
}

function clearAll() {
    document.getElementById('printerArea').innerHTML = '<div class="empty"><i class="fas fa-print"></i><h3>Waiting for orders...</h3></div>';
}

pollOrders();
setInterval(pollOrders, 5000);
</script>

</body>
</html>
