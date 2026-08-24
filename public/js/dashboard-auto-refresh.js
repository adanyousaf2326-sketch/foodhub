(function() {
    var autoRefresh = localStorage.getItem('dashboardAutoRefresh') !== 'false';
    var lastPendingCount = window.__dashboardPendingCount || 0;
    var lastTotalCount = window.__dashboardTotalCount || 0;
    var lastCompletedCount = window.__dashboardCompletedCount || 0;
    var lastCancelledCount = window.__dashboardCancelledCount || 0;
    var lastPreparingCount = window.__dashboardPreparingCount || 0;
    var pollInterval = null;
    var notifications = [];
    var maxNotifications = 50;
    var knownOrders = {};

    var refreshBtn = document.getElementById('autoRefreshToggle');
    var notifBtn = document.getElementById('notifToggle');
    var notifBadge = document.getElementById('notifBadge');
    var notifDropdown = document.getElementById('notifDropdown');
    var notifList = document.getElementById('notifList');
    if (refreshBtn) updateBtnUI();

    function updateBtnUI() {
        if (!refreshBtn) return;
        refreshBtn.innerHTML = autoRefresh
            ? '<span>\uD83D\uDD14</span> Live: ON'
            : '<span>\uD83D\uDD15</span> Live: OFF';
        refreshBtn.style.background = autoRefresh ? '#16a34a' : '#6b7280';
    }

    window.toggleAutoRefresh = function() {
        autoRefresh = !autoRefresh;
        localStorage.setItem('dashboardAutoRefresh', autoRefresh);
        updateBtnUI();
        if (autoRefresh) startPolling();
        else stopPolling();
    };

    window.toggleNotifDropdown = function() {
        if (!notifDropdown) return;
        var isOpen = notifDropdown.style.display === 'block';
        notifDropdown.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) {
            // Mark all as read
            notifications.forEach(function(n) { n.read = true; });
            if (notifBadge) notifBadge.textContent = '';
        }
    };

    window.clearNotifications = function() {
        notifications = [];
        if (notifList) notifList.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">\uD83D\uDD14 No notifications yet</div>';
        if (notifBadge) notifBadge.textContent = '';
    };

    function addNotification(type, message) {
        var now = new Date();
        var timeStr = now.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
        notifications.unshift({ type: type, message: message, time: timeStr, read: false });
        if (notifications.length > maxNotifications) notifications.pop();
        renderNotifications();
    }

    function renderNotifications() {
        if (!notifList) return;
        if (notifications.length === 0) {
            notifList.innerHTML = '<div style="text-align:center;padding:30px;color:#777;">\uD83D\uDD14 No notifications yet</div>';
            if (notifBadge) notifBadge.textContent = '';
            return;
        }

        var html = '';
        notifications.forEach(function(n) {
            var icons = { 'new': '\uD83C\uDD95', 'cancel': '\u274C', 'complete': '\u2705', 'preparing': '\uD83D\uDC68\u200D\uD83C\uDF73', 'update': '\uD83D\uDCE6', 'edit': '\u270F\uFE0F' };
            var bgColors = { 'new': '#fff7ed', 'cancel': '#fef2f2', 'complete': '#f0fdf4', 'preparing': '#eff6ff', 'update': '#f8fafc', 'edit': '#fefce8' };
            var txtColors = { 'new': '#c2410c', 'cancel': '#991b1b', 'complete': '#166534', 'preparing': '#1d4ed8', 'update': '#334155', 'edit': '#a16207' };
            var borders = { 'new': '#ff6b00', 'cancel': '#ef4444', 'complete': '#22c55e', 'preparing': '#3b82f6', 'update': '#94a3b8', 'edit': '#eab308' };

            var icon = icons[n.type] || '\uD83D\uDCCB';
            var bg = bgColors[n.type] || '#f8fafc';
            var tc = txtColors[n.type] || '#334155';
            var bc = borders[n.type] || '#94a3b8';

            html += '<div style="padding:12px 14px;border-bottom:1px solid #eee;background:' + bg + ';border-left:4px solid ' + bc + ';">' +
                '<div style="display:flex;align-items:flex-start;gap:10px;">' +
                '<span style="font-size:20px;">' + icon + '</span>' +
                '<div style="flex:1;">' +
                '<div style="font-size:13px;font-weight:600;color:' + tc + ';">' + n.message + '</div>' +
                '<div style="font-size:11px;color:#999;margin-top:3px;">' + n.time + '</div>' +
                '</div></div></div>';
        });
        notifList.innerHTML = html;
        var unread = notifications.filter(function(n) { return !n.read; }).length;
        if (notifBadge) notifBadge.textContent = unread > 0 ? unread : '';
    }

    function playSound() {
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

    function flashPending() {
        var cards = document.querySelectorAll('.stat-card');
        if (cards[2]) {
            cards[2].style.transition = 'box-shadow 0.3s';
            cards[2].style.boxShadow = '0 0 25px rgba(255,107,0,0.6)';
            setTimeout(function() { cards[2].style.boxShadow = ''; }, 2500);
        }
    }

    function showToast(msg) {
        var t = document.getElementById('toast');
        if (!t) { t = document.createElement('div'); t.className = 'toast'; t.style.cssText = 'position:fixed;right:25px;bottom:25px;background:#111827;color:white;padding:14px 20px;border-radius:9px;box-shadow:0 5px 20px rgba(0,0,0,.2);transform:translateY(100px);opacity:0;transition:.3s;z-index:100000;'; document.body.appendChild(t); }
        t.innerText = msg;
        t.classList.add('show');
        setTimeout(function() { t.classList.remove('show'); }, 3000);
    }

    function detectOrderChanges(orders) {
        var ts = new Date().toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});

        orders.forEach(function(o) {
            var known = knownOrders[o.id];
            if (!known) return;

            if (known.status !== o.status) {
                var emojis = { 'Pending': '\u23F3', 'Preparing': '\uD83D\uDC68\u200D\uD83C\uDF73', 'Completed': '\u2705', 'Delivered': '\uD83D\uDE9A', 'Cancelled': '\u274C' };
                var emoji = emojis[o.status] || '\uD83D\uDCCB';
                var msg = '#' + o.id + ' status: ' + known.status + ' \u2192 ' + o.status;
                showToast(emoji + ' ' + msg);
                addNotification('edit', msg + ' (' + ts + ')');
                playSound();
            }

            if (known.order_type !== o.order_type) {
                var msg = '#' + o.id + ' type: ' + known.order_type + ' \u2192 ' + o.order_type;
                showToast('\uD83D\uDD04 ' + msg);
                addNotification('edit', msg + ' (' + ts + ')');
                playSound();
            }

            if (Math.abs(known.total_amount - o.total_amount) > 0.01) {
                var diff = o.total_amount - known.total_amount;
                var direction = diff > 0 ? 'increased' : 'decreased';
                var msg = '#' + o.id + ' total ' + direction + ': Rs. ' + Math.abs(diff).toFixed(2);
                showToast('\uD83D\uDCB0 ' + msg);
                addNotification('edit', msg + ' (' + ts + ')');
                playSound();
            }
        });

        orders.forEach(function(o) {
            knownOrders[o.id] = {
                status: o.status,
                order_type: o.order_type,
                total_amount: parseFloat(o.total_amount)
            };
        });
    }

    function buildRow(o) {
        var sm = { 'Pending':'pending','Preparing':'preparing','Completed':'completed','Delivered':'delivered','Cancelled':'cancelled' };
        var tm = { 'Dine In':'dine-in','Delivery':'delivery','Takeaway':'takeaway','Take Away':'takeaway','TakeAway':'takeaway' };
        var sc = sm[o.status] || 'pending';
        var tc = tm[o.order_type] || 'takeaway';
        var te = o.order_type === 'Dine In' ? '\uD83C\uDF7D\uFE0F' : (o.order_type === 'Delivery' ? '\uD83D\uDEF5' : '\uD83E\uDD61');
        var d = new Date(o.created_at);
        var ds = d.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'});
        var ts = d.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});
        var act = o.status !== 'Completed' && o.status !== 'Cancelled'
            ? '<a href="/admin/orders/' + o.id + '/bill" class="view">\uD83D\uDCB3 Bill</a>'
            : '<a href="/admin/orders/' + o.id + '" class="view">View</a>';
        return '<tr><td>#' + o.id + '</td><td><strong>' + o.customer_name + '</strong></td><td>' + o.phone + '</td><td><span class="order-type ' + tc + '">' + te + ' ' + o.order_type + '</span></td><td>Rs. ' + Number(o.total_amount).toLocaleString('en-PK', {minimumFractionDigits:2}) + '</td><td>' + o.payment_method + '</td><td><span class="status ' + sc + '">' + o.status + '</span></td><td>' + ds + '<br><small>' + ts + '</small></td><td>' + act + '</td></tr>';
    }

    function updateTable(orders) {
        var tbody = document.querySelector('.table-card table tbody');
        if (!tbody) return;
        if (!orders.length) { tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#777;padding:40px;">\uD83D\uDCCB No orders found</td></tr>'; return; }
        var html = '';
        orders.forEach(function(o) { html += buildRow(o); });
        tbody.innerHTML = html;
    }

    function poll() {
        var params = new URLSearchParams(window.location.search);
        params.set('_', Date.now());

        fetch('/admin/dashboard/orders-json?' + params.toString(), {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function(r) {
                if (!r.ok) throw new Error('Not OK');
                return r.json();
            })
            .then(function(data) {
                var ts = new Date().toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});

                // Detect individual order changes
                detectOrderChanges(data.orders);

                if (data.pending_count > lastPendingCount) {
                    var diff = data.pending_count - lastPendingCount;
                    playSound(); flashPending();
                    showToast('\uD83D\uDD14 ' + diff + ' new order received!');
                    addNotification('new', diff + ' new order received! (' + ts + ')');
                }

                if (data.completed_count > lastCompletedCount) {
                    var diff = data.completed_count - lastCompletedCount;
                    showToast('\u2705 ' + diff + ' order completed');
                    addNotification('complete', diff + ' order completed (' + ts + ')');
                    playSound();
                }

                if (data.cancelled_count > lastCancelledCount) {
                    var diff = data.cancelled_count - lastCancelledCount;
                    showToast('\u274C ' + diff + ' order cancelled');
                    addNotification('cancel', diff + ' order cancelled (' + ts + ')');
                    playSound();
                }

                if (data.preparing_count > lastPreparingCount) {
                    var diff = data.preparing_count - lastPreparingCount;
                    showToast('\uD83D\uDC68\u200D\uD83C\uDF73 ' + diff + ' order is being prepared');
                    addNotification('preparing', diff + ' order is being prepared (' + ts + ')');
                }

                if (data.total_count > lastTotalCount && data.pending_count <= lastPendingCount) {
                    addNotification('update', 'New order received (' + ts + ')');
                    playSound(); flashPending();
                }

                lastPendingCount = data.pending_count;
                lastTotalCount = data.total_count;
                lastCompletedCount = data.completed_count;
                lastCancelledCount = data.cancelled_count;
                lastPreparingCount = data.preparing_count;

                var el1 = document.getElementById('pendingOrdersNum');
                var el2 = document.getElementById('totalOrdersNum');
                var el3 = document.getElementById('completedOrdersNum');
                if (el1) el1.textContent = data.pending_count;
                if (el2) el2.textContent = data.total_count;
                if (el3) el3.textContent = data.completed_count;

                updateTable(data.orders);
            })
            .catch(function(e) {
                console.log('Auto-refresh poll failed:', e.message);
            });
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(poll, 5000);
    }

    function stopPolling() {
        if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (notifDropdown && notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.style.display = 'none';
        }
    });

    // Start polling
    if (autoRefresh) {
        startPolling();
    }
})();
