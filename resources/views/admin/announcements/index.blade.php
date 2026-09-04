<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - FoodHub Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f6f9; color: #222; font-family: 'Inter', Arial, sans-serif; }
        .container { max-width: 1100px; margin: 0 auto; padding: 24px 16px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .header h1 { margin: 0; font-size: 24px; color: #111827; }
        .button { display: inline-block; background: #f97316; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: background 0.2s; }
        .button:hover { background: #ea580c; }

        .success { background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-weight: 500; }

        .card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; }
        .card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .card-head h2 { margin: 0 0 4px 0; font-size: 18px; color: #111827; }
        .message { color: #6b7280; font-size: 14px; margin-top: 4px; }

        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #d1fae5; color: #065f46; white-space: nowrap; }
        .status.off { background: #fee2e2; color: #991b1b; }

        .foods { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
        .food { background: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 6px; font-size: 13px; }

        .meta { color: #6b7280; font-size: 13px; margin-top: 8px; }

        .actions { display: flex; gap: 10px; margin-top: 14px; }
        .actions a { display: inline-block; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; background: #e0e7ff; color: #3730a3; transition: background 0.2s; }
        .actions a:hover { background: #c7d2fe; }
        .delete { background: #fee2e2; color: #991b1b; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: background 0.2s; }
        .delete:hover { background: #fecaca; }

        .empty { text-align: center; padding: 48px 16px; color: #9ca3af; font-size: 16px; background: #fff; border-radius: 12px; border: 1px dashed #d1d5db; }

        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 16px 12px; }
            .header { flex-direction: column; align-items: flex-start; }
            .header h1 { font-size: 20px; }
            .card { padding: 16px; }
            .card-head { flex-direction: column; }
            .actions { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
@include('admin.partials.sidebar')
@include('admin.partials.topbar')
<div class="admin-main container">
    <div class="header">
        <div><h1><i class="fas fa-bullhorn"></i> New Deals & Announcements</h1></div>
        <a class="button" href="{{ route('admin.announcements.create') }}">＋ New Announcement</a>
    </div>
    @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
    @forelse($announcements as $announcement)
        <div class="card">
            <div class="card-head">
                <div><h2>{{ $announcement->title }}</h2><div class="message">{{ $announcement->message }}</div></div>
                <span class="status {{ $announcement->is_active ? '' : 'off' }}">{{ $announcement->is_active ? 'Active' : 'Hidden' }}</span>
            </div>
            @if($announcement->foods->count())
                <div class="foods">@foreach($announcement->foods as $food)<span class="food">{{ $food->name }}</span>@endforeach</div>
            @endif
            @if($announcement->deal_total !== null)
                <div class="meta"><strong>Complete deal total: Rs. {{ number_format($announcement->deal_total, 2) }}</strong></div>
            @endif
            <div class="meta">{{ $announcement->starts_at?->format('d M Y H:i') ?? 'Immediately' }} to {{ $announcement->ends_at?->format('d M Y H:i') ?? 'No expiry' }}</div>
            <div class="actions">
                <a href="{{ route('admin.announcements.edit', $announcement) }}"><i class="fas fa-pen"></i> Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">@csrf @method('DELETE')<button class="delete"><i class="fas fa-trash"></i> Delete</button></form>
            </div>
        </div>
    @empty
        <div class="empty">No announcements created yet.</div>
    @endforelse
</div>
</body>
</html>
