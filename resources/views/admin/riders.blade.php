<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Riders - FoodHub Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #f4f6f9; }
        .container { max-width: 1100px; margin: auto; padding: 30px 20px; }
        .page-header { margin-bottom: 25px; }
        .page-header h1 { font-size: 28px; color: #111827; }
        .subtitle { color: #777; margin-top: 4px; }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,.05); text-align: center; }
        .stat-card .stat-num { font-size: 28px; font-weight: 900; }
        .stat-card .stat-label { font-size: 12px; color: #6b7280; margin-top: 2px; }
        .stat-pending .stat-num { color: #f59e0b; }
        .stat-approved .stat-num { color: #16a34a; }
        .stat-duty .stat-num { color: #3b82f6; }
        .stat-total .stat-num { color: #111827; }

        /* Tabs */
        .tabs { display: flex; gap: 4px; margin-bottom: 20px; background: white; padding: 4px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
        .tab-btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; background: transparent; color: #6b7280; transition: all 0.2s; }
        .tab-btn.active { background: #ff6b00; color: white; }
        .tab-btn:hover:not(.active) { background: #f3f4f6; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .rider-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }

        .rider-card { background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid #e5e7eb; }
        .rider-card-header { padding: 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f3f4f6; }
        .rider-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb; }
        .rider-name { font-size: 16px; font-weight: 700; color: #111827; }
        .rider-phone { font-size: 12px; color: #6b7280; }
        .rider-status-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; margin-top: 2px; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-duty { background: #dbeafe; color: #1d4ed8; }
        .badge-off-duty { background: #f3f4f6; color: #6b7280; }

        .rider-card-body { padding: 12px 16px; font-size: 13px; color: #6b7280; }
        .rider-detail { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
        .rider-detail i { width: 14px; color: #9ca3af; }

        .rider-card-actions { padding: 10px 16px; display: flex; gap: 6px; border-top: 1px solid #f3f4f6; }
        .rider-btn { flex: 1; padding: 8px; border: none; border-radius: 6px; font-weight: 700; font-size: 12px; cursor: pointer; transition: all 0.2s; }
        .btn-approve { background: #16a34a; color: white; }
        .btn-approve:hover { background: #15803d; }
        .btn-reject { background: #dc2626; color: white; }
        .btn-reject:hover { background: #b91c1c; }
        .btn-duty { background: #3b82f6; color: white; }
        .btn-duty:hover { background: #2563eb; }
        .btn-delete { background: #f3f4f6; color: #6b7280; }
        .btn-delete:hover { background: #fee2e2; color: #dc2626; }

        .empty { text-align: center; padding: 40px; color: #9ca3af; }
        .empty i { font-size: 40px; margin-bottom: 10px; display: block; }

        @media(max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .rider-grid { grid-template-columns: 1fr; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
</head>
<body>
@include('admin.partials.sidebar')
@include('admin.partials.topbar')

<div class="admin-main container">
    <div class="page-header">
        <h1><i class="fas fa-motorcycle"></i> Manage Riders</h1>
        <p class="subtitle">View and manage all delivery riders</p>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin-bottom:16px;font-weight:600;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-total"><div class="stat-num">{{ $riders->count() }}</div><div class="stat-label">Total Riders</div></div>
        <div class="stat-card stat-pending"><div class="stat-num">{{ $pendingCount }}</div><div class="stat-label">Pending</div></div>
        <div class="stat-card stat-approved"><div class="stat-num">{{ $approvedCount }}</div><div class="stat-label">Approved</div></div>
        <div class="stat-card stat-duty"><div class="stat-num">{{ $onDutyCount }}</div><div class="stat-label">On Duty Now</div></div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('pending')">Pending ({{ $pendingCount }})</button>
        <button class="tab-btn" onclick="switchTab('approved')">Approved ({{ $approvedCount }})</button>
        <button class="tab-btn" onclick="switchTab('all')">All Riders</button>
    </div>

    <!-- Pending Tab -->
    <div class="tab-content active" id="tab-pending">
        <div class="rider-grid">
            @forelse($riders->where('status', 'pending') as $rider)
                <div class="rider-card">
                    <div class="rider-card-header">
                        <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
                        <div>
                            <div class="rider-name">{{ $rider->name }}</div>
                            <div class="rider-phone">{{ $rider->phone }}</div>
                            <span class="rider-status-badge badge-pending">⏳ Pending</span>
                        </div>
                    </div>
                    <div class="rider-card-body">
                        <div class="rider-detail"><i class="fas fa-id-card"></i> {{ $rider->cnic }}</div>
                        @if($rider->address)<div class="rider-detail"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($rider->address, 50) }}</div>@endif
                        <div class="rider-detail"><i class="fas fa-calendar"></i> Registered: {{ $rider->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="rider-card-actions">
                        <a href="{{ route('admin.riders.approve', $rider->id) }}" class="rider-btn btn-approve" onclick="return confirm('Approve this rider?')"><i class="fas fa-check"></i> Approve</a>
                        <a href="{{ route('admin.riders.reject', $rider->id) }}" class="rider-btn btn-reject" onclick="return confirm('Reject this rider?')"><i class="fas fa-times"></i> Reject</a>
                    </div>
                </div>
            @empty
                <div class="empty" style="grid-column:1/-1;"><i class="fas fa-inbox"></i><p>No pending registrations</p></div>
            @endforelse
        </div>
    </div>

    <!-- Approved Tab -->
    <div class="tab-content" id="tab-approved">
        <div class="rider-grid">
            @forelse($riders->where('status', 'approved') as $rider)
                <div class="rider-card">
                    <div class="rider-card-header">
                        <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
                        <div>
                            <div class="rider-name">{{ $rider->name }}</div>
                            <div class="rider-phone">{{ $rider->phone }}</div>
                            <span class="rider-status-badge {{ $rider->is_on_duty ? 'badge-duty' : 'badge-off-duty' }}">
                                {{ $rider->is_on_duty ? '🟢 ON DUTY' : '🔴 OFF DUTY' }}
                            </span>
                        </div>
                    </div>
                    <div class="rider-card-body">
                        <div class="rider-detail"><i class="fas fa-id-card"></i> {{ $rider->cnic }}</div>
                        @if($rider->address)<div class="rider-detail"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($rider->address, 50) }}</div>@endif
                        <div class="rider-detail"><i class="fas fa-box"></i> Total Deliveries: <strong>{{ $rider->total_orders }}</strong></div>
                        <div class="rider-detail"><i class="fas fa-clock"></i> Last Active: {{ $rider->last_active_at ? $rider->last_active_at->diffForHumans() : 'Never' }}</div>
                    </div>
                    <div class="rider-card-actions">
                        <a href="{{ route('admin.riders.toggle-duty', $rider->id) }}" class="rider-btn btn-duty">
                            <i class="fas fa-power-off"></i> {{ $rider->is_on_duty ? 'Set OFF' : 'Set ON' }}
                        </a>
                        <a href="{{ route('admin.riders.delete', $rider->id) }}" class="rider-btn btn-delete" onclick="return confirm('Delete this rider permanently?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty" style="grid-column:1/-1;"><i class="fas fa-inbox"></i><p>No approved riders</p></div>
            @endforelse
        </div>
    </div>

    <!-- All Tab -->
    <div class="tab-content" id="tab-all">
        <div class="rider-grid">
            @forelse($riders as $rider)
                <div class="rider-card">
                    <div class="rider-card-header">
                        <img src="{{ $rider->photo_url }}" alt="" class="rider-avatar">
                        <div>
                            <div class="rider-name">{{ $rider->name }}</div>
                            <div class="rider-phone">{{ $rider->phone }}</div>
                            <span class="rider-status-badge badge-{{ $rider->status }}">{{ ucfirst($rider->status) }}</span>
                            @if($rider->status === 'approved')
                                <span class="rider-status-badge {{ $rider->is_on_duty ? 'badge-duty' : 'badge-off-duty' }}">
                                    {{ $rider->is_on_duty ? 'ON' : 'OFF' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="rider-card-body">
                        <div class="rider-detail"><i class="fas fa-id-card"></i> {{ $rider->cnic }}</div>
                        <div class="rider-detail"><i class="fas fa-box"></i> Deliveries: {{ $rider->orders_count ?? $rider->total_orders }}</div>
                    </div>
                </div>
            @empty
                <div class="empty" style="grid-column:1/-1;"><i class="fas fa-inbox"></i><p>No riders found</p></div>
            @endforelse
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(function(el) { el.classList.remove('active'); });
    document.querySelectorAll('.tab-btn').forEach(function(el) { el.classList.remove('active'); });
    document.getElementById('tab-' + tab).classList.add('active');
    event.target.classList.add('active');
}
</script>
</body>
</html>
