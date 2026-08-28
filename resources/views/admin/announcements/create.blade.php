<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($announcement) ? 'Edit' : 'Create' }} Announcement - FoodHub</title>
    <link rel="stylesheet" href="{{ asset('css/admin-topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #f4f6f9; color: #222; }
        .topbar { background: #111827; color: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 22px; font-weight: bold; }.logo span { color: #ff6b00; }.nav { display:flex; gap:8px; }.nav a { color:white; text-decoration:none; padding:10px 13px; border-radius:7px; font-weight:bold; font-size:13px; }.nav a:hover { background:#ff6b00; }
        .container { max-width: 780px; margin: auto; padding: 30px 20px; }.card { background:white; border-radius:12px; padding:28px; box-shadow:0 5px 20px rgba(0,0,0,.06); }.field { margin-bottom:20px; } label { display:block; margin-bottom:8px; font-weight:bold; } input, textarea { width:100%; padding:12px; border:1px solid #d1d5db; border-radius:8px; font-size:15px; } textarea { min-height:120px; resize:vertical; }.food-list { display:grid; grid-template-columns:repeat(2, 1fr); gap:10px; max-height:260px; overflow:auto; padding:12px; border:1px solid #ddd; border-radius:8px; }.food-option { display:flex; gap:8px; align-items:center; padding:10px; background:#f8fafc; border-radius:6px; cursor:pointer; }.food-option input[type="checkbox"] { width:auto; }.food-quantity { width:62px; padding:7px; margin-left:auto; }.food-real-price { color:#ff6b00; font-weight:bold; white-space:nowrap; }.selected-total { margin-top:12px; padding:12px; border-radius:8px; background:#eff6ff; color:#1d4ed8; font-weight:bold; }.row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }.check { display:flex; gap:8px; align-items:center; }.check input { width:auto; }.error { background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px; }.buttons { display:flex; gap:10px; margin-top:24px; }.buttons button, .buttons a { border:0; padding:12px 18px; border-radius:8px; text-decoration:none; cursor:pointer; font-weight:bold; }.save { background:#ff6b00; color:white; }.cancel { background:#6b7280; color:white; }
        @media(max-width:700px) { .topbar, .row { flex-direction:column; display:flex; align-items:stretch; }.nav { overflow-x:auto; }.food-list { grid-template-columns:1fr; } }
    </style>
</head>
<body>
@include('admin.partials.topbar')
