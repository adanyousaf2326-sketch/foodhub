<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - FoodHub Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #f4f6f9; color: #222; }
    </style>
</head>
<body>
@include('admin.partials.topbar')
<div class="container">
    <div class="header">
        <div><h1>📣 New Deals & Announcements</h1></div>
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
                <a href="{{ route('admin.announcements.edit', $announcement) }}">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm('Delete this announcement?')">@csrf @method('DELETE')<button class="delete">🗑️ Delete</button></form>
            </div>
        </div>
    @empty
        <div class="empty">No announcements created yet.</div>
    @endforelse
</div>
</body>
</html>
