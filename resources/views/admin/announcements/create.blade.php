<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($announcement) ? 'Edit' : 'Create' }} Announcement - FoodHub</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f6f9; color: #222; font-family: 'Inter', Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #fff; border-radius: 12px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; }
        .card h1 { margin: 0 0 8px 0; font-size: 22px; color: #111827; }
        .card > p { color: #6b7280; font-size: 14px; margin: 0 0 20px 0; }

        .error { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .error ul { margin: 4px 0 0 0; padding-left: 18px; }

        .field { margin-bottom: 18px; }
        .field label { display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px; }
        .field small { font-weight: 400; color: #9ca3af; }
        .field input, .field textarea, .field select {
            width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px;
            font-size: 14px; background: #fff; color: #111827; transition: border-color 0.2s;
        }
        .field input:focus, .field textarea:focus { border-color: #f97316; outline: none; box-shadow: 0 0 0 3px rgba(249,115,22,0.15); }
        .field textarea { min-height: 100px; resize: vertical; }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .food-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 8px; margin-top: 8px; }
        .food-option { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 13px; }
        .food-option:has(input:checked) { border-color: #f97316; background: #fff7ed; }
        .food-option input[type="checkbox"] { width: 16px; height: 16px; accent-color: #f97316; }
        .food-option span { flex: 1; }
        .food-real-price { color: #6b7280; font-size: 12px; }
        .food-quantity { width: 50px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; text-align: center; font-size: 13px; }

        .selected-total { margin-top: 10px; padding: 10px 14px; background: #f97316; color: #fff; border-radius: 8px; font-weight: 600; font-size: 14px; }

        .check { display: flex; align-items: center; gap: 8px; margin: 16px 0; font-size: 14px; cursor: pointer; }
        .check input { width: 18px; height: 18px; accent-color: #f97316; }

        .buttons { display: flex; gap: 12px; margin-top: 24px; }
        .save { background: #f97316; color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .save:hover { background: #ea580c; }
        .cancel { display: inline-flex; align-items: center; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 15px; font-weight: 500; color: #6b7280; background: #f3f4f6; transition: background 0.2s; }
        .cancel:hover { background: #e5e7eb; }

        @media (max-width: 768px) {
            .container { padding: 16px 12px; }
            .card { padding: 20px 16px; }
            .row { grid-template-columns: 1fr; }
            .food-list { grid-template-columns: 1fr; }
            .buttons { flex-direction: column; }
        }
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
        <div class="field"><label>Starts At</label><input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($announcement) && $announcement->starts_at ? $announcement->starts_at->format('Y-m-d\\\\TH:i') : '') }}"></div>
        <div class="field"><label>Ends At</label><input type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($announcement) && $announcement->ends_at ? $announcement->ends_at->format('Y-m-d\\\\TH:i') : '') }}"></div>
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
