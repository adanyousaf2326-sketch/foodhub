<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table {{ $table->table_number }} - FoodHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: white;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Booked Table Card */
        .booked-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.5s ease;
        }

        .booked-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
            animation: pulse 2s infinite;
        }

        .booked-card h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .booked-card .table-number {
            color: #ef4444;
            font-size: 18px;
            font-weight: 700;
        }

        .booked-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.5;
        }

        .occupied-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #fca5a5;
            margin-top: 15px;
        }

        .occupied-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse 1.5s infinite;
        }

        /* Available Tables Section */
        .available-section {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            animation: fadeInUp 0.5s ease 0.2s both;
        }

        .available-section h2 {
            font-size: 18px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .available-section h2 i {
            color: #10b981;
        }

        .available-section > p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            margin-bottom: 20px;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
        }

        .table-card {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
        }

        .table-card:hover {
            transform: translateY(-4px) scale(1.05);
            background: rgba(16, 185, 129, 0.3);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .table-card .table-num {
            font-size: 22px;
            font-weight: 800;
            display: block;
        }

        .table-card .table-label {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 2px;
            display: block;
        }

        .table-card .check-icon {
            font-size: 14px;
            color: #10b981;
            margin-top: 4px;
        }

        /* No tables */
        .no-tables {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.5);
        }

        .no-tables i {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        /* Back button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 25px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        /* Logo */
        .logo-bar {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo-bar a {
            color: #ff6b00;
            font-size: 22px;
            font-weight: 800;
            text-decoration: none;
        }
        .logo-bar a i {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo-bar">
            <a href="{{ route('home') }}"><i class="fas fa-utensils"></i>FoodHub</a>
        </div>

        <!-- Booked Table Card -->
        <div class="booked-card">
            <div class="booked-icon">
                <i class="fas fa-times"></i>
            </div>
            <h1>Table #{{ $table->table_number }}</h1>
            <div class="occupied-badge">
                <span class="dot"></span>
                Already Occupied
            </div>
            <p>This table is currently booked by another guest.<br>Please select an available table below to order.</p>
        </div>

        <!-- Available Tables -->
        <div class="available-section">
            <h2><i class="fas fa-check-circle"></i> Available Tables</h2>
            <p>Tap a table to start ordering</p>

            @if($availableTables->count() > 0)
                <div class="tables-grid">
                    @foreach($availableTables as $t)
                        <a href="{{ route('scan.select', $t->table_number) }}" class="table-card">
                            <span class="table-num">{{ $t->table_number }}</span>
                            <span class="table-label">Table</span>
                            <span class="check-icon"><i class="fas fa-check-circle"></i></span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="no-tables">
                    <i class="fas fa-chair"></i>
                    <p>No tables available right now.<br>Please wait or ask staff for help.</p>
                </div>
            @endif

            <a href="{{ route('home') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Browse Menu Without Table
            </a>
        </div>
    </div>
</body>
</html>
