<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Category - FoodHub Admin</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #222;
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


        .website-btn {
            background: #16a34a !important;
            color: white !important;
        }

        .website-btn:hover {
            background: #15803d !important;
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

        .page-header p {
            color: #777;
        }


        .card {
            background: white;

            padding: 30px;

            border-radius: 15px;

            border: 1px solid #e5e7eb;

            box-shadow: 0 5px 25px rgba(0,0,0,.07);
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: bold;

            color: #374151;
        }

        input,
        textarea {
            width: 100%;

            padding: 13px;

            border: 1px solid #d1d5db;

            border-radius: 9px;

            outline: none;

            font-family: inherit;

            font-size: 14px;

            transition: .2s;
        }

        input:focus,
        textarea:focus {
            border-color: #ff6b00;

            box-shadow:
                0 0 0 3px rgba(255,107,0,.10);
        }

        textarea {
            min-height: 120px;

            resize: vertical;
        }


        .image-box {
            background: #f8fafc;

            border: 1px solid #e5e7eb;

            padding: 15px;

            border-radius: 10px;
        }

        .image-help {
            color: #777;

            font-size: 12px;

            margin-top: 8px;

            line-height: 1.5;
        }

        .image-preview {
            margin-top: 15px;

            width: 180px;

            height: 120px;

            border-radius: 10px;

            object-fit: cover;

            border: 1px solid #ddd;

            display: none;

            background: #eee;
        }


        .check {
            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 25px;

            background: #f8fafc;

            padding: 13px;

            border-radius: 9px;

            border: 1px solid #e5e7eb;
        }

        .check input {
            width: 18px;

            height: 18px;

            accent-color: #ff6b00;

            cursor: pointer;
        }

        .check label {
            margin: 0;

            cursor: pointer;
        }


        .buttons {
            display: flex;

            gap: 10px;
        }

        button,
        .back {
            padding: 12px 20px;

            border-radius: 8px;

            border: none;

            cursor: pointer;

            font-weight: bold;

            text-decoration: none;

            font-size: 14px;
        }

        button {
            background: #ff6b00;

            color: white;
        }

        button:hover {
            background: #e85f00;
        }

        .back {
            background: #e5e7eb;

            color: #374151;
        }

        .back:hover {
            background: #d1d5db;
        }


        .error {
            background: #fee2e2;

            color: #991b1b;

            padding: 14px;

            border-radius: 9px;

            margin-bottom: 20px;

            border: 1px solid #fecaca;
        }

        .error div {
            margin-bottom: 4px;
        }

        .error div:last-child {
            margin-bottom: 0;
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

            button,
            .back {
                width: 100%;

                text-align: center;
            }

            line-height: 1.5;
        }

        .image-preview {
            margin-top: 15px;

            width: 180px;

            height: 120px;

            border-radius: 10px;

            object-fit: cover;

            border: 1px solid #ddd;

            display: none;

            background: #eee;
        }


        .check {
            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 25px;

            background: #f8fafc;

            padding: 13px;

            border-radius: 9px;

            border: 1px solid #e5e7eb;
        }

        .check input {
            width: 18px;

            height: 18px;

            accent-color: #ff6b00;

            cursor: pointer;
        }

        .check label {
            margin: 0;

            cursor: pointer;
        }


        .buttons {
            display: flex;

            gap: 10px;
        }

        button,
        .back {
            padding: 12px 20px;

            border-radius: 8px;

            border: none;

            cursor: pointer;

            font-weight: bold;

            text-decoration: none;

            font-size: 14px;
        }

        button {
            background: #ff6b00;

            color: white;
        }

        button:hover {
            background: #e85f00;
        }

        .back {
            background: #e5e7eb;

            color: #374151;
        }

        .back:hover {
            background: #d1d5db;
        }


        .error {
            background: #fee2e2;

            color: #991b1b;

            padding: 14px;

            border-radius: 9px;

            margin-bottom: 20px;

            border: 1px solid #fecaca;
        }

        .error div {
            margin-bottom: 4px;
        }

        .error div:last-child {
            margin-bottom: 0;
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

            button,
            .back {
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

    <div class="page-header">
        <h1><i class="fas fa-layer-group"></i> Add New Category</h1>
        <p>Create a new food category for your FoodHub menu.</p>
    </div>

    <div class="card">

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-triangle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="field">

                <label>
                    <i class="fas fa-layer-group"></i> Category Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Burgers"
                    required
                >

            </div>


            <!-- DESCRIPTION -->

            <div class="field">

                <label>
                    <i class="fas fa-align-left"></i> Description
                </label>

                <textarea
                    name="description"
                    placeholder="Category description..."
                >{{ old('description') }}</textarea>

            </div>


            <!-- IMAGE -->

            <div class="field">

                <label>
                    <i class="fas fa-image"></i> Category Image
                </label>

                <div class="image-box">

                    <input
                        type="text"
                        name="image"
                        id="image"
                        value="{{ old('image') }}"
                        placeholder="Paste image URL here..."
                        autocomplete="off"
                    >

                    <div class="image-help">

                        Paste the image link you want to use.
                        The link will be saved exactly as entered.

                        <br>

                        Examples:
                        JPG, JPEG, PNG, WEBP, GIF and other browser-supported image URLs.

                    </div>


                    <!-- PREVIEW -->

                    <img
                        id="imagePreview"
                        class="image-preview"
                        alt="Category Preview"
                    >

                </div>

            </div>


            <!-- ACTIVE -->

            <div class="check">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    id="is_active"
                    {{ old('is_active', true) ? 'checked' : '' }}
                >

                <label for="is_active">
                    Active Category
                </label>

            </div>


            <!-- BUTTONS -->

            <div class="buttons">

                <button type="submit">
                    <i class="fas fa-save"></i> Save Category
                </button>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="back"
                >
                    ← Cancel
                </a>

            </div>


        </form>


    </div>


</div>


<script>



const imageInput = document.getElementById('image');
const imagePreview = document.getElementById('imagePreview');

function showImagePreview() {

    const url = imageInput.value.trim();

    if (!url) {

        imagePreview.style.display = 'none';

        imagePreview.removeAttribute('src');

        return;
    }

    imagePreview.src = url;

    imagePreview.onload = function () {

        imagePreview.style.display = 'block';

    };

    imagePreview.onerror = function () {

        imagePreview.style.display = 'none';

    };

}

imageInput.addEventListener('input', showImagePreview);

showImagePreview();

</script>


</body>

</html>
