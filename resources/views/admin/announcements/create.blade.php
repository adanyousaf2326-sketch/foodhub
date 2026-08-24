<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($announcement) ? 'Edit' : 'Create' }} Announcement - FoodHub</title>
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #f4f6f9; color: #222; }
        .topbar { background: #111827; color: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 22px; font-weight: bold; }.logo span { color: #ff6b00; }.nav { display:flex; gap:8px; }.nav a { color:white; text-decoration:none; padding:10px 13px; border-radius:7px; font-weight:bold; font-size:13px; }.nav a:hover { background:#ff6b00; }
        .container { max-width: 780px; margin: auto; padding: 30px 20px; }.card { background:white; border-radius:12px; padding:28px; box-shadow:0 5px 20px rgba(0,0,0,.06); }.field { margin-bottom:20px; } label { display:block; margin-bottom:8px; font-weight:bold; } input, textarea { width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px; font-size:15px; } textarea { min-height:120px; resize:vertical; }.food-list { display:grid; grid-template-columns:repeat(2, 1fr); gap:10px; max-height:260px; overflow:auto; padding:12px; border:1px solid #ddd; border-radius:8px; }.food-option { display:flex; gap:8px; align-items:center; padding:10px; background:#f8fafc; border-radius:6px; cursor:pointer; }.food-option input[type="checkbox"] { width:auto; }.food-quantity { width:62px; padding:7px; margin-left:auto; }.food-real-price { color:#ff6b00; font-weight:bold; white-space:nowrap; }.selected-total { margin-top:12px; padding:12px; border-radius:8px; background:#eff6ff; color:#1d4ed8; font-weight:bold; }.row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }.check { display:flex; gap:8px; align-items:center; }.check input { width:auto; }.error { background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px; }.buttons { display:flex; gap:10px; margin-top:24px; }.buttons button, .buttons a { border:0; padding:12px 18px; border-radius:8px; text-decoration:none; cursor:pointer; font-weight:bold; }.save { background:#ff6b00; color:white; }.cancel { background:#6b7280; color:white; }
        @media(max-width:700px) { .topbar, .row { flex-direction:column; display:flex; align-items:stretch; }.nav { overflow-x:auto; }.food-list { grid-template-columns:1fr; } }
    </style>
</head>
<body>
@include('admin.partials.topbar')
<div class="container"><div class="card">
    <h1>{{ isset($announcement) ? '✏️ Edit Announcement' : '📣 New Announcement' }}</h1>
    <p>Customer home page par new item, deal ya restaurant update show karein.</p>
    @if($errors->any())<div class="error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
        @csrf @if(isset($announcement)) @method('PUT') @endif
        <div class="field"><label>Title</label><input name="title" value="{{ old('title', $announcement->title ?? '') }}" placeholder="e.g. Weekend Burger Deal" required></div>
        <div class="field"><label>Details / Message</label><textarea name="message" placeholder="Write offer details for customers...">{{ old('message', $announcement->message ?? '') }}</textarea></div>
        <div class="field"><label>Select Food Items and Quantity <small>(same item 2 ya 3 dafa bhi le sakte hain)</small></label><div class="food-list">@foreach($foods as $food) @php $selectedFoodIds = old('food_ids', isset($announcement) ? $announcement->foods->pluck('id')->all() : []); $savedQuantity = isset($announcement) ? (optional($announcement->foods->firstWhere('id', $food->id))->pivot->quantity ?? 1) : 1; @endphp <label class="food-option"><input type="checkbox" name="food_ids[]" value="{{ $food->id }}" data-price="{{ $food->price }}" {{ in_array($food->id, $selectedFoodIds) ? 'checked' : '' }}><span>{{ $food->name }}</span><span class="food-real-price">Rs. {{ number_format($food->price, 2) }}</span><input class="food-quantity" type="number" name="food_quantities[{{ $food->id }}]" value="{{ old('food_quantities.' . $food->id, $savedQuantity) }}" min="1" max="99" data-quantity></label>@endforeach</div><div class="selected-total" id="selected-total">Selected items total: Rs. 0.00</div></div>
        <div class="row"><div class="field"><label>Bundle Deal Total Price (Rs.)</label><input type="number" name="deal_total" value="{{ old('deal_total', $announcement->deal_total ?? '') }}" min="0" step="0.01" placeholder="e.g. 250"></div><div class="field"><label>Button Text</label><input name="button_text" value="{{ old('button_text', $announcement->button_text ?? 'Order Now') }}" placeholder="Order Now"></div></div>
        <div class="field"><label>Deal Poster / Image URL</label><input type="url" name="deal_image" value="{{ old('deal_image', $announcement->deal_image ?? '') }}" placeholder="https://example.com/deal-poster.jpg"><small>Customer ko deal ki yahi image show hogi.</small></div>
        <div class="field"><label>Starts At</label><input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($announcement) && $announcement->starts_at ? $announcement->starts_at->format('Y-m-d\\TH:i') : '') }}"></div>
        <div class="field"><label>Ends At</label><input type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($announcement) && $announcement->ends_at ? $announcement->ends_at->format('Y-m-d\\TH:i') : '') }}"></div>
        <label class="check"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}> Show this announcement on website</label>
        <div class="buttons"><button class="save">💾 Save Announcement</button><a class="cancel" href="{{ route('admin.announcements.index') }}">Cancel</a></div>
    </form>
</div></div>
<script>
    const foodChecks = document.querySelectorAll('input[name="food_ids[]"]');
    const selectedTotal = document.getElementById('selected-total');

    function updateSelectedTotal() {
        let total = 0;
        let count = 0;

        foodChecks.forEach(function (checkbox) {
            if (checkbox.checked) {
                const quantityInput = checkbox.parentElement.querySelector('[data-quantity]');
                const quantity = Number(quantityInput?.value || 1);

                total += Number(checkbox.dataset.price || 0) * quantity;
                count += quantity;
            }
        });

        selectedTotal.textContent =
            `${count} item${count === 1 ? '' : 's'} selected. Real total: Rs. ${total.toFixed(2)}`;
    }

    foodChecks.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectedTotal);
        checkbox.parentElement.querySelector('[data-quantity]')?.addEventListener('input', updateSelectedTotal);
    });

    updateSelectedTotal();
</script>
</body>
</html>
