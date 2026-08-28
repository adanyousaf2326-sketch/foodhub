<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Food - FoodHub</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 0 0 40px 0;
        }

        .container {
            max-width: 850px;
            margin: auto;
            padding: 30px 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        h1 {
            color: #222;
        }

        .subtitle {
            color: #777;
            margin-top: 6px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6b00;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox input {
            width: auto;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-primary {
            background: #ff6b00;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .current-image {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
        }

        @media(max-width: 700px) {
            .row {
                grid-template-columns: 1fr;
            }

            body {
                padding: 15px;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-responsive.css') }}">
</head>

<body>

@include('admin.partials.topbar')

<div class="container">

    <div class="header">
        <h1>✏️ Edit Food</h1>

        <p class="subtitle">
            Update food item information
        </p>
    </div>

    <div class="card">

        @if($errors->any())

            <div class="error">
                <strong>Please fix the following:</strong>

                <ul style="margin-top:8px; margin-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif


        <form
            action="{{ route('admin.food.update', $food) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="form-group">

                <label>
                    Food Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $food->name) }}"
                    placeholder="Enter food name"
                    required
                >

            </div>


            <div class="row">

                <div class="form-group">

                    <label>
                        Category
                    </label>

                    <select name="category_id" required>

                        <option value="">
                            Select Category
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $food->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Price (Rs.)
                    </label>

                    <input
                        type="number"
                        name="price"
                        value="{{ old('price', $food->price) }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label>
                    Discount (%)
                </label>

                <input
                    type="number"
                    name="discount_percentage"
                    value="{{ old('discount_percentage', $food->discount_percentage) }}"
                    min="0"
                    max="100"
                    step="0.01"
                    placeholder="0"
                >

            </div>


            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    placeholder="Enter food description"
                >{{ old('description', $food->description) }}</textarea>

            </div>


            <div class="form-group">

                <label>
                    Image URL
                </label>

                <input
                    type="text"
                    name="image"
                    value="{{ old('image', $food->image) }}"
                    placeholder="https://example.com/food.jpg"
                >

                @if($food->image)

                    <div class="current-image">
                        Current Image:
                        {{ $food->image }}
                    </div>

                @endif

            </div>


            <div class="form-group">

                <label class="checkbox">

                    <input
                        type="checkbox"
                        name="is_available"
                        value="1"
                        {{ old('is_available', $food->is_available) ? 'checked' : '' }}
                    >

                    <span>
                        Food is Available
                    </span>

                </label>

            </div>

            <!-- INVENTORY / STOCK -->
            <div style="border:2px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:20px;background:#f9fafb;">
                <h4 style="margin:0 0 12px;font-size:14px;color:#374151;">📦 Inventory / Stock</h4>
                @php $inv = $food->inventory; @endphp
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="checkbox">
                        <input type="checkbox" name="track_stock" value="1" id="track_stock"
                            {{ old('track_stock', $inv && $inv->track_stock) ? 'checked' : '' }}
                            onchange="document.getElementById('stockFields').style.display = this.checked ? 'block' : 'none';">
                        <span>Track Stock Quantity</span>
                    </label>
                </div>
                <div id="stockFields" style="display:{{ old('track_stock', $inv && $inv->track_stock) ? 'block' : 'none' }};">
                    <div class="form-group" style="margin-bottom:10px;">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $inv ? $inv->stock_quantity : 0) }}" min="0" style="width:100%;padding:10px;border:2px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $inv ? $inv->low_stock_threshold : 5) }}" min="1" style="width:100%;padding:10px;border:2px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                </div>
            </div>

            <div class="buttons">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    💾 Update Food
                </button>

                <a
                    href="{{ route('admin.food.index') }}"
                    class="btn btn-secondary"
                >
                    ← Back
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
