<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - FoodHub Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #f4f6f9; }
        .container { max-width: 1200px; margin: auto; padding: 30px 20px; }
        .page-header { margin-bottom: 25px; }
        .page-header h1 { font-size: 28px; color: #111827; }
        .subtitle { color: #777; margin-top: 4px; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: 600; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,.05); text-align: center; }
        .stat-card .stat-num { font-size: 28px; font-weight: 900; }
        .stat-card .stat-label { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .stat-customers .stat-num { color: #ff6b00; }
        .stat-orders .stat-num { color: #3b82f6; }
        .stat-revenue .stat-num { color: #16a34a; }

        /* Search */
        .search-box { margin-bottom: 20px; }
        .search-box input { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; outline: none; }
        .search-box input:focus { border-color: #ff6b00; }

        /* Customer Table */
        .customer-table { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .customer-table th { background: #1e293b; color: white; padding: 14px 16px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .customer-table td { padding: 14px 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .customer-table tr:hover td { background: #f8fafc; }
        .customer-table tr:last-child td { border-bottom: none; }

        .customer-name { font-weight: 700; color: #111827; }
        .customer-email { color: #6b7280; font-size: 12px; }
        .customer-phone { color: #374151; }
        .orders-count { background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 12px; }
        .revenue { color: #16a34a; font-weight: 700; }
        .date { color: #9ca3af; font-size: 12px; }

        .action-btn { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; border: none; }
        .btn-view { background: #eff6ff; color: #1d4ed8; }
        .btn-view:hover { background: #dbeafe; }
        .btn-delete { background: #fee2e2; color: #dc2626; }
        .btn-delete:hover { background: #fecaca; }

        .empty { text-align: center; padding: 50px; color: #9ca3af; }
        .empty i { font-size: 48px; margin-bottom: 12px; display: block; }

        @media(max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .customer-table { font-size: 13px; }
            .customer-table th, .customer-table td { padding: 10px 8px; }
        }
    </style>
</head>
<body>
@include('admin.partials.sidebar')
@include('admin.partials.topbar')

<div class="admin-main container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Customer Management</h1>
        <p class="subtitle">View all registered customers — Admin only</p>
    </div>

    @if(session('success'))
        <div class="success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-customers">
            <div class="stat-num">{{ $totalCustomers }}</div>
            <div class="stat-label">👥 Total Customers</div>
        </div>
        <div class="stat-card stat-orders">
            <div class="stat-num">{{ $totalOrders }}</div>
            <div class="stat-label">📦 Total Orders</div>
        </div>
        <div class="stat-card stat-revenue">
            <div class="stat-num">Rs. {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">💰 Total Revenue</div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Search by name, email, or phone..." onkeyup="filterTable()">
    </div>

    <!-- Customer Table -->
    <table class="customer-table" id="customerTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>
                        <div class="customer-name">{{ $customer->name }}</div>
                        <div class="customer-email">{{ $customer->email }}</div>
                    </td>
                    <td class="customer-phone">{{ $customer->phone }}</td>
                    <td><span class="orders-count">{{ $customer->orders_count }}</span></td>
                    <td class="revenue">Rs. {{ number_format($customer->orders_sum_total_amount ?? 0, 0) }}</td>
                    <td class="date">{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="action-btn btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <form method="POST" action="{{ route('admin.customers.delete', $customer->id) }}" style="display:inline;"
                              onsubmit="return confirm('Delete this customer and all their orders?')">
                            @csrf
                            <button type="submit" class="action-btn btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <i class="fas fa-users"></i>
                            <h3>No customers yet</h3>
                            <p>Customers will appear here after they register.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#customerTable tbody tr');
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>
</body>
</html>
