<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; }
        .topbar { background: linear-gradient(135deg, #16a34a, #22c55e); padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar h1 { color: white; font-size: 18px; }
        .topbar a { color: rgba(255,255,255,0.8); text-decoration: none; }
        .container { max-width: 1100px; margin: 25px auto; padding: 0 20px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,.05); text-align: center; }
        .stat-card .num { font-size: 28px; font-weight: 800; }
        .stat-card .label { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .stat-card.green .num { color: #16a34a; }
        .stat-card.red .num { color: #dc2626; }
        .stat-card.yellow .num { color: #eab308; }
        .stat-card.blue .num { color: #2563eb; }
        .filters { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; }
        .filter-btn { padding: 8px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-weight: 600; font-size: 13px; transition: .2s; }
        .filter-btn.active { border-color: #16a34a; color: #16a34a; background: #f0fdf4; }
        .inventory-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; }
        .food-card { background: white; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; gap: 12px; align-items: center; }
        .food-card img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; }
        .food-info { flex: 1; }
        .food-info .name { font-weight: 700; font-size: 14px; }
        .food-info .category { font-size: 11px; color: #6b7280; }
        .food-info .stock { font-size: 13px; margin-top: 4px; font-weight: 600; }
        .stock-in { color: #16a34a; }
        .stock-low { color: #eab308; }
        .stock-out { color: #dc2626; }
        .stock-controls { display: flex; gap: 6px; margin-top: 6px; align-items: center; }
        .stock-controls input { width: 60px; padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; text-align: center; }
        .stock-controls button { padding: 4px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; }
        .btn-stock { background: #dcfce7; color: #16a34a; }
        .btn-unlimited { background: #e5e7eb; color: #374151; }
        .out-of-stock-badge { background: #fee2e2; color: #dc2626; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .in-stock-badge { background: #dcfce7; color: #16a34a; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        @media (max-width: 700px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .inventory-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <h1><i class="fas fa-boxes-stacked"></i> Inventory Management</h1>
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

<div class="container">
    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:12px;border-radius:10px;margin-bottom:15px;">{{ session('success') }}</div>
    @endif

    <div class="stats">
        <div class="stat-card green"><div class="num">{{ $inStockCount }}</div><div class="label">In Stock</div></div>
        <div class="stat-card red"><div class="num">{{ $outOfStockCount }}</div><div class="label">Out of Stock</div></div>
        <div class="stat-card yellow"><div class="num">{{ $lowStockCount }}</div><div class="label">Low Stock</div></div>
        <div class="stat-card blue"><div class="num">{{ $totalItems }}</div><div class="label">Total Items</div></div>
    </div>

    <div class="filters">
        <button class="filter-btn active" onclick="filterItems('all')">All</button>
        <button class="filter-btn" onclick="filterItems('in-stock')">✅ In Stock</button>
        <button class="filter-btn" onclick="filterItems('low-stock')">⚠️ Low Stock</button>
        <button class="filter-btn" onclick="filterItems('out-of-stock')">❌ Out of Stock</button>
    </div>

    <div class="inventory-grid" id="inventoryGrid">
        @foreach($foods as $food)
            <div class="food-card" data-stock="{{ $food->is_in_stock ? ($food->stock_quantity >= 0 && $food->stock_quantity <= $food->low_stock_threshold ? 'low-stock' : 'in-stock') : 'out-of-stock' }}">
                <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://via.placeholder.com/60' }}" alt="{{ $food->name }}">
                <div class="food-info">
                    <div class="name">{{ $food->name }}</div>
                    <div class="category">{{ $food->category->name ?? 'Uncategorized' }}</div>
                    <div class="stock {{ $food->is_in_stock ? ($food->stock_quantity >= 0 && $food->stock_quantity <= $food->low_stock_threshold ? 'stock-low' : 'stock-in') : 'stock-out' }}">
                        @if(!$food->is_in_stock)
                            <span class="out-of-stock-badge">OUT OF STOCK</span>
                        @elseif($food->stock_quantity == -1)
                            <span class="in-stock-badge">Unlimited</span>
                        @elseif($food->stock_quantity <= $food->low_stock_threshold)
                            ⚠️ {{ $food->stock_quantity }} left
                        @else
                            ✅ {{ $food->stock_quantity }} in stock
                        @endif
                    </div>
                    <div class="stock-controls">
                        <input type="number" id="stock-{{ $food->id }}" value="{{ $food->stock_quantity == -1 ? '' : $food->stock_quantity }}" placeholder="∞" min="0">
                        <button class="btn-stock" onclick="updateStock({{ $food->id }}, 'set')">Set</button>
                        <button class="btn-unlimited" onclick="setUnlimited({{ $food->id }})">∞</button>
                        <button class="btn-stock" onclick="toggleInStock({{ $food->id }}, {{ $food->is_in_stock ? 'false' : 'true' }})" style="background:{{ $food->is_in_stock ? '#fee2e2' : '#dcfce7' }};color:{{ $food->is_in_stock ? '#dc2626' : '#16a34a' }};">
                            {{ $food->is_in_stock ? 'Disable' : 'Enable' }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function filterItems(filter) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    document.querySelectorAll('.food-card').forEach(card => {
        if (filter === 'all' || card.dataset.stock === filter) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function updateStock(foodId, action) {
    var qty = document.getElementById('stock-' + foodId).value;
    fetch('/admin/food/' + foodId + '/stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ stock_quantity: qty }),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.success) location.reload(); })
    .catch(function(e) { alert('Error updating stock'); });
}

function setUnlimited(foodId) {
    document.getElementById('stock-' + foodId).value = '-1';
    updateStock(foodId, 'set');
}

function toggleInStock(foodId, inStock) {
    fetch('/admin/food/' + foodId + '/toggle-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ is_in_stock: inStock }),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.success) location.reload(); })
    .catch(function(e) { alert('Error toggling stock'); });
}
</script>

</body>
</html>
