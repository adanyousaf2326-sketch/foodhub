// Scroll-triggered fade-in animations
(function() {
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right, .scale-in').forEach(function(el) {
            observer.observe(el);
        });
    });

    // Count-up animation for stat numbers
    window.animateCountUp = function(el, target, duration) {
        duration = duration || 1500;
        var start = 0;
        var startTime = null;
        var isDecimal = target % 1 !== 0;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
            var current = Math.floor(eased * target);
            el.textContent = isDecimal ? (eased * target).toFixed(1) : current.toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = isDecimal ? target.toFixed(1) : target.toLocaleString();
            }
        }
        requestAnimationFrame(step);
    };

    // Auto-animate stat numbers on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-count]').forEach(function(el) {
            var target = parseFloat(el.getAttribute('data-count'));
            if (!isNaN(target)) {
                animateCountUp(el, target);
            }
        });
    });
})();
