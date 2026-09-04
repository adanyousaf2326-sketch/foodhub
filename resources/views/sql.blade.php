<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub SQL</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: #111827;
            color: #e5e7eb;
            min-height: 100vh;
            padding: 28px 16px 48px;
        }
        .wrap { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 28px; margin-bottom: 8px; }
        h1 span { color: #ff6b00; }
        p { color: #9ca3af; margin-bottom: 18px; line-height: 1.5; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
        a.btn {
            display: inline-block;
            background: #ff6b00;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 11px 16px;
            border-radius: 8px;
        }
        a.btn.secondary { background: #374151; }
        pre {
            background: #0b1220;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: 18px;
            overflow: auto;
            font-size: 13px;
            line-height: 1.45;
            color: #d1d5db;
            white-space: pre;
            max-height: 75vh;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Food<span>Hub</span> SQL</h1>
        <p>Project schema file. Download and import in phpMyAdmin or MySQL. File path: <code>database/foodhub.sql</code></p>
        <div class="actions">
            <a class="btn" href="{{ route('sql.download') }}">Download foodhub.sql</a>
            <a class="btn secondary" href="{{ url('/') }}">Back to home</a>
        </div>
        <pre>{{ $sql }}</pre>
    </div>
</body>
</html>
