<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - FoodHub Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dark-theme.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #f4f6f9; color: #222; }
        .topbar { background: #111827; color: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 22px; font-weight: bold; }
        .logo span { color: #ff6b00; }
        .nav { display: flex; gap: 8px; flex-wrap: wrap; }
        .nav a, .button { color: white; text-decoration: none; padding: 10px 13px; border-radius: 7px; font-weight: bold; font-size: 13px; }
        .nav a:hover, .nav .active, .button { background: #ff6b00; }
        .container { max-width: 1050px; margin: auto; padding: 30px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 22px; }
        h1 { margin: 0; }
        .card { background: white; border-radius: 12px; padding: 22px; margin-bottom: 16px; box-shadow: 0 5px 20px rgba(0,0,0,.06); }
        .card-head { display: flex; justify-content: space-between; gap: 15px; align-items: start; }
        .card h2 { margin: 0 0 8px; }
        .message { color: #666; line-height: 1.5; white-space: pre-line; }
        .meta { margin-top: 15px; color: #777; font-size: 13px; }
        .status { padding: 5px 9px; border-radius: 14px; background: #dcfce7; color: #166534; font-size: 12px; font-weight: bold; }
        .status.off { background: #fee2e2; color: #991b1b; }
        .foods { margin-top: 12px; display: flex; flex-wrap: wrap; gap: 7px; }
        .food { padding: 6px 9px; background: #fff7ed; color: #c2410c; border-radius: 6px; font-size: 12px; }
        .actions { margin-top: 16px; display: flex; gap: 8px; }
        .actions a, .actions button { border: 0; border-radius: 6px; padding: 9px 12px; text-decoration: none; cursor: pointer; font-weight: bold; background: #111827; color: white; }
        .actions .delete { background: #dc2626; }
        .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 18px; }
        .empty { text-align: center; color: #777; padding: 45px; background: white; border-radius: 12px; }
        @media(max-width: 700px) { .topbar, .header, .card-head { flex-direction: column; align-items: stretch; } .nav { overflow-x: auto; flex-wrap: nowrap; } }
    </style>
</head>
<body>
@include('admin.partials.topbar')
