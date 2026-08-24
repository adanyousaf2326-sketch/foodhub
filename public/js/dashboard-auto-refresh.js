(function() {
    var autoRefresh = localStorage.getItem('dashboardAutoRefresh') !== 'false';
    var lastPendingCount = window.__dashboardPendingCount || 0;
    var lastTotalCount = window.__dashboardTotalCount || 0;
    var pollInterval = null;

    var refreshBtn = document.getElementById('autoRefreshToggle');
    if (refreshBtn) updateBtnUI();

    function updateBtnUI() {
        if (!refreshBtn) return;
        refreshBtn.innerHTML = autoRefresh
            ? '<span>🔔</span> Live: ON'
            : '<span>🔕</span> Live: OFF';
        refreshBtn.style.background = autoRefresh ? '#16a34a' : '#6b7280';
    }

    window.toggleAutoRefresh = function() {
        autoRefresh = !autoRefresh;
        localStorage.setItem('dashboardAutoRefresh', autoRefresh);
        updateBtnUI();
        if (autoRefresh) startPolling();
        else stopPolling();
    };

    function playNotificationSound() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.frequency.setValueAtTime(1100, ctx.currentTime + 0.1);
            osc.frequency.setValueAtTime(880, ctx.currentTime + 0.2);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.5);
        } catch(e) {}
    }

    function flashPendingCard() {
        var cards = document.querySelectorAll('.stat-card');
        if (cards[2]) {
            cards[2].style.transition = 'box-shadow 0.3s';
            cards[2].style.boxShadow = '0 0 25px rgba(255,107,0,0.6)';
            setTimeout(function() { cards[2].style.boxShadow = ''; }, 2500);
        }
    }

    function showToast(msg) {
        var t = document.getElementById('toast');
        if (!t) {
            t = document.createElement('div');
            t.className = 'toast';
            document.body.appendChild(t);
        }
        t.innerText = msg;
        t.classList.add('show');
        setTimeout(function() { t.classList.remove('show'); }, 3000);
    }

    function buildOrderRow(order) {
        var statusMap = {
            'Pending': 'pending',
            'Preparing': 'preparing',
            'Completed': 'completed',
            'Delivered': 'delivered',
            'Cancelled': 'cancelled'
        };
        var typeMap = {
            'Dine In': 'dine-in',
            'Delivery': 'delivery',
            'Takeaway': 'takeaway',
            'Take Away': 'takeaway',
            'TakeAway': 'takeaway'
        };
        var statusClass = statusMap[order.status] || 'pending';
        var typeClass = typeMap[order.order_type] || 'takeaway';
        var typeEmoji = order.order_type === 'Dine In' ? '🍽️' : (order.order_type === 'Delivery' ? '🛵' : '🥡');
        var date = new Date(order.created_at);
        var dateStr = date.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'});
        var timeStr = date.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});

        return '<tr>' +
            '<td>#' + order.id + '</td>' +
            '<td><strong>' + escapeHtml(order.customer_name) + '</strong></td>' +
            '<td>' + escapeHtml(order.phone) + '</td>' +
            '<td><span class="order-type ' + typeClass + '">' + typeEmoji + ' ' + escapeHtml(order.order_type) + '</span></td>' +
            '<td>Rs. ' + Number(order.total_amount).toLocaleString('en-PK', {minimumFractionDigits:2}) + '</td>' +
            '<td>' + escapeHtml(order.payment_method) + '</td>' +
            '<td><span class="status ' + statusClass + '">' + escapeHtml(order.status) + '</span></td>' +
            '<td>' + dateStr + '<br><small>' + timeStr + '</small></td>' +
            '<td><a href="/admin/orders/' + order.id + '" class="view">View</a></td>' +
            '</tr>';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.innerText = text || '';
        return div.innerHTML;
    }

    function updateOrdersTable(orders) {
        var tbody = document.querySelector('.table-card table tbody');
        if (!tbody) return;

        if (orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#777;padding:40px;">📋 No orders found</td></tr>';
            return;
        }

        var html = '';
        orders.forEach(function(order) {
            html += buildOrderRow(order);
        });
        tbody.innerHTML = html;
    }

    function poll() {
        var params = new URLSearchParams(window.location.search);
        params.set('_', Date.now());

        fetch('/admin/dashboard/orders-json?' + params.toString())
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var hasNewOrders = data.pending_count > lastPendingCount;
                var hasNewAny = data.total_count > lastTotalCount;

                if (hasNewOrders) {
                    playNotificationSound();
                    flashPendingCard();
                    showToast('🔔 Naya order aaya hai! #' + (data.orders[0] ? data.orders[0].id : ''));
                } else if (hasNewAny) {
                    showToast('📦 Order update hua hai');
                }

                lastPendingCount = data.pending_count;
                lastTotalCount = data.total_count;

                // Update stat numbers
                var el1 = document.getElementById('pendingOrdersNum');
                var el2 = document.getElementById('totalOrdersNum');
                if (el1) el1.textContent = data.pending_count;
                if (el2) el2.textContent = data.total_count;

                // Update orders table live
                updateOrdersTable(data.orders);
            })
            .catch(function() {});
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(poll, 10000); // every 10 seconds
    }

    function stopPolling() {
        if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
    }

    if (autoRefresh) startPolling();
})();
