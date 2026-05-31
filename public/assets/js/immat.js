/* ── Lookup immatriculation → pré-remplit le formulaire ─────────────────── */

document.addEventListener('DOMContentLoaded', () => {

    const inputImmat = document.getElementById('immat-lookup');
    const btnLookup  = document.getElementById('btn-immat-lookup');
    const statusEl   = document.getElementById('immat-status');

    if (!inputImmat || !btnLookup) return;

    /* Normalise : retire accents, met en minuscule, retire espaces superflus */
    const norm = s => (s || '').trim().toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    btnLookup.addEventListener('click', async () => {
        const plaque = inputImmat.value.trim().replace(/[-\s]/g, '').toUpperCase();

        if (!plaque || plaque.length < 5) {
            showStatus('Plaque invalide', 'error');
            return;
        }

        showStatus('Recherche en cours…', 'loading');
        btnLookup.disabled = true;

        try {
            const res  = await fetch(`/api/immat?plaque=${encodeURIComponent(plaque)}`);
            const data = await res.json();

            console.log('[IMMAT] data =', data);

            if (data.error) {
                showStatus('❌ ' + data.error, 'error');
                return;
            }

            /* ── 1. MARQUE ── */
            if (data.marque) {
                const selMarque = document.getElementById('select-marque');
                console.log('[IMMAT] marque API =', data.marque);
                console.log('[IMMAT] options disponibles =', [...selMarque.options].map(o => o.text));

                const optMarque = [...selMarque.options].find(o => norm(o.text) === norm(data.marque));
                console.log('[IMMAT] option trouvée =', optMarque ? optMarque.text : 'AUCUNE');

                if (optMarque) {
                    selMarque.value = optMarque.value;

                    /* Charge les modèles en appelant directement la fonction du form */
                    if (typeof window._chargeModeles === 'function') {
                        await window._chargeModeles(optMarque.value, 0);
                    } else {
                        /* fallback si _chargeModeles pas encore exposé */
                        selMarque.dispatchEvent(new Event('change'));
                        await new Promise(r => setTimeout(r, 800));
                    }

                    /* Sélectionne le modèle dans le select chargé */
                    if (data.modele) {
                        await waitForModeles(data.modele);
                    }
                } else {
                    showStatus(`⚠️ Marque "${data.marque}" non trouvée dans la liste — ajoutez-la manuellement`, 'error');
                    return;
                }
            }

            /* ── 2. MODÈLE texte libre (toujours rempli) ── */
            if (data.modele) {
                const inp = document.getElementById('input-modele');
                if (inp) inp.value = data.modele;
            }

            /* ── 3. ANNÉE ── */
            if (data.annee) {
                const inp = document.querySelector('input[name="annee"]');
                if (inp) inp.value = data.annee;
            }

            /* ── 4. PUISSANCE ── */
            if (data.puissance) {
                const inp = document.querySelector('input[name="puissance"]');
                if (inp) inp.value = data.puissance;
            }

            /* ── 5. CARBURANT (id="input-carburant", options sans value attr) ── */
            if (data.carburant) {
                const sel = document.getElementById('input-carburant');
                if (sel) {
                    const opt = [...sel.options].find(o => norm(o.text) === norm(data.carburant));
                    if (opt) {
                        opt.selected = true;
                        sel.dispatchEvent(new Event('change'));
                    }
                    console.log('[IMMAT] carburant =', data.carburant, '→', opt ? opt.text : 'non trouvé');
                }
            }

            /* ── 6. TRANSMISSION (id="input-transmission") ── */
            if (data.transmission) {
                const sel = document.getElementById('input-transmission');
                if (sel) {
                    const opt = [...sel.options].find(o => norm(o.text) === norm(data.transmission));
                    if (opt) {
                        opt.selected = true;
                        sel.dispatchEvent(new Event('change'));
                    }
                    console.log('[IMMAT] transmission =', data.transmission, '→', opt ? opt.text : 'non trouvé');
                }
            }

            /* ── 7. PORTES ── */
            if (data.portes) {
                const sel = document.querySelector('select[name="portes"]');
                if (sel) {
                    const opt = [...sel.options].find(o => o.value === String(data.portes));
                    if (opt) sel.value = opt.value;
                }
            }

            /* ── 8. PLACES ── */
            if (data.places) {
                const sel = document.querySelector('select[name="places"]');
                if (sel) {
                    const opt = [...sel.options].find(o => o.value === String(data.places));
                    if (opt) sel.value = opt.value;
                }
            }

            /* ── 9. COULEUR (id="input-couleur") ── */
            if (data.couleur) {
                const inp = document.getElementById('input-couleur');
                if (inp) inp.value = data.couleur;
            }

            /* ── 10. MOTORISATION ── */
            if (data.motorisation) {
                const inp = document.querySelector('input[name="motorisation"]');
                if (inp) inp.value = data.motorisation;
            }

            /* ── 11. FINITION ── */
            if (data.finition) {
                const inp = document.querySelector('input[name="finition"]');
                if (inp) inp.value = data.finition;
            }

            /* ── 12. DATES ── */
            if (data.date_mise_circulation) {
                const inp = document.querySelector('input[name="date_mise_circulation"]');
                if (inp) inp.value = data.date_mise_circulation;
            }
            if (data.date_immatriculation) {
                const inp = document.querySelector('input[name="date_immatriculation"]');
                if (inp) inp.value = data.date_immatriculation;
            }

            showStatus('✓ Données récupérées — vérifiez et complétez le kilométrage', 'success');

        } catch (e) {
            console.error('[IMMAT] exception =', e);
            showStatus('Erreur réseau', 'error');
        } finally {
            btnLookup.disabled = false;
        }
    });

    /* Attend que le select modele_id soit rechargé puis sélectionne.
       Comparaison bidirectionnelle : "BERLINGO III" (API) doit matcher "Berlingo" (catalogue) et inversement. */
    async function waitForModeles(modeleNom, attempts = 15) {
        for (let i = 0; i < attempts; i++) {
            await new Promise(r => setTimeout(r, 300));
            const sel = document.getElementById('select-modele');
            if (!sel || sel.options.length <= 1) continue;

            const b = norm(modeleNom);
            const opt = [...sel.options].find(o => {
                const a = norm(o.text);
                return a && (a.includes(b) || b.includes(a));
            });

            if (opt) {
                sel.value = opt.value;
                sel.dispatchEvent(new Event('change'));
                const inputModele = document.getElementById('input-modele');
                if (inputModele) inputModele.value = opt.text;
                console.log('[IMMAT] modèle sélectionné =', opt.text);
                return;
            }
        }
        console.warn('[IMMAT] modèle non trouvé dans le select :', modeleNom);
    }

    function showStatus(msg, type) {
        if (!statusEl) return;
        const colors = { loading: '#888', success: '#2e7d32', error: '#c62828' };
        statusEl.textContent   = msg;
        statusEl.style.color   = colors[type] || '#333';
        statusEl.style.display = 'block';
    }
});