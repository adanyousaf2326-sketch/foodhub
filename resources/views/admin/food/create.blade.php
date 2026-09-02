<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Add Food - FoodHub Admin</title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }





        .logo {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 22px;
            font-weight: bold;

            white-space: nowrap;
        }

        .logo span {
            color: #ff6b00;
        }





        .nav a:hover {
            background: #ff6b00;
            color: white;
        }



        /* WEBSITE */

        .website-btn {
            background: #16a34a !important;
            color: white !important;
        }

        .website-btn:hover {
            background: #15803d !important;
        }



        body {
            background: #f4f6f9;
            color: #222;
        }



        .container {
            max-width: 750px;

            margin: auto;

            padding: 35px 20px;
        }



        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #777;
        }



        .card {
            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 5px 25px rgba(0,0,0,.08);

            border: 1px solid #e5e7eb;
        }



        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #374151;
        }

        input,
        textarea,
        select {
            width: 100%;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 9px;

            font-size: 15px;

            outline: none;

            background: white;

            transition: .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #ff6b00;

            box-shadow:
                0 0 0 3px rgba(255,107,0,.10);
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        select {
            cursor: pointer;
        }



        .image-help {
            display: block;

            margin-top: 8px;

            color: #777;

            font-size: 13px;

            line-height: 1.5;
        }

        .image-example {
            margin-top: 10px;

            padding: 10px 12px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;

            border-radius: 8px;

            font-size: 13px;

            color: #475569;

            word-break: break-all;
        }



        .checkbox {
            display: flex;

            gap: 10px;

            align-items: center;

            background: #f8fafc;

            padding: 13px;

            border-radius: 9px;

            border: 1px solid #e5e7eb;
        }

        .checkbox input {
            width: 18px;

            height: 18px;

            accent-color: #ff6b00;

            cursor: pointer;
        }

        .checkbox label {
            margin: 0;

            cursor: pointer;
        }



        .buttons {
            display: flex;

            gap: 10px;

            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            text-decoration: none;

            font-weight: bold;

            font-size: 14px;
        }

        .btn-primary {
            background: #ff6b00;

            color: white;
        }

        .btn-primary:hover {
            background: #e85f00;
        }

        .btn-secondary {
            background: #e5e7eb;

            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }



        .errors {
            background: #fee2e2;

            padding: 15px;

            border-radius: 9px;

            margin-bottom: 20px;

            color: #991b1b;

            border: 1px solid #fecaca;
        }

        .errors ul {
            margin-left: 20px;

            margin-top: 8px;
        }

        .errors li {
            margin-bottom: 4px;
        }



        @media(max-width: 1000px) {



        }


        @media(max-width: 700px) {

            .container {
                padding: 25px 15px;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .card {
                padding: 22px;
            }



        }


        @media(max-width: 500px) {

            .logo {
                font-size: 19px;
            }


            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;

                text-align: center;
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


    <!-- HEADER -->

    <div class="page-header">

        <h1>
            <i class="fas fa-hamburger"></i> Add Food
        </h1>

        <p class="subtitle">
            Add a new item to your FoodHub menu.
        </p>

    </div>


    <!-- CARD -->

    <div class="card">


        <!-- ERRORS -->

        @if($errors->any())

            <div class="errors">

                <strong>
                    <i class="fas fa-exclamation-triangle"></i> Please fix these errors:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- FORM -->

        <form
            action="{{ route('admin.food.store') }}"
            method="POST"
        >

            @csrf


            <!-- CATEGORY -->

            <div class="form-group">

                <label>
                    <i class="fas fa-layer-group"></i> Category
                </label>

                <select
                    name="category_id"
                    required
                >

                    <option value="">
                        Select Category
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            <!-- FOOD NAME -->

            <div class="form-group">

                <label>
                    <i class="fas fa-hamburger"></i> Food Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Zinger Burger"
                    required
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label>
                    <i class="fas fa-align-left"></i> Description
                </label>

                <textarea
                    name="description"
                    placeholder="Describe the food..."
                >{{ old('description') }}</textarea>

            </div>


            <!-- PRICE & VARIATIONS SECTION -->
            <div class="form-group checkbox" style="margin-bottom:15px; background: #eff6ff; border-color: #bfdbfe;">
                <input
                    type="checkbox"
                    name="has_variations"
                    value="1"
                    id="has_variations"
                    {{ old('has_variations') ? 'checked' : '' }}
                    onchange="toggleVariations(this.checked)"
                >
                <label for="has_variations" style="color: #1e40af; font-weight: bold;">
                    <i class="fas fa-layer-group"></i> This food has multiple sizes / variations (e.g. Small, Medium, Large, Cold Drink sizes)
                </label>
            </div>

            <!-- REGULAR SINGLE PRICE (Hidden when variations enabled) -->
            <div id="single-price-section" style="{{ old('has_variations') ? 'display:none;' : '' }}">
                <div class="form-group">
                    <label>
                        <i class="fas fa-coins"></i> Price (Rs.)
                    </label>
                    <input
                        type="number"
                        name="price"
                        id="base_price_input"
                        value="{{ old('price') }}"
                        min="0"
                        step="0.01"
                        placeholder="500"
                        {{ old('has_variations') ? '' : 'required' }}
                    >
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-tag"></i> Discount (%)
                    </label>
                    <input
                        type="number"
                        name="discount_percentage"
                        value="{{ old('discount_percentage', 0) }}"
                        min="0"
                        max="100"
                        step="0.01"
                        placeholder="e.g. 20"
                    >
                </div>
            </div>

            <!-- DYNAMIC VARIATIONS SECTION (Visible when variations enabled) -->
            <div id="variations-section" style="{{ old('has_variations') ? '' : 'display:none;' }}; margin-bottom: 25px; padding: 20px; background: #f8fafc; border: 2px dashed #93c5fd; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div>
                        <strong style="font-size: 16px; color: #1e3a8a;"><i class="fas fa-pizza-slice"></i> Define Sizes & Prices</strong>
                        <p style="font-size: 13px; color: #64748b; margin-top: 3px;">Add each size with its specific price (e.g., Small Rs. 450, Medium Rs. 850, Large Rs. 1300)</p>
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
                    <i class="fas fa-image"></i> Food Image URL
                </label>
                <input
                    type="url"
                    name="image"
                    value="{{ old('image') }}"
                    placeholder="https://example.com/images/burger.jpg"
                    autocomplete="off"
                >
                <span class="image-help">
                    Paste the direct URL of your image here.
                    The URL will be saved exactly as you enter it.
                </span>
                <div class="image-example">
                    Example:
                    https://example.com/burger.jpg
                </div>
            </div>

            <!-- AVAILABLE -->
            <div class="form-group checkbox">
                <input
                    type="checkbox"
                    name="is_available"
                    value="1"
                    id="is_available"
                    {{ old('is_available', true) ? 'checked' : '' }}
                >
                <label for="is_available">
                    Food is Available
                </label>
            </div>

            <!-- BUTTONS -->
            <div class="buttons">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-save"></i> Save Food
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
            basePriceInput.removeAttribute('required');
            const container = document.getElementById('variations-container');
            if (container.children.length === 0) {
                addVariationRow('Small', '', '');
                addVariationRow('Medium', '', '');
                addVariationRow('Large', '', '');
            }
        } else {
            singlePriceSec.style.display = 'block';
            variationsSec.style.display = 'none';
            basePriceInput.setAttribute('required', 'required');
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
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569;">Size Name *</label>
                <input type="text" name="variations[${index}][name]" value="${escapeHtml(name)}" placeholder="e.g. Small / Large / 1.5L" required style="padding: 9px 12px; font-size: 14px;">
            </div>
            <div>
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569;">Price (Rs.) *</label>
                <input type="number" name="variations[${index}][price]" value="${escapeHtml(price)}" min="0" step="0.01" placeholder="e.g. 550" required style="padding: 9px 12px; font-size: 14px;">
            </div>
            <div>
                <label style="font-size: 12px; margin-bottom: 3px; color: #475569;">Discount %</label>
                <input type="number" name="variations[${index}][discount_percentage]" value="${escapeHtml(discount)}" min="0" max="100" step="0.01" placeholder="0" style="padding: 9px 12px; font-size: 14px;">
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

    @if(old('variations'))
        const oldVars = {!! json_encode(array_values(old('variations'))) !!};
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('variations-container');
            container.innerHTML = '';
            oldVars.forEach(v => {
                addVariationRow(v.name || '', v.price || '', v.discount_percentage || '');
            });
        });
    @endif
</script>


</body>

</html>
