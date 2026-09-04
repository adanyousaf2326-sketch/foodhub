<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #{{ $order->id }} - FoodHub</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ff6b00">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; }
        #map { width: 100%; height: 55vh; min-height: 300px; z-index: 1; }
        .tracking-bar { background: white; padding: 16px 20px; box-shadow: 0 2px 10px rgba(0,0,0,.05); position: relative; z-index: 10; }
        .tracking-bar h2 { font-size: 16px; color: #111; }
        .tracking-bar .status { color: #ff6b00; font-weight: 700; }
        .rider-info { display: flex; align-items: center; gap: 12px; padding: 14px 20px; background: white; border-top: 1px solid #f3f4f6; }
        .rider-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
        .rider-name { font-weight: 700; font-size: 15px; }
        .rider-phone { color: #6b7280; font-size: 13px; }
        .call-btn { background: #16a34a; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-left: auto; }
        .status-steps { display: flex; justify-content: space-between; padding: 16px 20px; background: white; margin-top: 1px; }
        .step { text-align: center; flex: 1; }
        .step .icon { width: 36px; height: 36px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-size: 14px; color: #9ca3af; }
        .step.active .icon { background: #ff6b00; color: white; }
        .step.done .icon { background: #16a34a; color: white; }
        .step .label { font-size: 11px; color: #6b7280; font-weight: 600; }
        .step.active .label, .step.done .label { color: #111; }
        .back-btn { display: inline-block; padding: 8px 16px; color: #6b7280; text-decoration: none; font-size: 13px; }
        @media (max-width: 600px) {
            #map { height: 45vh; }
            .rider-info { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<div class="tracking-bar">
    <a href="{{ url('/') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    <h2>📍 Order #{{ $order->id }} <span class="status" id="orderStatus">{{ $order->status }}</span></h2>
</div>

<div id="map"></div>

<div class="rider-info" id="riderInfo" style="display:none;">
    <img src="" alt="" class="rider-avatar" id="riderAvatar">
    <div>
        <div class="rider-name" id="riderName"></div>
        <div class="rider-phone" id="riderPhone"></div>
    </div>
    <a href="" class="call-btn" id="callBtn"><i class="fas fa-phone"></i> Call</a>
</div>

<div class="status-steps">
    <div class="step done" id="step-placed"><div class="icon"><i class="fas fa-check"></i></div><div class="label">Placed</div></div>
    <div class="step" id="step-preparing"><div class="icon">👨‍🍳</div><div class="label">Preparing</div></div>
    <div class="step" id="step-picked"><div class="icon">📦</div><div class="label">Picked Up</div></div>
    <div class="step" id="step-delivered"><div class="icon">✅</div><div class="label">Delivered</div></div>
</div>

<script>
var map = L.map('map').setView([{{ $restaurantLat }}, {{ $restaurantLng }}], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Restaurant marker
var restaurantIcon = L.divIcon({
    html: '<div style="background:#ff6b00;color:white;padding:6px 10px;border-radius:8px;font-size:14px;white-space:nowrap;">🍽️ Restaurant</div>',
    className: '',
    iconAnchor: [50, 30]
});
L.marker([{{ $restaurantLat }}, {{ $restaurantLng }}], {icon: restaurantIcon}).addTo(map);

// Customer marker
@if($order->customer_lat && $order->customer_lng)
var customerIcon = L.divIcon({
    html: '<div style="background:#2563eb;color:white;padding:6px 10px;border-radius:8px;font-size:14px;white-space:nowrap;">📍 You</div>',
    className: '',
    iconAnchor: [30, 30]
});
L.marker([{{ $order->customer_lat }}, {{ $order->customer_lng }}], {icon: customerIcon}).addTo(map);
@endif

// Rider marker (updated via polling)
var riderMarker = null;

function updateRiderPosition(lat, lng) {
    if (!lat || !lng) return;
    if (riderMarker) {
        riderMarker.setLatLng([lat, lng]);
    } else {
        var riderIcon = L.divIcon({
            html: '<div style="font-size:28px;">🛵</div>',
            className: '',
            iconAnchor: [14, 14]
        });
        riderMarker = L.marker([lat, lng], {icon: riderIcon}).addTo(map);
    }
}

var statusSteps = {
    'Pending': 'step-preparing',
    'Assigned': 'step-preparing',
    'Preparing': 'step-preparing',
    'Picked Up': 'step-picked',
    'Out for Delivery': 'step-picked',
    'Delivered': 'step-delivered',
    'Completed': 'step-delivered',
};

function updateStatusSteps(status) {
    var steps = ['step-placed', 'step-preparing', 'step-picked', 'step-delivered'];
    var activeStep = statusSteps[status] || 'step-preparing';
    var reached = false;
    steps.forEach(function(s) {
        var el = document.getElementById(s);
        el.classList.remove('active', 'done');
        if (s === activeStep) {
            el.classList.add('active');
            reached = true;
        } else if (!reached) {
            el.classList.add('done');
        }
    });
}

// Poll rider location every 5 seconds
function pollLocation() {
    fetch('/api/tracking/{{ $order->id }}/rider-location')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('orderStatus').textContent = data.order_status;
            updateStatusSteps(data.order_status);

            if (data.rider_location) {
                updateRiderPosition(data.rider_location.lat, data.rider_location.lng);
                map.setView([data.rider_location.lat, data.rider_location.lng], 14);
            }

            if (data.rider_name) {
                document.getElementById('riderInfo').style.display = 'flex';
                document.getElementById('riderName').textContent = data.rider_name;
                document.getElementById('riderPhone').textContent = data.rider_phone || '';
                document.getElementById('callBtn').href = 'tel:' + data.rider_phone;
            }
        })
        .catch(function() {});
}

pollLocation();
setInterval(pollLocation, 5000);

// PWA Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(function() {});
}
</script>

</body>
</html>
