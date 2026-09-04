<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Tracking - Admin</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e293b">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #1a1a2e; }
        .topbar { background: #16213e; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar h1 { color: white; font-size: 18px; }
        .topbar a { color: #94a3b8; text-decoration: none; font-size: 14px; }
        #map { width: 100%; height: calc(100vh - 56px); }
        .rider-popup { font-family: 'Segoe UI', sans-serif; }
        .rider-popup img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .rider-popup .name { font-weight: 700; font-size: 14px; }
        .rider-popup .info { color: #6b7280; font-size: 12px; }
        .rider-popup .order-badge { background: #ff6b00; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; display: inline-block; margin-top: 4px; }
        .rider-list { position: absolute; top: 70px; right: 15px; background: white; border-radius: 12px; padding: 15px; width: 280px; max-height: 60vh; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,.15); }
        .rider-list h3 { font-size: 14px; margin-bottom: 10px; color: #111; }
        .rider-card { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 8px; cursor: pointer; transition: .2s; }
        .rider-card:hover { background: #f3f4f6; }
        .rider-card img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
        .rider-card .info { flex: 1; }
        .rider-card .info .name { font-weight: 600; font-size: 13px; }
        .rider-card .info .status { font-size: 11px; color: #6b7280; }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .live-dot { width: 8px; height: 8px; background: #16a34a; border-radius: 50%; display: inline-block; margin-right: 4px; animation: pulse 2s infinite; }
        .stats-bar { position: absolute; top: 70px; left: 15px; background: white; border-radius: 12px; padding: 12px 16px; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,.15); display: flex; gap: 20px; }
        .stat { text-align: center; }
        .stat .num { font-size: 20px; font-weight: 800; color: #ff6b00; }
        .stat .label { font-size: 10px; color: #6b7280; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="topbar">
    <h1><i class="fas fa-map-marked-alt"></i> Live Rider Tracking</h1>
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<div id="map"></div>

<div class="stats-bar" id="statsBar">
    <div class="stat"><div class="num" id="totalRiders">0</div><div class="label">On Duty</div></div>
    <div class="stat"><div class="num" id="withOrders">0</div><div class="label">Delivering</div></div>
    <div class="stat"><div class="num" id="withoutOrders">0</div><div class="label">Available</div></div>
</div>

<div class="rider-list" id="riderList">
    <h3><span class="live-dot"></span> Live Riders</h3>
    <div id="riderCards"></div>
</div>

<script>
var map = L.map('map').setView([{{ $restaurantLat }}, {{ $restaurantLng }}], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Restaurant marker
var restaurantIcon = L.divIcon({
    html: '<div style="background:#ff6b00;color:white;padding:8px 14px;border-radius:10px;font-size:16px;white-space:nowrap;font-weight:700;">🍽️ FoodHub Restaurant</div>',
    className: '',
    iconAnchor: [70, 30]
});
L.marker([{{ $restaurantLat }}, {{ $restaurantLng }}], {icon: restaurantIcon}).addTo(map);

var riderMarkers = {};

function updateRiderOnMap(rider) {
    if (!rider.location) return;

    if (riderMarkers[rider.id]) {
        riderMarkers[rider.id].setLatLng([rider.location.lat, rider.location.lng]);
    } else {
        var icon = L.divIcon({
            html: '<div style="font-size:24px;">🛵</div>',
            className: '',
            iconAnchor: [12, 12]
        });
        var marker = L.marker([rider.location.lat, rider.location.lng], {icon: icon}).addTo(map);
        marker.bindPopup(
            '<div class="rider-popup">' +
            '<img src="' + rider.photo + '" alt="' + rider.name + '">' +
            '<div class="name">' + rider.name + '</div>' +
            '<div class="info">' + rider.phone + '</div>' +
            (rider.active_order_id ? '<div class="order-badge">Order #' + rider.active_order_id + ' — ' + rider.active_order_status + '</div>' : '') +
            '<div class="info" style="margin-top:4px;">Last active: ' + (rider.last_active || 'N/A') + '</div>' +
            '</div>'
        );
        riderMarkers[rider.id] = marker;
    }
}

function renderRiderList(riders) {
    var html = '';
    riders.forEach(function(r) {
        html += '<div class="rider-card" onclick="map.setView([' + (r.location ? r.location.lat + ',' + r.location.lng : '{{ $restaurantLat }},{{ $restaurantLng }}') + '], 15)">';
        html += '<img src="' + r.photo + '" alt="' + r.name + '">';
        html += '<div class="info">';
        html += '<div class="name">' + r.name + '</div>';
        html += '<div class="status">' + (r.active_order_id ? '🛵 Order #' + r.active_order_id : '✅ Available') + '</div>';
        html += '</div></div>';
    });
    document.getElementById('riderCards').innerHTML = html;
}

function pollRiders() {
    fetch('/api/admin/rider-locations')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var riders = data.riders || [];
            var withOrders = riders.filter(function(r) { return r.active_order_id; }).length;

            document.getElementById('totalRiders').textContent = riders.length;
            document.getElementById('withOrders').textContent = withOrders;
            document.getElementById('withoutOrders').textContent = riders.length - withOrders;

            riders.forEach(updateRiderOnMap);
            renderRiderList(riders);
        })
        .catch(function() {});
}

pollRiders();
setInterval(pollRiders, 10000);

// PWA Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(function() {});
}
</script>

</body>
</html>
