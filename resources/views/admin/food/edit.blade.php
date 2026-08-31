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
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
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


            <!-- SIZES -->
            <div class="form-group">
                <label>📏 Food Sizes (Optional)</label>
                <p style="color:#777;font-size:13px;margin-bottom:10px;">
                    Add sizes like Small/Medium/Large or Half/Full. If no sizes added, base price is used.
                </p>
                <div id="sizes-container">
                    @foreach($food->foodSizes as $size)
                        <div class="size-row" style="display:flex;gap:8px;margin-bottom:8px;align-items:center;flex-wrap:wrap;">
                            <input type="text" name="size_names[]" value="{{ $size->name }}" placeholder="Size name" style="flex:1;min-width:120px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
                            <input type="number" name="size_prices[]" value="{{ $size->price }}" min="0" step="0.01" placeholder="Price (Rs.)" style="flex:1;min-width:100px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
                            <input type="number" name="size_discounts[]" value="{{ $size->discount_percentage }}" min="0" max="100" step="0.01" placeholder="Discount %" style="width:90px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
                            <button type="button" onclick="this.parentElement.remove()" style="padding:8px 12px;background:#fee2e2;color:#dc2626;border:none;border-radius:6px;cursor:pointer;font-size:14px;">×</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addSizeField()" style="margin-top:10px;padding:8px 16px;background:#16a34a;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:bold;font-size:13px;">
                    + Add Size
                </button>
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

<script>
function addSizeField(name = '', price = '', discount = '0') {
    const container = document.getElementById('sizes-container');
    const div = document.createElement('div');
    div.className = 'size-row';
    div.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;align-items:center;flex-wrap:wrap;';
    div.innerHTML = `
        <input type="text" name="size_names[]" value="${name}" placeholder="Size name" style="flex:1;min-width:120px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
        <input type="number" name="size_prices[]" value="${price}" min="0" step="0.01" placeholder="Price (Rs.)" style="flex:1;min-width:100px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
        <input type="number" name="size_discounts[]" value="${discount}" min="0" max="100" step="0.01" placeholder="Discount %" style="width:90px;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;">
        <button type="button" onclick="this.parentElement.remove()" style="padding:8px 12px;background:#fee2e2;color:#dc2626;border:none;border-radius:6px;cursor:pointer;font-size:14px;">×</button>
    `;
    container.appendChild(div);
}
</script>
</body>
</html>
