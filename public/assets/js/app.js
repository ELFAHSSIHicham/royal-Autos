/* Royal Autos app.js */

document.addEventListener('DOMContentLoaded', () => {

    /* Hamburger menu (mobile) */
    const hamburger = document.getElementById('hamburger');
    const navCenter = document.querySelector('.nav-center');

    if (hamburger && navCenter) {
        hamburger.addEventListener('click', () => {
            const open = navCenter.style.display === 'flex';
            navCenter.style.cssText = open
                ? ''
                : 'display:flex;flex-direction:column;position:absolute;top:64px;left:0;right:0;background:var(--off);border-bottom:1px solid var(--g-l);padding:12px 20px;gap:14px;z-index:99';
        });
    }

    /* Navbar scroll shadow */
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.boxShadow = window.scrollY > 10
                ? '0 2px 20px rgba(0,0,0,.06)'
                : 'none';
        }, { passive: true });
    }

    /* Flash messages auto-hide */
    document.querySelectorAll('[data-flash]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    /* Confirm delete */
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('submit', e => {
            if (!confirm(el.dataset.confirm || 'Confirmer cette action ?')) {
                e.preventDefault();
            }
        });
    });

    /* Card click (catalogue) */
    document.querySelectorAll('.card[data-href]').forEach(card => {
        card.addEventListener('click', () => {
            window.location.href = card.dataset.href;
        });
    });

    /* Image preview (admin form) */
    const imageInput = document.querySelector('input[name="image"]');
    if (imageInput) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                let preview = document.getElementById('img-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'img-preview';
                    preview.style.cssText = 'height:80px;object-fit:cover;margin-top:8px;display:block';
                    imageInput.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    /* Marque → Modèles dynamiques */
    const selectMarque = document.getElementById('select-marque');
    const selectModele = document.getElementById('select-modele');
    const sfModele     = document.getElementById('sf-modele');

    if (selectMarque && selectModele && sfModele) {

        selectMarque.addEventListener('change', async () => {
            const marqueId = selectMarque.value;

            if (!marqueId) {
                sfModele.style.display = 'none';
                selectModele.innerHTML = '<option value="">Tous les modèles</option>';
                return;
            }

            try {
                const res     = await fetch(`/api/modeles?marque_id=${marqueId}`);
                const modeles = await res.json();

                selectModele.innerHTML = '<option value="">Tous les modèles</option>';
                modeles.forEach(m => {
                    const opt       = document.createElement('option');
                    opt.value       = m.id;
                    opt.textContent = m.nom;
                    selectModele.appendChild(opt);
                });

                sfModele.style.display    = 'block';
                sfModele.style.opacity    = '0';
                sfModele.style.transition = 'opacity .25s';
                requestAnimationFrame(() => { sfModele.style.opacity = '1'; });

            } catch (e) {
                console.error('Erreur chargement modèles', e);
            }
        });
    }

});