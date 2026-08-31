<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FoodHub - Order Delicious Food</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        window.foodHubCart = @json(session()->get('cart', []));
    </script>

    <link rel="stylesheet" href="{{ asset('css/foodhub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-styles.css') }}">
<style>
.full-menu-section {
    position: fixed;
    inset: 0;
    z-index: 11000;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 25px 5%;
    background: rgba(17, 24, 39, .72);
    backdrop-filter: blur(5px);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity .25s ease, visibility .25s ease;
    overflow-y: auto;
    overflow-x: hidden;
}

.full-menu-section.is-open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.menu-poster {
    width: 100%;
    max-width: 1400px;
    min-height: auto;
    margin: 0 auto 25px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 50px rgba(0,0,0,.25);
    border: 4px solid #111827;
    position: relative;
    transform: translateY(18px) scale(.98);
    transition: transform .25s ease;
    flex-shrink: 0;
}

.full-menu-section.is-open .menu-poster {
    transform: translateY(0) scale(1);
}

.poster-header {
    background:
        linear-gradient(
            135deg,
            #111827,
            #1f2937
        );
    color: white;
    text-align: center;
    padding: 35px 25px;
    position: relative;
}

.poster-content {
    padding: 35px 45px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 35px 45px;
}

.poster-footer {
    background: #111827;
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
}

.menu-close {
    position: absolute;
    top: 14px;
    right: 16px;
    z-index: 20;
    width: 42px;
    height: 42px;
    border: 0;
    border-radius: 50%;
    background: #111827;
    color: white;
    font-size: 25px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .2s ease;
}

.menu-close:hover {
    background: #ff6b00;
    transform: rotate(90deg);
}

@media (max-width: 900px) {

    .full-menu-section {
        padding: 20px 3%;
    }

    .poster-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        padding: 30px;
    }
}

@media (max-width: 600px) {

    .full-menu-section {
        align-items: flex-start;
        padding: 15px 3%;
    }

    .menu-poster {
        width: 100%;
        border-width: 3px;
        border-radius: 15px;
        margin-bottom: 15px;
    }

    .poster-header {
        padding: 28px 15px;
    }

    .poster-logo {
        font-size: 24px;
    }

    .poster-header h2 {
        font-size: 34px;
    }

    .poster-content {
        grid-template-columns: 1fr;
        padding: 25px 18px;
        gap: 20px;
    }

    .poster-footer {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }

    .menu-close {
        top: 10px;
        right: 10px;
        width: 38px;
        height: 38px;
        font-size: 22px;
    }
}

.menu-close {
    position: absolute;
    top: 14px;
    right: 16px;
    z-index: 1;
    width: 38px;
    height: 38px;
    border: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, .14);
    color: white;
    font-size: 22px;
    cursor: pointer;
}

.menu-close:hover {
    background: #ff6b00;
}

/* HEADER */

.poster-header {

    background:
        linear-gradient(
            135deg,
            #111827,
            #1f2937
        );

    color: white;

    text-align: center;

    padding: 40px 25px;

    position: relative;

}


.poster-logo {

    color: #ff6b00;

    font-size: 30px;

    font-weight: 900;

    letter-spacing: 2px;

}


.poster-subtitle {

    color: #d1d5db;

    font-size: 12px;

    letter-spacing: 5px;

    margin-top: 5px;

}


.poster-header h2 {

    font-size: 48px;

    margin: 18px 0 8px;

    letter-spacing: 3px;

}


.poster-header p {

    color: #d1d5db;

    font-size: 14px;

}


/* CONTENT */

.poster-content {
    padding: 35px 45px;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 35px 45px;

    align-items: start;
}

.poster-category {
    margin-bottom: 0;
}

/* CATEGORY */

.poster-category {

    margin-bottom: 35px;

}


.poster-category-title {

    display: flex;

    align-items: center;

    gap: 10px;

    font-size: 23px;

    font-weight: 900;

    color: #111827;

    padding-bottom: 10px;

    margin-bottom: 8px;

    border-bottom: 3px solid #ff6b00;

}


.poster-category-title span {

    font-size: 25px;

}


/* ITEMS */

.poster-item {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 13px 5px;

    border-bottom: 1px dashed #d1d5db;

}


.poster-item:last-child {

    border-bottom: none;

}


.poster-item-left {

    min-width: 0;

    flex: 1;

}


.poster-item-left strong {

    display: block;

    font-size: 16px;

    color: #222;

}


.poster-item-left small {

    display: block;

    color: #777;

    font-size: 12px;

    margin-top: 4px;

    line-height: 1.4;

}


.poster-price {

    color: #ff6b00;

    font-size: 17px;

    font-weight: 900;

    white-space: nowrap;

}

.poster-old-price {
    display: block;
    color: #9ca3af;
    font-size: 12px;
    font-weight: 400;
    text-decoration: line-through;
}


/* FOOTER */

.poster-footer {

    background: #111827;

    color: white;

    padding: 20px 30px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    font-weight: bold;

}


.poster-footer span {

    color: #ff6b00;

    font-size: 13px;

}


.poster-empty {

    text-align: center;

    padding: 50px;

    color: #777;

    font-size: 50px;

}


.poster-empty h3 {

    font-size: 22px;

    color: #222;

}


/* MOBILE */

@media(max-width:700px) {

    .full-menu-section {

        padding: 35px 4%;

    }


    .menu-poster {

        border-width: 3px;

        border-radius: 15px;

    }


    .poster-header {

        padding: 30px 15px;

    }


    .poster-logo {

        font-size: 24px;

    }


    .poster-header h2 {

        font-size: 34px;

    }


    .poster-content {

        padding: 25px 18px;

    }


    .poster-category-title {

        font-size: 19px;

    }


    .poster-item {

        gap: 10px;

    }


    .poster-item-left strong {

        font-size: 14px;

    }


    .poster-item-left small {

        font-size: 11px;

    }


    .poster-price {

        font-size: 15px;

    }


    .poster-footer {

        flex-direction: column;

        gap: 8px;

        text-align: center;

    }

    .full-menu-section {

        align-items: flex-start;

        padding: 18px 4%;

    }

    .menu-poster {

        margin: 0 auto;

    }

}

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        position: relative;
        background:
            linear-gradient(rgba(248, 249, 250, .88), rgba(248, 249, 250, .94)),
            url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=2000&q=85') center / cover fixed;
        color: #222;
        padding-bottom: 30px;
        overflow-x: hidden;
    }



    nav {
        background: #111827;

        padding: 14px 7%;

        display: flex;
        justify-content: space-between;
        align-items: center;

        position: sticky;
        top: 0;

        z-index: 10000;
    }

    .logo {
        color: #ff6b00;
        font-size: 26px;
        font-weight: bold;
        white-space: nowrap;
    }

    nav > div:last-child {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    nav a {
        color: white;
        text-decoration: none;

        margin-left: 10px;

        padding: 10px 12px;

        border-radius: 8px;

        transition: .2s ease;
    }

    nav a:hover {
        background: #ff6b00;
    }

    .announcement-nav {
        background: #16a34a;
    }

    .announcement-nav:hover {
        background: #15803d !important;
    }

    .announcement-overlay {
        position: fixed;
        inset: 0;
        z-index: 12000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(17, 24, 39, .68);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: .25s ease;
    }

    .announcement-overlay.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .announcement-panel {
        position: relative;
        width: min(680px, 100%);
        max-height: 90vh;
        overflow-y: auto;
        padding: 32px;
        border-radius: 16px;
        background: white;
        box-shadow: 0 20px 60px rgba(0,0,0,.28);
    }

    .announcement-close {
        position: absolute;
        top: 14px;
        right: 16px;
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 50%;
        background: #111827;
        color: white;
        font-size: 22px;
        cursor: pointer;
    }

    .announcement-kicker {
        color: #16a34a;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .announcement-panel h2 {
        margin: 8px 45px 10px 0;
        color: #111827;
        font-size: 30px;
    }

    .announcement-deal-image {
        width: 100%;
        max-height: 280px;
        margin-top: 14px;
        border-radius: 10px;
        overflow: hidden;
        background: #f3f4f6;
    }

    .announcement-deal-image img {
        width: 100%;
        max-height: 280px;
        object-fit: cover;
        display: block;
    }

    .announcement-message {
        color: #4b5563;
        line-height: 1.6;
        white-space: pre-line;
    }

    .announcement-foods {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 22px;
    }

    .announcement-food {
        padding: 13px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        color: #111827;
        font-weight: bold;
    }

    .announcement-food-image {
        width: 100%;
        height: 115px;
        margin-bottom: 10px;
        border-radius: 7px;
        overflow: hidden;
        display: grid;
        place-items: center;
        background: #f3f4f6;
        font-size: 34px;
    }

    .announcement-food-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .announcement-food small {
        display: block;
        margin-top: 5px;
        color: #ff6b00;
    }

    .announcement-action {
        display: inline-block;
        margin-top: 22px;
        padding: 11px 16px;
        border-radius: 8px;
        background: #ff6b00;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .bundle-total {
        margin-top: 20px;
        padding: 14px;
        border-radius: 8px;
        background: #fff7ed;
        color: #9a3412;
        font-weight: bold;
    }

    .deal-end-date {
        margin-top: 8px;
        color: #6b7280;
        font-size: 13px;
    }

    .menu-deal-card {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 14px;
        border: 2px solid #16a34a;
        border-radius: 10px;
        background: #f0fdf4;
    }

    .menu-deal-image {
        width: 120px;
        height: 90px;
        flex: 0 0 120px;
        border-radius: 8px;
        overflow: hidden;
        background: #e5e7eb;
        display: grid;
        place-items: center;
        font-size: 30px;
    }

    .menu-deal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .menu-deal-info { flex: 1; }
    .menu-deal-info h3 { color: #166534; margin-bottom: 5px; }
    .menu-deal-info p { color: #4b5563; margin-bottom: 5px; }
    .menu-deal-price { color: #ea580c; font-size: 18px; font-weight: bold; }

    .poster-deal {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        margin-bottom: 12px;
        border: 2px solid #16a34a;
        border-radius: 8px;
        background: #f0fdf4;
    }

    .poster-deal-image { width: 100px; height: 70px; flex: 0 0 100px; border-radius: 6px; overflow: hidden; background: #e5e7eb; display: grid; place-items: center; font-size: 25px; }
    .poster-deal-image img { width: 100%; height: 100%; object-fit: cover; }
    .poster-deal-info { flex: 1; }
    .poster-deal-info h3 { color: #166534; margin-bottom: 4px; }
    .poster-deal-info p { color: #4b5563; font-size: 12px; }
    .poster-deal-items { display: flex; flex-wrap: wrap; gap: 4px 10px; margin-top: 6px; }
    .poster-deal-item { display: inline-block; background: #f0fdf4; color: #166534; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }

    @media(max-width:700px) {
        .menu-deal-card, .poster-deal { align-items: flex-start; }
        .menu-deal-image { width: 90px; height: 75px; flex-basis: 90px; }
        .menu-deal-card .poster-order { white-space: nowrap; }
    }

    .cart-nav {
        background: #ff6b00;
    }

    .cart-nav:hover {
        background: #e85f00 !important;
    }



    .cart-count {
        background: white;
        color: #ff6b00;

        min-width: 24px;
        height: 24px;

        padding: 2px 6px;

        border-radius: 50%;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        font-size: 11px;
        font-weight: bold;

        margin-left: 5px;
    }

        .deal-badge {
            display: inline-block;
            margin-top: 7px;
            padding: 4px 7px;
            border-radius: 5px;
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            font-weight: bold;
        }


    .announcement-food .deal-order,
    .poster-order {
        display: inline-block;
        margin-top: 8px;
        padding: 6px 9px;
        border: 0;
        border-radius: 6px;
        background: #111827;
        color: white;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
    }

    .hero {
        min-height: 300px;

        display: flex;
        align-items: center;

        padding: 34px 7%;

        position: sticky;
        top: 58px;
        z-index: 9000;
        background:
            linear-gradient(90deg, rgba(17, 24, 39, .9), rgba(17, 24, 39, .42)),
            url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1800&q=85') center / cover;

        color: white;
    }

    .hero-content {
        max-width: 650px;
    }

    .hero h1 {
        font-size: 52px;
        line-height: 1.1;
        margin-bottom: 20px;
    }

    .hero h1 span {
        color: #ff6b00;
    }

    .hero p {
        color: #d1d5db;

        font-size: 18px;

        line-height: 1.6;

        margin-bottom: 25px;
    }

    .hero-btn {
        display: inline-block;

        background: #ff6b00;

        color: white;

        padding: 14px 25px;

        border-radius: 9px;

        text-decoration: none;

        font-weight: bold;

        transition: .2s ease;
    }

    .hero-btn:hover {
        background: #e85f00;
        transform: translateY(-2px);
    }



    .section {
        padding: 50px 7%;
    }

    .section-title {
        text-align: center;
        margin-bottom: 30px;
    }

    .section-title h2 {
        font-size: 32px;
    }

    .section-title p {
        color: #777;
        margin-top: 8px;
    }



    .categories {
        display: grid;

        grid-template-columns:
            repeat(
                auto-fit,
                minmax(180px, 1fr)
            );

        gap: 20px;
    }

    .category {
        background: white;

        padding: 25px;

        text-align: center;

        border-radius: 15px;

        box-shadow:
            0 5px 20px rgba(0,0,0,.06);

        transition: .25s;

        cursor: pointer;

        text-decoration: none;

        color: #222;

        border: 2px solid transparent;
    }

    .category:hover {
        transform: translateY(-5px);

        border-color: #ff6b00;

        box-shadow:
            0 8px 25px
            rgba(255,107,0,.15);
    }

    .category-icon {
        font-size: 45px;
        margin-bottom: 12px;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .category h3 {
        margin-bottom: 8px;
    }

    .category p {
        color: #777;
        font-size: 14px;
    }



    .menu-top {
        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 25px;

        gap: 15px;
    }

    .selected-category {
        color: #ff6b00;
        font-weight: bold;
    }

    .all-food-btn {
        background: #111827;

        color: white;

        text-decoration: none;

        padding: 10px 16px;

        border-radius: 8px;

        font-weight: bold;

        transition: .2s;
    }

    .all-food-btn:hover {
        background: #ff6b00;
    }



    .foods {
        display: grid;

        grid-template-columns:
            repeat(
                auto-fit,
                minmax(230px, 1fr)
            );

        gap: 25px;
    }



    .food-card {
        background: white;

        border-radius: 15px;

        overflow: hidden;

        box-shadow:
            0 5px 20px
            rgba(0,0,0,.07);

        transition: .2s;

        min-width: 0;
    }

    .food-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 10px 25px
            rgba(0,0,0,.10);
    }



    .food-image {
        height: 180px;

        background: #eee;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 60px;

        overflow: hidden;
    }

    .food-image img {
        width: 100%;
        height: 100%;

        object-fit: cover;

        display: block;
    }



    .food-info {
        padding: 18px;
    }

    .food-info h3 {
        margin-bottom: 8px;

        font-size: 18px;
    }

    .food-info p {
        color: #777;

        font-size: 14px;

        min-height: 40px;

        line-height: 1.5;
    }


    .food-category {
        display: inline-block;

        margin-top: 8px;

        padding: 5px 9px;

        background: #fff7ed;

        color: #ea580c;

        border-radius: 20px;

        font-size: 12px;

        font-weight: bold;
    }



    .food-bottom {
        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-top: 15px;

        gap: 10px;
    }

    .price {
        color: #ff6b00;

        font-weight: bold;

        font-size: 18px;

        white-space: nowrap;
    }

    .price-old {
        display: block;
        color: #9ca3af;
        font-size: 12px;
        font-weight: normal;
        text-decoration: line-through;
    }

    .discount-badge {
        display: inline-block;
        margin-left: 5px;
        padding: 3px 6px;
        border-radius: 5px;
        background: #dcfce7;
        color: #15803d;
        font-size: 11px;
        font-weight: bold;
    }

    .order-btn {
        background: #111827;

        color: white;

        border: none;

        padding: 9px 14px;

        border-radius: 7px;

        cursor: pointer;

        font-weight: bold;

        transition: .2s;
    }    .order-btn:hover {

        background: #ff6b00;

    }




    .side-cart {

        position: fixed;

        top: 75px;

        right: 15px;

        bottom: 15px;

        width: 350px;

        background: white;

        border-radius: 16px;

        border: 1px solid #e5e7eb;

        box-shadow:
            0 10px 35px
            rgba(0,0,0,.20);

        z-index: 99999;

        display: flex;

        flex-direction: column;

        overflow: hidden;

        transform: translateX(calc(100% + 25px));

        transition: transform .3s ease;
    }

    .side-cart.open {
        transform: translateX(0);
    }


    .cart-header {

        background: #111827;

        color: white;

        padding: 17px 18px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        flex-shrink: 0;
    }

    .cart-header h2 {
        font-size: 20px;
    }



    .close-cart {
        display: none;
    }



    .cart-items {

        flex: 1;

        min-height: 0;

        overflow-y: auto;

        padding: 8px 14px;
    }



    .cart-items::-webkit-scrollbar {
        width: 6px;
    }

    .cart-items::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .cart-items::-webkit-scrollbar-thumb {
        background: #cbd5e1;

        border-radius: 10px;
    }

    .cart-items::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }



    .side-cart-item {

        display: flex;

        gap: 10px;

        padding: 12px 0;

        border-bottom: 1px solid #eee;

        align-items: flex-start;
    }



    .side-cart-image {

        width: 60px;

        height: 60px;

        border-radius: 9px;

        overflow: hidden;

        background: #eee;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

        font-size: 25px;
    }

    .side-cart-image img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        display: block;
    }



    .side-cart-info {

        flex: 1;

        min-width: 0;
    }

    .side-cart-info h4 {

        font-size: 14px;

        margin-bottom: 4px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }

    .side-cart-price {

        color: #ff6b00;

        font-weight: bold;

        font-size: 14px;
    }


    .quantity-controls {

        display: flex;

        align-items: center;

        gap: 6px;

        margin-top: 8px;
    }

    .qty-btn {

        width: 28px;

        height: 28px;

        border: none;

        border-radius: 6px;

        background: #111827;

        color: white;

        cursor: pointer;

        font-weight: bold;

        font-size: 15px;

        transition: .2s;
    }

    .qty-btn:hover {
        background: #ff6b00;
    }

    .qty-number {

        min-width: 28px;

        text-align: center;

        font-weight: bold;

        font-size: 14px;
    }



    .remove-item {

        border: none;

        background: #fee2e2;

        color: #dc2626;

        width: 30px;

        height: 30px;

        border-radius: 6px;

        cursor: pointer;

        flex-shrink: 0;

        transition: .2s;
    }

    .remove-item:hover {

        background: #dc2626;

        color: white;
    }


    .cart-empty {

        text-align: center;

        padding: 60px 20px;

        color: #777;
    }

    .cart-empty-icon {

        font-size: 55px;

        margin-bottom: 15px;
    }

    .cart-empty h3 {
        margin-bottom: 8px;
    }



    .cart-footer {

        border-top: 1px solid #ddd;

        padding: 15px;

        background: white;

        flex-shrink: 0;
    }

    .cart-total-row {

        display: flex;

        justify-content: space-between;

        align-items: center;

        font-size: 17px;

        font-weight: bold;

        margin-bottom: 14px;
    }

    .side-total {

        color: #ff6b00;

        font-size: 21px;

        white-space: nowrap;
    }



    .checkout-btn {

        width: 100%;

        display: block;

        text-align: center;

        background: #ff6b00;

        color: white;

        text-decoration: none;

        padding: 13px;

        border-radius: 9px;

        font-weight: bold;

        transition: .2s;
    }

    .checkout-btn:hover {

        background: #e85f00;

        transform: translateY(-1px);
    }



    .continue-btn {

        width: 100%;

        margin-top: 8px;

        padding: 11px;

        background: #111827;

        color: white;

        border: none;

        border-radius: 8px;

        cursor: pointer;
    }

    .continue-btn:hover {
        background: #374151;
    }



    @media (min-width: 901px) {

        .hero {

            padding-right: 405px;
        }

        .section {

            padding-right: 405px;
        }

        .foods {

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(210px, 1fr)
                );

            gap: 22px;
        }

        .categories {

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(160px, 1fr)
                );

            gap: 18px;
        }

    }



    .floating-cart {

        display: none;
    }



    .cart-overlay {

        display: none !important;
    }


    /* DRAGGABLE CART BUTTON - hidden on desktop */
    .draggable-cart-btn {
        display: none;
    }


    .toast {

        position: fixed;

        right: 25px;

        bottom: 25px;

        background: #111827;

        color: white;

        padding: 14px 20px;

        border-radius: 9px;

        box-shadow:
            0 5px 20px
            rgba(0,0,0,.2);

        transform: translateY(100px);

        opacity: 0;

        transition: .3s;

        z-index: 100000;
    }

    .toast.show {

        transform: translateY(0);

        opacity: 1;
    }


    .empty {

        grid-column: 1 / -1;

        text-align: center;

        padding: 50px;

        color: #777;
    }



    footer {

        background: #111827;

        color: white;

        text-align: center;

        padding: 25px;

        margin-top: 30px;
    }


    @media (max-width: 1100px) {

        .side-cart {

            width: 320px;

            right: 10px;
        }

        .hero,
        .section {

            padding-right: 365px;
        }

        .foods {

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(190px, 1fr)
                );

            gap: 18px;
        }

    }



    @media (max-width: 900px) {

        nav {

            padding: 15px 5%;
        }

        nav > div:last-child {

            gap: 2px;

            overflow-x: auto;
        }

        nav a {

            margin-left: 3px;

            font-size: 12px;

            padding: 8px;
        }



        /* Cart becomes bottom panel - HIDDEN by default on mobile */

        .side-cart {

            top: auto;

            left: 10px;

            right: 10px;

            bottom: -100vh;

            width: auto;

            height: 75vh;

            max-height: 75vh;

            border-radius: 20px 20px 16px 16px;

            transform: none;

            transition: bottom 0.38s cubic-bezier(0.32, 0.72, 0, 1);

            z-index: 999990;
        }

        /* Cart slides up when open */
        .side-cart.open {

            bottom: 10px;
        }


        /* Overlay visible on mobile */
        .cart-overlay {

            display: block !important;

            position: fixed;

            inset: 0;

            background: rgba(0,0,0,0.5);

            z-index: 999980;

            opacity: 0;

            pointer-events: none;

            transition: opacity 0.3s;
        }

        .cart-overlay.show {

            opacity: 1;

            pointer-events: all;
        }

        /* Close button visible on mobile */
        .close-cart {

            display: flex !important;

            align-items: center;

            justify-content: center;

            background: rgba(255,255,255,0.2);

            border: none;

            color: white;

            font-size: 18px;

            width: 32px;

            height: 32px;

            border-radius: 50%;

            cursor: pointer;
        }

        /* DRAGGABLE FLOATING CART BUTTON - show on mobile */
        .draggable-cart-btn {

            display: flex !important;

            position: fixed;

            bottom: 30px;

            right: 20px;

            width: 62px;

            height: 62px;

            background: linear-gradient(135deg, #ff6b00, #e85f00);

            color: white;

            border: none;

            border-radius: 50%;

            font-size: 26px;

            align-items: center;

            justify-content: center;

            cursor: grab;

            z-index: 999999;

            box-shadow:
                0 6px 20px rgba(255,107,0,0.5),
                0 2px 6px rgba(0,0,0,0.3);

            user-select: none;

            touch-action: none;

            transition: transform 0.15s, box-shadow 0.15s;
        }

        .draggable-cart-btn:active {
            cursor: grabbing;
            transform: scale(0.94);
        }

        /* Cart count badge on floating button */
        .draggable-cart-btn .fab-count {

            position: absolute;

            top: -4px;

            right: -4px;

            background: #111827;

            color: white;

            font-size: 11px;

            font-weight: bold;

            min-width: 20px;

            height: 20px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 0 4px;

            border: 2px solid white;
        }

        /* Restore full page width */

        .hero,
        .section {

            padding-left: 5%;

            padding-right: 5%;
        }


        .foods {

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );
        }

    }

@media (max-width: 900px) {

    .poster-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        padding: 30px;
    }

}

@media (max-width: 600px) {

    .full-menu-section {
        padding: 25px 3%;
    }

    .poster-content {
        grid-template-columns: 1fr;
        padding: 25px 18px;
    }

}

    @media (max-width: 700px) {

        nav {

            padding: 12px 4%;
        }

        nav > div:last-child {

            display: flex;

            max-width: 75vw;

            overflow-x: auto;

            scrollbar-width: none;
        }

        nav > div:last-child::-webkit-scrollbar {

            display: none;
        }

        nav a {

            white-space: nowrap;

            margin-left: 2px;

            font-size: 11px;

            padding: 7px 8px;
        }


        .logo {

            font-size: 20px;
        }


        .hero {

            min-height: 260px;

            padding: 30px 5%;

            top: 50px;
        }

        .hero h1 {

            font-size: 38px;
        }

        .hero p {

            font-size: 16px;
        }


        .section {

            padding: 40px 5%;
        }


        .section-title h2 {

            font-size: 27px;
        }


        .categories {

            grid-template-columns:
                repeat(2, 1fr);

            gap: 12px;
        }


        .category {

            padding: 18px 10px;
        }


        .category-icon {

            font-size: 35px;
        }


        .foods {

            grid-template-columns: repeat(2, 1fr);

            gap: 12px;
        }


        .food-image {

            height: 130px;
        }


        .food-info {

            padding: 12px;
        }


        .food-info h3 {

            font-size: 14px;

            margin-bottom: 5px;
        }


        .food-info p {

            font-size: 12px;

            min-height: unset;
        }


        .food-category {

            font-size: 11px;

            padding: 3px 7px;
        }


        .food-bottom {

            margin-top: 10px;

            gap: 6px;
        }


        .price {

            font-size: 14px;
        }


        .order-btn {

            padding: 7px 10px;

            font-size: 12px;
        }


        .menu-top {

            flex-direction: column;

            align-items: flex-start;
        }


        .side-cart {

            left: 8px;

            right: 8px;

            bottom: -100vh;

            height: 68vh;

            max-height: 68vh;
        }

        .side-cart.open {

            bottom: 8px;
        }


        .toast {

            left: 15px;

            right: 15px;

            bottom: 15px;

            text-align: center;
        }

    }


    @media (max-width: 450px) {

        .logo {

            font-size: 18px;
        }


        nav a {

            font-size: 10px;

            padding: 6px;
        }


        .hero h1 {

            font-size: 32px;
        }


        .categories {

            grid-template-columns: 1fr 1fr;
        }


        .foods {

            grid-template-columns: repeat(2, 1fr);

            gap: 10px;
        }


        .food-image {

            height: 110px;
        }


        .food-info h3 {

            font-size: 13px;
        }


        .food-bottom {

            gap: 4px;

            flex-wrap: wrap;
        }


        .price {

            font-size: 13px;
        }


        .order-btn {

            padding: 6px 9px;

            font-size: 11px;
        }


        .side-cart {

            height: 68vh;

            max-height: 68vh;
        }

    }

    /* ============================================
       DARK MODE — Overrides inline light colors
       ============================================ */
    body.dark-theme { background: #0f172a; color: #e2e8f0; }

    /* Text */
    body.dark-theme h1, body.dark-theme h2, body.dark-theme h3, body.dark-theme h4,
    body.dark-theme strong, body.dark-theme label, body.dark-theme span { color: #f1f5f9; }
    body.dark-theme p, body.dark-theme small { color: #94a3b8; }
    body.dark-theme a { color: #60a5fa; }

    /* Nav */
    body.dark-theme nav { background: #0f172a; }

    /* Hero */
    body.dark-theme .hero { background: linear-gradient(90deg, rgba(15,23,42,.95), rgba(15,23,42,.6)); }
    body.dark-theme .hero h1 { color: #f1f5f9; }
    body.dark-theme .hero p { color: #94a3b8; }

    /* Sections */
    body.dark-theme .section-title h2 { color: #f1f5f9; }
    body.dark-theme .section-title p { color: #94a3b8; }

    /* Categories */
    body.dark-theme .category { background: #1e293b; color: #e2e8f0; border-color: #334155; }
    body.dark-theme .category:hover { border-color: #ff6b00; }
    body.dark-theme .category h3 { color: #f1f5f9; }
    body.dark-theme .category p { color: #94a3b8; }
    body.dark-theme .category-icon { background: #1e293b; }

    /* Food Cards */
    body.dark-theme .food-card { background: #1e293b; border-color: #334155; }
    body.dark-theme .food-info h3 { color: #f1f5f9; }
    body.dark-theme .food-info p { color: #94a3b8; }
    body.dark-theme .food-category { background: rgba(255,107,0,.15); color: #ff8c38; }
    body.dark-theme .food-bottom { border-top-color: #334155; }
    body.dark-theme .price { color: #f1f5f9; }
    body.dark-theme .price-old { color: #64748b; }
    body.dark-theme .discount-badge { background: rgba(220,38,38,.15); color: #f87171; }
    body.dark-theme .deal-badge { background: rgba(22,101,52,.2); color: #4ade80; }

    /* Menu Top */
    body.dark-theme .menu-top { color: #94a3b8; }
    body.dark-theme .all-food-btn { background: #334155; color: #e2e8f0; }
    body.dark-theme .selected-category { color: #ff6b00; }

    /* Poster */
    body.dark-theme .menu-poster { background: #1e293b; border-color: #334155; }
    body.dark-theme .poster-header { background: #0f172a; }
    body.dark-theme .poster-header h2 { color: #f1f5f9; }
    body.dark-theme .poster-header p { color: #94a3b8; }
    body.dark-theme .poster-logo { color: #f1f5f9; }
    body.dark-theme .poster-subtitle { color: #94a3b8; }
    body.dark-theme .poster-category-title { color: #f1f5f9; border-bottom-color: #ff6b00; }
    body.dark-theme .poster-item { border-bottom-color: #334155; }
    body.dark-theme .poster-item-left strong { color: #e2e8f0; }
    body.dark-theme .poster-item-left small { color: #94a3b8; }
    body.dark-theme .poster-price { color: #ff6b00; }
    body.dark-theme .poster-old-price { color: #64748b; }
    body.dark-theme .poster-footer { background: #0f172a; color: #94a3b8; }
    body.dark-theme .poster-deal { background: rgba(22,101,52,.1); border-color: rgba(22,101,52,.4); }
    body.dark-theme .poster-deal-info h3 { color: #4ade80; }
    body.dark-theme .poster-deal-info strong { color: #e2e8f0; }
    body.dark-theme .poster-deal-info p { color: #94a3b8; }
    body.dark-theme .poster-deal-item { background: rgba(22,101,52,.2); color: #4ade80; }

    /* Cart */
    body.dark-theme .side-cart { background: #1e293b; border-color: #334155; }
    body.dark-theme .cart-header { background: #0f172a; }
    body.dark-theme .cart-header h3 { color: #f1f5f9; }
    body.dark-theme .side-cart-info h4 { color: #f1f5f9; }
    body.dark-theme .side-cart-info p { color: #94a3b8; }
    body.dark-theme .qty-number { color: #e2e8f0; }
    body.dark-theme .cart-footer { background: #1e293b; border-top-color: #334155; }
    body.dark-theme .cart-total-row { color: #e2e8f0; }
    body.dark-theme .continue-btn { background: #334155; color: #e2e8f0; }
    body.dark-theme .continue-btn:hover { background: #ff6b00; }

    /* Announcement */
    body.dark-theme .announcement-overlay { background: rgba(0,0,0,.75); }
    body.dark-theme .announcement-panel { background: #1e293b; }
    body.dark-theme .announcement-panel h2 { color: #f1f5f9; }
    body.dark-theme .announcement-kicker { color: #4ade80; }
    body.dark-theme .announcement-message { color: #94a3b8; }
    body.dark-theme .announcement-food { background: #0f172a; border-color: #334155; color: #e2e8f0; }
    body.dark-theme .announcement-food small { color: #94a3b8; }
    body.dark-theme .announcement-close { background: #334155; color: #e2e8f0; }
    body.dark-theme .bundle-total { color: #4ade80; }

    /* Deals */
    body.dark-theme .menu-deal-card { background: rgba(22,101,52,.15); border-color: rgba(22,101,52,.4); }
    body.dark-theme .menu-deal-info h3 { color: #4ade80; }
    body.dark-theme .menu-deal-info p { color: #94a3b8; }

    /* Track Order */
    body.dark-theme .track-card { background: #1e293b; border-color: #334155; }
    body.dark-theme .track-card h2 { color: #f1f5f9; }
    body.dark-theme .track-info { color: #94a3b8; }

    /* Forms & Inputs */
    body.dark-theme input, body.dark-theme textarea, body.dark-theme select {
        background: #1e293b; color: #e2e8f0; border-color: #334155;
    }
    body.dark-theme input::placeholder, body.dark-theme textarea::placeholder { color: #64748b; }

    /* Toast & Empty */
    body.dark-theme .toast { background: #334155; color: #e2e8f0; }
    body.dark-theme .empty { color: #94a3b8; }
    body.dark-theme .empty h3 { color: #f1f5f9; }

    /* Footer */
    body.dark-theme footer { background: #0f172a; color: #94a3b8; }

    /* Full Menu */
    body.dark-theme .full-menu-section { background: rgba(0,0,0,.8); }

    /* Theme Toggle */
    body.dark-theme .theme-toggle-customer { background: rgba(255,200,60,.15); border-color: rgba(255,200,60,.3); }

</style>
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-responsive.css') }}">

    <style>
        #fh-loader {
            position: fixed;
            inset: 0;
            z-index: 9999999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            color: white;
            background: rgba(15, 23, 42, .72);
            backdrop-filter: blur(4px);
            animation: fh-loader-hide .35s ease 1.5s forwards;
        }

        .fh-loader-brand {
            color: #ff6b00;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 3px;
        }

        .fh-loader-brand span {
            color: white;
        }

        .fh-plate {
            width: 86px;
            height: 86px;
            border: 5px solid rgba(255, 255, 255, .15);
            border-top-color: #ff6b00;
            border-right-color: #ff8c38;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 32px;
            animation: fh-loader-spin 1s linear infinite;
        }

        .fh-loader-dots {
            display: flex;
            gap: 8px;
        }

        .fh-loader-dots span {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ff6b00;
            animation: fh-loader-bounce 1s ease-in-out infinite;
        }

        .fh-loader-dots span:nth-child(2) { animation-delay: .15s; }
        .fh-loader-dots span:nth-child(3) { animation-delay: .3s; }

        .fh-loader-text {
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        @keyframes fh-loader-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fh-loader-bounce {
            0%, 100% { transform: translateY(0); opacity: .45; }
            50% { transform: translateY(-8px); opacity: 1; }
        }

        @keyframes fh-loader-hide {
            to {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
        }
    </style>
</head>


<body>

<div id="fh-loader" aria-label="Loading FoodHub">

    <div class="fh-loader-brand">
        <i class="fas fa-utensils"></i> FOOD<span>HUB</span>
    </div>

    <div class="fh-plate"><i class="fas fa-utensils"></i></div>

    <div class="fh-loader-dots" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="fh-loader-text">Preparing your delicious experience</div>

</div>


<nav>
    <button type="button" class="customer-hamburger" onclick="toggleCustomerNav()" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
    <a href="{{ url('/') }}" class="logo">
        <span class="logo-icon"><i class="fas fa-utensils"></i></span>
        Food<span class="hub-brand">Hub</span>
    </a>

    <div id="customerNavLinks">
        <a href="{{ url('/') }}">
            <i class="fas fa-home"></i> Home
        </a>

        <a href="#categories">
            <i class="fas fa-th-large"></i> Categories
        </a>

        <a href="#full-menu" onclick="openMenu(event)">
            <i class="fas fa-utensils"></i> Menu
        </a>

        <a href="#announcement" class="announcement-nav" onclick="openAnnouncement(event)">
            <i class="fas fa-tags"></i> New Deals
        </a>

        <a href="{{ route('track.order') }}">
            <i class="fas fa-map-marker-alt"></i> Track Order
        </a>

        <a
            href="javascript:void(0)"
            class="cart-nav"
            onclick="openCart()"
        >
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-count" id="navCartCount">0</span>
        </a>
        <button type="button" class="theme-toggle-customer" onclick="toggleCustomerTheme()" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:white;cursor:pointer;font-size:17px;margin-left:6px;transition:all .25s ease;">
            <span class="theme-icon-customer"><i class="fas fa-moon"></i></span>
        </button>
    </div>
</nav>


@php
    $featuredFoodIds = [];
    $dealPrices = [];
    $dealAnnouncementIds = [];

    foreach ($announcements as $announcementItem) {
        foreach ($announcementItem->foods as $announcementFood) {
            $featuredFoodIds[] = $announcementFood->id;
            $dealPrices[$announcementFood->id] ??= $announcementFood->pivot->deal_price ?? $announcementFood->discounted_price;
            $dealAnnouncementIds[$announcementFood->id] ??= $announcementItem->id;
        }
    }

    $featuredFoodIds = array_unique($featuredFoodIds);
@endphp


<div class="announcement-overlay" id="announcement" onclick="closeAnnouncementOnBackdrop(event)">
        <div class="announcement-panel">
            <button type="button" class="announcement-close" onclick="closeAnnouncement()" aria-label="Close announcement">×</button>
            @forelse($announcements as $announcement)
                <div class="announcement-kicker">New from FoodHub</div>
                @if($announcement->deal_image)
                    <div class="announcement-deal-image">
                        <img src="{{ $announcement->deal_image }}" alt="{{ $announcement->title }}">
                    </div>
                @endif
                <h2>{{ $announcement->title }}</h2>

                @if($announcement->deal_total !== null)
                    <div class="bundle-total">
                        Deal price: Rs. {{ number_format($announcement->deal_total, 2) }} total
                    </div>
                    @if($announcement->ends_at)
                        <div class="deal-end-date">Offer valid until {{ $announcement->ends_at->format('d M Y, h:i A') }}</div>
                    @endif

                    @if($announcement->foods && $announcement->foods->count())
                        <div class="announcement-foods">
                            @foreach($announcement->foods as $food)
                                <div class="announcement-food">
                                    <div class="announcement-food-image">
                                        @if($food->image)
                                            <img src="{{ $food->image }}" alt="{{ $food->name }}">
                                        @else
                                            <i class="fas fa-utensils" style="font-size:32px;color:#9ca3af;"></i>
                                        @endif
                                    </div>
                                    <span>{{ $food->name }}</span>
                                    <small>Qty: {{ $food->pivot->quantity ?? 1 }}</small>
                                    @if($food->pivot->deal_price)
                                        <span style="color:#16a34a;">Rs. {{ number_format($food->pivot->deal_price, 0) }}</span>
                                    @else
                                        <span style="color:#16a34a;">Rs. {{ number_format($food->price, 0) }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <button type="button" class="announcement-action" onclick="closeAnnouncement(); addDealToCart({{ $announcement->id }})"><i class="fas fa-shopping-cart"></i> Add Complete Deal</button>
                @endif

                @if($announcement->button_text)
                    <a href="#menu" class="announcement-action" onclick="closeAnnouncement()">{{ $announcement->button_text }}</a>
                @endif
            @empty
                <div class="announcement-kicker">FoodHub Updates</div>
                <h2>New deals coming soon</h2>
                <div class="announcement-message">No active offers right now. New deals will be shown here soon.</div>
            @endforelse
        </div>
    </div>


<section class="hero">

    <div class="hero-content">

        <h1>Delicious Food, <span>Delivered Fast.</span></h1>

        <p>Order your favorite meals from FoodHub. Fresh food, great taste and easy ordering.</p>

        <a href="#categories" class="hero-btn"><i class="fas fa-utensils"></i> Explore Menu</a>

    </div>

</section>


<section class="section" id="categories">

    <div class="section-title">

        <h2>Food Categories</h2>

        <p>Choose what you are craving today</p>

    </div>

    <div class="categories">

        <a
            href="#menu"
            class="category"
            onclick="showAllFood()"
        >

            <div class="category-icon">
                <i class="fas fa-utensils" style="font-size:45px;color:#ff6b00;"></i>
            </div>

            <h3>
                All Food
            </h3>

            <p>
                View all delicious food
            </p>

        </a>


        @forelse($categories as $category)

            <a
                href="#menu"
                class="category"
                onclick="filterCategory(
                    {{ $category->id }},
                    '{{ addslashes($category->name) }}'
                )"
            >

                <div class="category-icon">

                    @if($category->image)

                        <img
                            src="{{ $category->image }}"
                            style="
                                width:60px;
                                height:60px;
                                object-fit:cover;
                                border-radius:50%;
                            "
                        >

                    @else

                        🍽️

                    @endif

                </div>

                <h3>
                    {{ $category->name }}
                </h3>

                <p>
                    {{ $category->description ?? 'Delicious food items' }}
                </p>

            </a>

        @empty

            <div style="grid-column:1/-1;text-align:center;">
                No categories available.
            </div>

        @endforelse


    </div>

</section>



    
<section class="section" id="menu">

    <div class="section-title">

        <h2>
            <i class="fas fa-utensils"></i> Food Menu
        </h2>

        <p id="menuDescription">
            Our delicious food items
        </p>

    </div>


    <div class="menu-top">

        <div>

            Showing:

            <span
                class="selected-category"
                id="selectedCategory"
            >
                All Food
            </span>

        </div>            <a
                href="#menu"
                class="all-food-btn"
                onclick="showAllFood()"
            >
                <i class="fas fa-th-large"></i> Show All
            </a>

    </div>


    <div class="foods" id="foodContainer">


        @foreach($announcements as $deal)
            @if($deal->deal_total !== null && $deal->foods->count())
                <div class="menu-deal-card">
                    <div class="menu-deal-image">
                        @if($deal->deal_image)
                            <img src="{{ $deal->deal_image }}" alt="{{ $deal->title }}">
                        @else
                            🍔
                        @endif
                    </div>
                    <div class="menu-deal-info">
                        <h3><i class="fas fa-bullhorn"></i> {{ $deal->title }}</h3>
                        <div class="menu-deal-price">Complete Deal: Rs. {{ number_format($deal->deal_total, 2) }}</div>
                        @if($deal->ends_at)
                            <div class="deal-end-date">Until {{ $deal->ends_at->format('d M Y, h:i A') }}</div>
                        @endif
                    </div>
                    <button type="button" class="poster-order" onclick="addDealToCart({{ $deal->id }})">+ Add Deal</button>
                </div>
            @endif
        @endforeach


        @forelse($foods as $food)

            <div
                class="food-card"
                data-category="{{ $food->category_id }}"
            >


                <div class="food-image">

                    @if($food->image)

                        <img src="{{ $food->image }}">

                    @else

                        <i class="fas fa-utensils" style="font-size:50px;color:#d1d5db;"></i>

                    @endif

                </div>


                <div class="food-info">


                    <h3>
                        {{ $food->name }}
                    </h3>


                    <p>
                        {{ $food->description ?? 'Delicious and freshly prepared.' }}
                    </p>


                    <span class="food-category">

                        {{ $food->category->name ?? 'Food' }}

                    </span>

                    @if(in_array($food->id, $featuredFoodIds))
                        <span class="deal-badge"><i class="fas fa-bullhorn"></i> Featured Deal</span>
                    @endif


                    <div class="food-bottom">

                        <div class="price">

                            @if((float) ($dealPrices[$food->id] ?? $food->discounted_price) < (float) $food->price)
                                <span class="price-old">
                                    Rs. {{ number_format($food->price, 2) }}
                                </span>
                            @endif

                            Rs. {{ number_format($dealPrices[$food->id] ?? $food->discounted_price, 2) }}

                            @if($food->hasDiscount())
                                <span class="discount-badge">
                                    -{{ rtrim(rtrim(number_format($food->discount_percentage, 2), '0'), '.') }}%
                                </span>
                            @endif

                        </div>


                        <button
                            type="button"
                            class="order-btn"
                            onclick="addToCart({{ $food->id }}, {{ $dealAnnouncementIds[$food->id] ?? 'null' }})"
                        >
                            + Add
                        </button>


                    </div>


                </div>


            </div>

        @empty

            <div class="empty">

                <i class="fas fa-utensils" style="font-size:50px;color:#d1d5db;"></i>

                <h3>
                    No food items available
                </h3>

                <p>
                    Please check again later.
                </p>

            </div>

        @endforelse


    </div>

</section>



<div
    class="cart-overlay"
    id="cartOverlay"
    onclick="closeCart()"
></div>


<!-- ===== DRAGGABLE FLOATING CART BUTTON (Mobile Only) ===== -->
<button
    class="draggable-cart-btn"
    id="draggableCartBtn"
    title="View Cart"
>
    🛒
    <span class="fab-count" id="fabCartCount">0</span>
</button>


<div
    class="side-cart"
    id="sideCart"
>


    <div class="cart-header">

        <h2>
            <i class="fas fa-shopping-cart"></i> Your Cart
        </h2>

        <button
            class="close-cart"
            onclick="closeCart()"
        >
            ✕
        </button>

    </div>


    <div
        class="cart-items"
        id="sideCartItems"
    >

        <!-- Cart will load here -->

    </div>


    <div class="cart-footer">


        <div class="cart-total-row">

            <span>
                Total:
            </span>

            <span
                class="side-total"
                id="sideCartTotal"
            >
                Rs. 0.00
            </span>

        </div>


        <a
            href="{{ route('checkout') }}"
            class="checkout-btn"
        >
            Proceed to Checkout →
        </a>


        <button
            class="continue-btn"
            onclick="closeCart()"
        >
            Continue Shopping
        </button>


    </div>


</div>



<div
    class="toast"
    id="toast"
>
    Food added to cart!
</div>



  <!-- ================= FULL MENU POSTER ================= -->

<section class="full-menu-section" id="full-menu">

    <div class="menu-poster">

        <button
            type="button"
            class="menu-close"
            aria-label="Close menu"
            onclick="closeMenu()"
        >
            ×
        </button>

        <div class="poster-header">

            <div class="poster-logo">
                <i class="fas fa-utensils"></i> FOODHUB
            </div>

            <div class="poster-subtitle">
                HOTEL & RESTAURANT
            </div>

            <h2>
                OUR MENU
            </h2>

            <p>
                Delicious Food • Fresh Ingredients • Great Taste
            </p>

        </div>


        <div class="poster-content">

            @foreach($announcements as $deal)
                @if($deal->deal_total !== null && $deal->foods->count())
                    <div class="poster-deal">
                        <div class="poster-deal-image">
                            @if($deal->deal_image)
                                <img src="{{ $deal->deal_image }}" alt="{{ $deal->title }}">
                            @else
                                🍔
                            @endif
                        </div>
                        <div class="poster-deal-info">
                            <h3>{{ $deal->title }}</h3>
                            <strong>Complete Deal: Rs. {{ number_format($deal->deal_total, 2) }}</strong>
                            @if($deal->foods && $deal->foods->count())
                                <div style="margin-top:6px;" class="poster-deal-items">
                                    @foreach($deal->foods as $dfood)
                                        <small class="poster-deal-item">{{ $dfood->name }} x{{ $dfood->pivot->quantity ?? 1 }}</small>
                                    @endforeach
                                </div>
                            @endif
                            @if($deal->ends_at)
                                <p class="deal-end-date">Until {{ $deal->ends_at->format('d M Y, h:i A') }}</p>
                            @endif
                        </div>
                        <button type="button" class="poster-order" onclick="closeMenu(); addDealToCart({{ $deal->id }})">+ Add Deal</button>
                    </div>
                @endif
            @endforeach

            @forelse($categories as $category)

                @php
                    $categoryFoods = $foods->where(
                        'category_id',
                        $category->id
                    );
                @endphp


                @if($categoryFoods->count())

                    <div class="poster-category">

                        <div class="poster-category-title">

                            <span>
                                <i class="fas fa-utensils"></i>
                            </span>

                            {{ strtoupper($category->name) }}

                        </div>


                        <div class="poster-items">

                            @foreach($categoryFoods as $food)

                                <div class="poster-item">

                                    <div class="poster-item-left">

                                        <strong>
                                            {{ $food->name }}
                                        </strong>

                                        @if(in_array($food->id, $featuredFoodIds))
                                            <span class="deal-badge"><i class="fas fa-bullhorn"></i> Deal</span>
                                        @endif

                                        @if($food->description)

                                            <small>
                                                {{ $food->description }}
                                            </small>

                                        @endif

                                    </div>


                                    <div class="poster-price">

                                        @if($food->hasDiscount())
                                            <span class="poster-old-price">
                                                Rs. {{ number_format($food->price, 0) }}
                                            </span>
                                        @endif

                                        Rs. {{ number_format($dealPrices[$food->id] ?? $food->discounted_price, 0) }}

                                        <button type="button" class="poster-order" onclick="closeMenu(); addToCart({{ $food->id }}, {{ $dealAnnouncementIds[$food->id] ?? 'null' }})">+ Add</button>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            @empty

                <div class="poster-empty">

                    <i class="fas fa-utensils" style="font-size:50px;"></i>

                    <h3>
                        Menu Coming Soon
                    </h3>

                </div>

            @endforelse


            <!-- ITEMS WITHOUT CATEGORY -->

            @php
                $uncategorizedFoods = $foods->whereNull('category_id');
            @endphp


            @if($uncategorizedFoods->count())

                <div class="poster-category">

                    <div class="poster-category-title">

                        🍴 OTHER ITEMS

                    </div>


                    <div class="poster-items">

                        @foreach($uncategorizedFoods as $food)

                            <div class="poster-item">

                                <div class="poster-item-left">

                                    <strong>
                                        {{ $food->name }}
                                    </strong>

                                    @if(in_array($food->id, $featuredFoodIds))
                                        <span class="deal-badge"><i class="fas fa-bullhorn"></i> Deal</span>
                                    @endif

                                    @if($food->description)

                                        <small>
                                            {{ $food->description }}
                                        </small>

                                    @endif

                                </div>


                                <div class="poster-price">

                                    @if($food->hasDiscount())
                                        <span class="poster-old-price">
                                            Rs. {{ number_format($food->price, 0) }}
                                        </span>
                                    @endif

                                    Rs. {{ number_format($dealPrices[$food->id] ?? $food->discounted_price, 0) }}

                                    <button type="button" class="poster-order" onclick="closeMenu(); addToCart({{ $food->id }}, {{ $dealAnnouncementIds[$food->id] ?? 'null' }})">+ Add</button>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>


        <div class="poster-footer">

            <div>
                <i class="fas fa-utensils"></i> FOODHUB HOTEL
            </div>

            <span>
                Fresh • Tasty • Affordable
            </span>

        </div>

    </div>

</section>  
<footer>

    © {{ date('Y') }} FoodHub.
    All Rights Reserved.

</footer>


<script>




let cart = @json(session()->get('cart', []));


    

function money(value) {

    return Number(value).toLocaleString(
        'en-PK',
        {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }
    );

}


    

function showToast(message) {

    const toast = document.getElementById('toast');


    toast.innerText = message;

    toast.classList.add('show');


    setTimeout(function() {

        toast.classList.remove('show');

    }, 1800);

}


    
function openMenu(event) {

    event.preventDefault();

    document
        .getElementById('full-menu')
        .classList.add('is-open');

    document.body.style.overflow = 'hidden';

}


function closeMenu() {

    document
        .getElementById('full-menu')
        .classList.remove('is-open');

    document.body.style.overflow = '';

}


document
    .getElementById('full-menu')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            closeMenu();
        }

    });


document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {
        closeMenu();
    }

});


function openAnnouncement(event) {

    event.preventDefault();

    const announcement = document.getElementById('announcement');

    if (!announcement) {
        return;
    }

    announcement.classList.add('is-open');
    document.body.style.overflow = 'hidden';

}


function closeAnnouncement() {

    const announcement = document.getElementById('announcement');

    if (!announcement) {
        return;
    }

    announcement.classList.remove('is-open');
    document.body.style.overflow = '';

}


function closeAnnouncementOnBackdrop(event) {

    if (event.target === event.currentTarget) {
        closeAnnouncement();
    }

}


function openCart() {

    if (Object.keys(cart).length === 0 && window.innerWidth > 900) {
        return;
    }

    document
        .getElementById('sideCart')
        .classList.add('open');

    document
        .getElementById('cartOverlay')
        .classList.add('show');

    renderCart();

}

    

function closeCart() {

    document
        .getElementById('sideCart')
        .classList.remove('open');

    document
        .getElementById('cartOverlay')
        .classList.remove('show');

}


    

function addToCart(foodId, announcementId) {
    if (typeof announcementId === 'undefined' || announcementId === 'null') announcementId = null;
    var body = { announcement_id: announcementId };
    var headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        'Accept': 'application/json'
    };
    fetch('/cart/add/' + foodId, { method: 'POST', headers: headers, body: JSON.stringify(body) })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        cart = data.cart;
        updateCartUI();
        openCart();
        showToast('\u2705 Food added to cart!');
    })
    .catch(function(e) {
        console.error(e);
        showToast('\u274c Could not add item.');
    });
}


function addDealToCart(announcementId) {

    fetch(`/cart/add-deal/${announcementId}`, {

        method: 'POST',

        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }

    })
    .then(response => {

        if (!response.ok) {
            throw new Error('Deal could not be added');
        }

        return response.json();

    })
    .then(data => {

        cart = data.cart;
        updateCartUI();
        openCart();
        showToast('Deal added to cart!');

    })
    .catch(error => {

        console.error(error);
        showToast('Deal could not be added');

    });

}



function changeQuantity(id, change) {


    const item = cart[id];

    if (!item) {
        return;
    }


    let newQuantity =
        parseInt(item.quantity) + change;


    if (newQuantity < 1) {

        removeFromCart(id);

        return;

    }


    updateQuantity(id, newQuantity);

}


function updateQuantity(id, quantity) {


    fetch('/cart/update-json', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}',

            'Accept': 'application/json'

        },

        body: JSON.stringify({

            id: id,
            quantity: quantity

        })

    })

    .then(response => {

        if (!response.ok) {
            throw new Error('Update failed');
        }

        return response.json();

    })

    .then(data => {

        cart = data.cart;

        updateCartUI();

    })

    .catch(error => {

        console.error(error);

        showToast('Cart update failed. Please refresh and try again.');

    });

}


function removeFromCart(id) {

    fetch('/cart/remove-json', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Remove failed');
        }
        return response.json();
    })
    .then(data => {
        cart = data.cart;
        updateCartUI();
        showToast('Item removed');
    })
    .catch(error => {
        console.error(error);
        showToast('Item remove failed. Please refresh and try again.');
    });
}


function updateCartUI() {
    renderCart();
    updateCartCount();
}



function updateCartCount() {


    let count = 0;


    Object.values(cart).forEach(item => {

        count += parseInt(item.quantity);

    });


    document
        .getElementById('navCartCount')
        .innerText = count;

    // Update floating button badge
    const fabCount = document.getElementById('fabCartCount');
    if (fabCount) {
        fabCount.innerText = count;
        fabCount.style.display = count > 0 ? 'flex' : 'none';
    }

}


/* ===================================================
   DRAGGABLE FLOATING CART BUTTON LOGIC
   =================================================== */
(function initDraggableCartBtn() {

    const btn = document.getElementById('draggableCartBtn');
    if (!btn) return;

    let isDragging   = false;
    let hasMoved     = false;
    let startX, startY, startLeft, startTop;

    /* ---------- pointer events (touch + mouse) ---------- */
    btn.addEventListener('pointerdown', function(e) {

        isDragging = true;
        hasMoved   = false;

        const rect = btn.getBoundingClientRect();

        startX    = e.clientX;
        startY    = e.clientY;
        startLeft = rect.left;
        startTop  = rect.top;

        btn.setPointerCapture(e.pointerId);
        btn.style.transition = 'none';
        e.preventDefault();

    });

    btn.addEventListener('pointermove', function(e) {

        if (!isDragging) return;

        const dx = e.clientX - startX;
        const dy = e.clientY - startY;

        if (Math.abs(dx) > 5 || Math.abs(dy) > 5) {
            hasMoved = true;
        }

        // Clamp inside viewport
        const maxLeft = window.innerWidth  - btn.offsetWidth;
        const maxTop  = window.innerHeight - btn.offsetHeight;

        const newLeft = Math.max(0, Math.min(startLeft + dx, maxLeft));
        const newTop  = Math.max(0, Math.min(startTop  + dy, maxTop));

        btn.style.right  = 'auto';
        btn.style.bottom = 'auto';
        btn.style.left   = newLeft + 'px';
        btn.style.top    = newTop  + 'px';

        e.preventDefault();

    });

    btn.addEventListener('pointerup', function(e) {

        if (!isDragging) return;
        isDragging = false;

        btn.style.transition = '';

        // If barely moved → treat as click → open cart
        if (!hasMoved) {
            openCart();
        }

    });

    btn.addEventListener('pointercancel', function() {
        isDragging = false;
        btn.style.transition = '';
    });

    })();

    // Customer hamburger menu toggle
    function toggleCustomerNav() {
        var navLinks = document.getElementById("customerNavLinks");
        var hamburger = document.querySelector(".customer-hamburger");
        if (navLinks && hamburger) {
            navLinks.classList.toggle("open");
            hamburger.classList.toggle("active");
        }
    }
    document.querySelectorAll("#customerNavLinks a").forEach(function(link) {
        link.addEventListener("click", function() {
            var navLinks = document.getElementById("customerNavLinks");
            var hamburger = document.querySelector(".customer-hamburger");
            if (navLinks) navLinks.classList.remove("open");
            if (hamburger) hamburger.classList.remove("active");
        });
    });
    document.addEventListener("click", function(e) {
        var nav = document.querySelector("nav");
        var navLinks = document.getElementById("customerNavLinks");
        var hamburger = document.querySelector(".customer-hamburger");
        if (nav && !nav.contains(e.target)) {
            if (navLinks) navLinks.classList.remove("open");
            if (hamburger) hamburger.classList.remove("active");
        }
    });


function renderCart() {


    const container =
        document.getElementById('sideCartItems');


    const totalElement =
        document.getElementById('sideCartTotal');


    const items =
        Object.values(cart);


    if (items.length === 0) {


        container.innerHTML = `

            <div class="cart-empty">

                <div class="cart-empty-icon">
                    <i class="fas fa-shopping-cart" style="font-size:55px;color:#d1d5db;"></i>
                </div>

                <h3>
                    Your Cart is Empty
                </h3>

                <p>
                    Add some delicious food.
                </p>

            </div>

        `;


        totalElement.innerText =
            'Rs. 0.00';


        updateCartCount();

        if (window.innerWidth > 900) {
            closeCart();
        }

        return;

    }


    let total = 0;


    let html = '';


    items.forEach(function(item) {


        const subtotal =
            Number(item.price) *
            Number(item.quantity);


        total += subtotal;


        html += `

            <div class="side-cart-item">


                <div class="side-cart-image">

                    ${
                        item.image

                        ?

                        `<img
                            src="${item.image}"
                            alt="${item.name}"
                        >`

                        :

                        `🍔`
                    }

                </div>


                <div class="side-cart-info">


                    <h4>
                        ${escapeHtml(item.name)}
                    </h4>


                    <div class="side-cart-price">

                        Rs.
                        ${money(subtotal)}

                    </div>


                    <div class="quantity-controls">


                        <button
                            class="qty-btn"
                            onclick="changeQuantity('${escapeHtml(item.cart_key || item.id)}', -1)"
                        >
                            −
                        </button>


                        <span class="qty-number">
                            ${item.quantity}
                        </span>


                        <button
                            class="qty-btn"
                            onclick="changeQuantity('${escapeHtml(item.cart_key || item.id)}', 1)"
                        >
                            +
                        </button>


                    </div>


                </div>


                <button
                    class="remove-item"
                    onclick="removeFromCart('${escapeHtml(item.cart_key || item.id)}')"
                    title="Remove"
                >
                    <i class="fas fa-trash"></i>
                </button>


            </div>

        `;

    });


    container.innerHTML = html;


    totalElement.innerText =
        'Rs. ' + money(total);


    updateCartCount();

}



function escapeHtml(text) {

    const div =
        document.createElement('div');

    div.innerText = text;

    return div.innerHTML;

}


function filterCategory(
    categoryId,
    categoryName
) {


    const foods =
        document.querySelectorAll('.food-card');


    let found = false;


    foods.forEach(function(food) {


        if (
            food.dataset.category ==
            categoryId
        ) {

            food.style.display = 'block';

            found = true;

        } else {

            food.style.display = 'none';

        }

    });


    document
        .getElementById('selectedCategory')
        .innerText = categoryName;


    document
        .getElementById('menuDescription')
        .innerText =
        'Showing food from ' + categoryName;


    if (!found) {

        document
            .getElementById('foodContainer')
            .innerHTML = `

                <div class="empty">

                    🍔

                    <h3>
                        No food items in this category
                    </h3>

                    <p>
                        Please check another category.
                    </p>

                </div>

            `;

    }

}



function showAllFood() {


    location.reload();

}



document.addEventListener(
    'DOMContentLoaded',
    function() {

        updateCartUI();

    }
);


</script>

<script src="{{ asset('js/foodhub-cart.js') }}"></script>


<script>
    function toggleCustomerTheme() {
        var body = document.body;
        var icon = document.querySelector(".theme-icon-customer");
        if (body.classList.contains("dark-theme")) {
            body.classList.remove("dark-theme");
            if (icon) icon.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem("foodhub-theme", "light");
        } else {
            body.classList.add("dark-theme");
            if (icon) icon.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem("foodhub-theme", "dark");
        }
    }
    (function() {
        var saved = localStorage.getItem("foodhub-theme");
        var icon = document.querySelector(".theme-icon-customer");
        if (saved === "dark") {
            document.body.classList.add("dark-theme");
            if (icon) icon.innerHTML = '<i class="fas fa-sun"></i>';
        }
    })();

    // Customer hamburger menu toggle
    function toggleCustomerNav() {
        var navLinks = document.getElementById("customerNavLinks");
        var hamburger = document.querySelector(".customer-hamburger");
        if (navLinks && hamburger) {
            navLinks.classList.toggle("open");
            hamburger.classList.toggle("active");
        }
    }
    document.querySelectorAll("#customerNavLinks a").forEach(function(link) {
        link.addEventListener("click", function() {
            var navLinks = document.getElementById("customerNavLinks");
            var hamburger = document.querySelector(".customer-hamburger");
            if (navLinks) navLinks.classList.remove("open");
            if (hamburger) hamburger.classList.remove("active");
        });
    });
    document.addEventListener("click", function(e) {
        var nav = document.querySelector("nav");
        var navLinks = document.getElementById("customerNavLinks");
        var hamburger = document.querySelector(".customer-hamburger");
        if (nav && !nav.contains(e.target)) {
            if (navLinks) navLinks.classList.remove("open");
            if (hamburger) hamburger.classList.remove("active");
        }
    });</script>
    <script src="{{ asset('js/scroll-animations.js') }}"></script>
</body>

</html>
