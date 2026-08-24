(function() {
    var autoRefresh = localStorage.getItem('dashboardAutoRefresh') !== 'false';
    var lastPendingCount = window.__dashboardPendingCount || 0;
    var pollInterval = null;

    var refreshBtn = document.getElementById('autoRefreshToggle');
    if (refreshBtn) updateBtnUI();

    function updateBtnUI() {
        if (!refreshBtn) return;
        refreshBtn.innerHTML = autoRefresh
            ? '<span>🔔</span> Auto-Refresh: ON'
            : '<span>🔕</span> Auto-Refresh: OFF';
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
            cards[2].style.boxShadow = '0 0 20px rgba(255,107,0,0.5)';
            setTimeout(function() { cards[2].style.boxShadow = ''; }, 2000);
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

    function poll() {
        var params = new URLSearchParams(window.location.search);
        params.set('_', Date.now());

        fetch('/admin/dashboard/orders-json?' + params.toString())
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.pending_count > lastPendingCount) {
                    playNotificationSound();
                    flashPendingCard();
                    showToast('🔔 Naya order aaya hai!');
                }
                lastPendingCount = data.pending_count;
                var el1 = document.getElementById('pendingOrdersNum');
                var el2 = document.getElementById('totalOrdersNum');
                if (el1) el1.textContent = data.pending_count;
                if (el2) el2.textContent = data.total_count;
            })
            .catch(function() {});
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(poll, 15000);
    }

    function stopPolling() {
        if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
    }

    if (autoRefresh) startPolling();
})();
