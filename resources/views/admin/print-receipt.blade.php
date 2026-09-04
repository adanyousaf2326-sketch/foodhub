<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }} - FoodHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* ============ RECEIPT BASE ============ */
        .receipt {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background: #fff;
            padding: 12px;
            margin: 0 auto;
        }

        .receipt-header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 8px; margin-bottom: 8px; }
        .receipt-header h1 { font-size: 18px; letter-spacing: 2px; }
        .receipt-header .tagline { font-size: 10px; color: #555; margin-top: 2px; }

        .receipt-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 3px; }
        .receipt-row.bold { font-weight: bold; font-size: 13px; }
        .receipt-divider { border: none; border-top: 1px dashed #999; margin: 6px 0; }
        .receipt-divider-double { border: none; border-top: 3px double #000; margin: 8px 0; }

        .receipt-items { margin: 6px 0; }
        .receipt-item { font-size: 11px; margin-bottom: 4px; }
        .receipt-item-name { font-weight: bold; }
        .receipt-item-detail { color: #555; font-size: 10px; }
        .receipt-item-right { text-align: right; }

        .receipt-total { font-size: 14px; font-weight: bold; border-top: 2px solid #000; padding-top: 6px; margin-top: 6px; }
        .receipt-total .amount { font-size: 16px; }

        .receipt-footer { text-align: center; font-size: 10px; color: #555; margin-top: 10px; border-top: 2px dashed #000; padding-top: 8px; }
        .receipt-footer .thanks { font-size: 12px; font-weight: bold; margin-bottom: 4px; }

        .receipt-badge {
            display: inline-block;
            padding: 4px 12px;
            border: 2px solid #000;
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }

        /* ============ PRINTER SIZE CLASSES ============ */
        .paper-58mm { width: 48mm; font-size: 11px; }
        .paper-80mm { width: 72mm; font-size: 12px; }
        .paper-a4 { width: 190mm; max-width: 190mm; font-size: 13px; padding: 20mm; }

        .paper-58mm .receipt-header h1 { font-size: 14px; }
        .paper-58mm .receipt-item { font-size: 9px; }
        .paper-58mm .receipt-total { font-size: 12px; }
        .paper-58mm .receipt-total .amount { font-size: 14px; }
        .paper-58mm .receipt-row { font-size: 10px; }

        .paper-80mm .receipt-header h1 { font-size: 18px; }
        .paper-80mm .receipt-item { font-size: 11px; }
        .paper-80mm .receipt-total { font-size: 14px; }

        /* ============ PRINT STYLES ============ */
        @media print {
            body { margin: 0; padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .receipt { margin: 0; padding: 8px; width: 100%; }

            @page {
                margin: 2mm;
            }
        }

        /* ============ NON-PRINT UI ============ */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .print-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print { background: #ff6b00; color: white; }
        .btn-58 { background: #3b82f6; color: white; }
        .btn-80 { background: #16a34a; color: white; }
        .btn-a4 { background: #8b5cf6; color: white; }
        .btn-close { background: #6b7280; color: white; }

        .size-label {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>

<!-- Print Controls (hidden on print) -->
<div class="print-controls no-print">
    <button class="print-btn btn-58" onclick="printReceipt('58mm')"><i class="fas fa-receipt"></i> 58mm</button>
    <button class="print-btn btn-80" onclick="printReceipt('80mm')"><i class="fas fa-receipt"></i> 80mm</button>
    <button class="print-btn btn-a4" onclick="printReceipt('a4')"><i class="fas fa-file"></i> A4</button>
    <button class="print-btn btn-close" onclick="window.close()"><i class="fas fa-times"></i> Close</button>
</div>

<div class="size-label no-print" id="sizeLabel">Detecting printer...</div>

<!-- RECEIPT -->
<div class="receipt" id="receipt">

    <!-- Header -->
    <div class="receipt-header">
        <h1>🍽️ FOODHUB</h1>
        <div class="tagline">Restaurant &amp; Delivery</div>
        <div style="font-size:10px;margin-top:3px;">Order #{{ $order->id }}</div>
    </div>

    <!-- Order Info -->
    <div class="receipt-row bold">
        <span>Date:</span>
        <span>{{ $order->created_at->format('d M Y') }}</span>
    </div>
    <div class="receipt-row">
        <span>Time:</span>
        <span>{{ $order->created_at->format('h:i A') }}</span>
    </div>
    <div class="receipt-row">
        <span>Type:</span>
        <span>{{ $order->order_type }}</span>
    </div>
    @if($order->table_id)
        <div class="receipt-row">
            <span>Table:</span>
            <span>#{{ $order->table->table_number ?? '?' }}</span>
        </div>
    @endif
    <div class="receipt-row">
        <span>Customer:</span>
        <span>{{ $order->customer_name }}</span>
    </div>
    <div class="receipt-row">
        <span>Phone:</span>
        <span>{{ $order->phone }}</span>
    </div>
    @if($order->rider)
        <div class="receipt-row">
            <span>Rider:</span>
            <span>{{ $order->rider->name }}</span>
        </div>
    @endif

    <hr class="receipt-divider">

    <!-- Items -->
    <div class="receipt-items">
        @foreach($order->items as $item)
            <div class="receipt-item">
                <div style="display:flex;justify-content:space-between;">
                    <span class="receipt-item-name">{{ $item->quantity }}× {{ $item->food_name }}</span>
                    <span class="receipt-item-right">Rs. {{ number_format($item->subtotal, 2) }}</span>
                </div>
                @if($item->variant_name)
                    <div class="receipt-item-detail">  Size: {{ $item->variant_name }}</div>
                @endif
                <div class="receipt-item-detail">  @ Rs. {{ number_format($item->price, 2) }} each</div>
            </div>
        @endforeach
    </div>

    <hr class="receipt-divider">

    <!-- Subtotal -->
    <div class="receipt-row">
        <span>Subtotal:</span>
        <span>Rs. {{ number_format($order->total_amount - ($order->delivery_charges ?? 0), 2) }}</span>
    </div>

    @if($order->delivery_charges > 0)
        <div class="receipt-row">
            <span>Delivery ({{ $order->delivery_distance_km ?? '?' }} km):</span>
            <span>Rs. {{ number_format($order->delivery_charges, 2) }}</span>
        </div>
    @endif

    @if($order->notes)
        <hr class="receipt-divider">
        <div class="receipt-row">
            <span>📝 Note:</span>
            <span>{{ $order->notes }}</span>
        </div>
    @endif

    <!-- Total -->
    <div class="receipt-total">
        <div class="receipt-row bold">
            <span>TOTAL:</span>
            <span class="amount">Rs. {{ number_format($order->total_amount, 2) }}</span>
        </div>
        <div class="receipt-row">
            <span>Payment:</span>
            <span>{{ $order->payment_method }}</span>
        </div>
    </div>

    <!-- Status Badge -->
    <div style="text-align:center;margin-top:10px;">
        @if($order->status === 'Delivered')
            <span class="receipt-badge">✅ PAID & DELIVERED</span>
        @elseif($order->status === 'Cash Pending')
            <span class="receipt-badge">💰 CASH PENDING</span>
        @elseif($order->status === 'Cancelled')
            <span class="receipt-badge">❌ CANCELLED</span>
        @else
            <span class="receipt-badge">{{ strtoupper($order->status) }}</span>
        @endif
    </div>

    <!-- Footer -->
    <div class="receipt-footer">
        <div class="thanks">Thank you for ordering!</div>
        <div>FoodHub — Quality Food, Fast Delivery</div>
        <div style="margin-top:4px;">{{ now()->format('d M Y h:i A') }}</div>
    </div>
</div>

<script>
    // ============ AUTO-DETECT PRINTER SIZE ============
    function detectPaperSize() {
        // Check for thermal printer media query
        if (window.matchMedia('(max-width: 80mm)').matches || window.matchMedia('(max-width: 3in)').matches) {
            return '58mm';
        }
        if (window.matchMedia('(max-width: 90mm)').matches || window.matchMedia('(max-width: 3.5in)').matches) {
            return '80mm';
        }
        // Default: try to detect from page width
        var pw = window.innerWidth || document.documentElement.clientWidth;
        if (pw <= 250) return '58mm';
        if (pw <= 350) return '80mm';
        return 'a4';
    }

    function printReceipt(size) {
        var receipt = document.getElementById('receipt');
        receipt.className = 'receipt paper-' + size;
        document.getElementById('sizeLabel').textContent = 'Printing: ' + size + ' paper...';
        setTimeout(function() { window.print(); }, 300);
    }

    // Auto-detect and show
    var detectedSize = detectPaperSize();
    document.getElementById('sizeLabel').textContent = 'Detected: ' + detectedSize + ' paper — Click a size to print';

    // Set default
    document.getElementById('receipt').className = 'receipt paper-' + detectedSize;

    // Auto-print on load (with slight delay)
    window.onload = function() {
        setTimeout(function() {
            printReceipt(detectedSize);
        }, 500);
    };
</script>

</body>
</html>
