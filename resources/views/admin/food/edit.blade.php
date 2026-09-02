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
    <link rel="stylesheet" href="{{ asset('css/admin-mobile.css') }}">
</head>

<body>

@include('admin.partials.topbar')

<div class="container">

    <div class="header">
        <h1><i class="fas fa-pen"></i> Edit Food</h1>

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

            <!-- HAS VARIATIONS CHECKBOX -->
            @php
                $hasVars = old('has_variations', $food->hasVariations());
            @endphp
            <div class="form-group checkbox" style="margin-bottom:15px; background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px; border-radius: 8px;">
                <input
                    type="checkbox"
                    name="has_variations"
                    value="1"
                    id="has_variations"
                    {{ $hasVars ? 'checked' : '' }}
                    onchange="toggleVariations(this.checked)"
                >
                <label for="has_variations" style="color: #1e40af; font-weight: bold; margin: 0; cursor: pointer;">
                    <i class="fas fa-layer-group"></i> This food has multiple sizes / variations (e.g. Small, Medium, Large, Cold Drink sizes)
                </label>
            </div>

            <!-- REGULAR SINGLE PRICE (Hidden when variations enabled) -->
            <div id="single-price-section" style="{{ $hasVars ? 'display:none;' : '' }}">
                <div class="row">
                    <div class="form-group">
                        <label>
                            Price (Rs.)
                        </label>
                        <input
                            type="number"
                            name="price"
                            id="base_price_input"
                            value="{{ old('price', $food->price) }}"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            {{ $hasVars ? '' : 'required' }}
                        >
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
                </div>
            </div>

            <!-- DYNAMIC VARIATIONS SECTION (Visible when variations enabled) -->
            <div id="variations-section" style="{{ $hasVars ? '' : 'display:none;' }}; margin-bottom: 25px; padding: 20px; background: #f8fafc; border: 2px dashed #93c5fd; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <strong style="font-size: 16px; color: #1e3a8a;"><i class="fas fa-pizza-slice"></i> Define Sizes & Prices</strong>
                        <p style="font-size: 13px; color: #64748b; margin-top: 3px;">Configure sizes and prices for this item</p>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap;">
                    <span style="font-size: 12px; color: #475569; align-self: center; font-weight: bold;">Quick Presets:</span>
                    <button type="button" onclick="addPresetSizes(['Small', 'Medium', 'Large'])" class="btn" style="padding: 6px 12px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">+ Pizza (Small, Medium, Large)</button>
                    <button type="button" onclick="addPresetSizes(['Regular', 'Large'])" class="btn" style="padding: 6px 12px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">+ Regular / Large</button>
                    <button type="button" onclick="addPresetSizes(['Half', 'Full'])" class="btn" style="padding: 6px 12px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">+ Half / Full</button>
                    <button type="button" onclick="addPresetSizes(['500ml', '1.5 Litre'])" class="btn" style="padding: 6px 12px; font-size: 12px; background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">+ Drinks (500ml, 1.5L)</button>
                </div>

                <div id="variations-container" style="display: flex; flex-direction: column; gap: 10px;">
                    <!-- Variation rows injected via JS -->
                </div>

                <button type="button" onclick="addVariationRow('', '', '')" class="btn" style="margin-top: 15px; background: #2563eb; color: white; padding: 9px 16px; font-size: 13px;">
                    <i class="fas fa-plus"></i> + Add Another Size
                </button>
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

            <div class="buttons">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-save"></i> Update Food
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
    let variationIndex = 0;

    function toggleVariations(enabled) {
        const singlePriceSec = document.getElementById('single-price-section');
        const variationsSec = document.getElementById('variations-section');
        const basePriceInput = document.getElementById('base_price_input');

        if (enabled) {
            singlePriceSec.style.display = 'none';
            variationsSec.style.display = 'block';
            if (basePriceInput) basePriceInput.removeAttribute('required');
            const container = document.getElementById('variations-container');
            if (container.children.length === 0) {
                addVariationRow('Small', '', '');
                addVariationRow('Medium', '', '');
                addVariationRow('Large', '', '');
            }
        } else {
            singlePriceSec.style.display = 'block';
            variationsSec.style.display = 'none';
            if (basePriceInput) basePriceInput.setAttribute('required', 'required');
        }
    }

    function addVariationRow(name = '', price = '', discount = '') {
        const container = document.getElementById('variations-container');
        const index = variationIndex++;

        const row = document.createElement('div');
        row.className = 'variation-row';
        row.id = `variation-row-${index}`;
        row.style.cssText = 'display: grid; grid-template-columns: 2fr 1.5fr 1fr 40px; gap: 10px; align-items: center; background: white; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);';

        row.innerHTML = `
            <div>
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569; display:block;">Size Name *</label>
                <input type="text" name="variations[${index}][name]" value="${escapeHtml(name)}" placeholder="e.g. Small / Large / 1.5L" required style="padding: 9px 12px; font-size: 14px; width: 100%;">
            </div>
            <div>
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569; display:block;">Price (Rs.) *</label>
                <input type="number" name="variations[${index}][price]" value="${escapeHtml(price)}" min="0" step="0.01" placeholder="e.g. 550" required style="padding: 9px 12px; font-size: 14px; width: 100%;">
            </div>
            <div>
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569; display:block;">Discount %</label>
                <input type="number" name="variations[${index}][discount_percentage]" value="${escapeHtml(discount)}" min="0" max="100" step="0.01" placeholder="0" style="padding: 9px 12px; font-size: 14px; width: 100%;">
            </div>
            <div style="padding-top: 18px;">
                <button type="button" onclick="removeVariationRow(${index})" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 15px;" title="Remove Size">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    function removeVariationRow(index) {
        const row = document.getElementById(`variation-row-${index}`);
        if (row) {
            row.remove();
        }
    }

    function addPresetSizes(presets) {
        const container = document.getElementById('variations-container');
        container.innerHTML = '';
        presets.forEach(name => {
            addVariationRow(name, '', '');
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    @php
        $initialVarsList = [];
        if (old('variations')) {
            $initialVarsList = array_values(old('variations'));
        } elseif ($food->variations && $food->variations->isNotEmpty()) {
            foreach ($food->variations as $v) {
                $initialVarsList[] = [
                    'name' => $v->name,
                    'price' => (string) $v->price,
                    'discount_percentage' => (string) $v->discount_percentage,
                ];
            }
        }
    @endphp

    document.addEventListener('DOMContentLoaded', function() {
        const initialVars = {!! json_encode($initialVarsList) !!};
        const container = document.getElementById('variations-container');
        if (initialVars && initialVars.length > 0) {
            container.innerHTML = '';
            initialVars.forEach(v => {
                addVariationRow(v.name || '', v.price || '', v.discount_percentage || '');
            });
        }
    });
</script>

</body>
</html>
