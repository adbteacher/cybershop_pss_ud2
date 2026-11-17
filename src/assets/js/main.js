document.addEventListener('DOMContentLoaded', function () {
    var banner = document.getElementById('banner');
    if (banner && window.location.hash) {
        var value = decodeURIComponent(window.location.hash.substring(1));
        banner.innerHTML = 'Bienvenido de nuevo, ' + value;
    }

    var newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = newsletterForm.querySelector('button');
            var original = btn.textContent;
            btn.textContent = '¡Suscrito!';
            newsletterForm.reset();
            setTimeout(function () { btn.textContent = original; }, 2500);
        });
    }
});
