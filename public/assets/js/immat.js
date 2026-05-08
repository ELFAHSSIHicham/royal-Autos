/* ── Lookup immatriculation → pré-remplit le formulaire ─────────────────── */

document.addEventListener('DOMContentLoaded', () => {

    const inputImmat = document.getElementById('immat-lookup');
    const btnLookup  = document.getElementById('btn-immat-lookup');
    const statusEl   = document.getElementById('immat-status');

    if (!inputImmat || !btnLookup) return;

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
            console.log('API response:', data);

            if (data.error) {
                showStatus(data.error, 'error');
                return;
            }

            // ── Marque : trouve l'option dont le texte correspond ──
            if (data.marque) {
                const selMarque = document.querySelector('[name="marque_id"]');
                if (selMarque) {
                    const opt = [...selMarque.options].find(o =>
                        o.text.trim().toLowerCase() === data.marque.trim().toLowerCase()
                    );
                    if (opt) {
                        selMarque.value = opt.value;
                        // Déclenche le rechargement des modèles
                        selMarque.dispatchEvent(new Event('change'));

                        // ── Modèle : attend le rechargement AJAX puis sélectionne ──
                        if (data.modele) {
                            await waitForModeles(data.modele);
                        }
                    }
                }
            }

            // ── Autres champs texte ──
            setField('annee',        data.annee);
            setField('carburant',    data.carburant);
            setField('transmission', data.transmission);
            setField('puissance',    data.puissance);
            setField('couleur',      data.couleur);

            showStatus('✓ Données récupérées — vérifiez et complétez le kilométrage', 'success');

        } catch (e) {
            showStatus('Erreur réseau', 'error');
        } finally {
            btnLookup.disabled = false;
        }
    });

    // Attend que le select modele_id soit rechargé puis sélectionne le bon
    async function waitForModeles(modeleNom, attempts = 10) {
        for (let i = 0; i < attempts; i++) {
            await new Promise(r => setTimeout(r, 300));
            const selModele = document.querySelector('[name="modele_id"]');
            if (!selModele) continue;
            const opt = [...selModele.options].find(o =>
                o.text.trim().toLowerCase().includes(modeleNom.trim().toLowerCase())
            );
            if (opt) {
                selModele.value = opt.value;
                return;
            }
        }
    }

    function setField(name, value) {
        if (!value) return;
        const el = document.querySelector(`[name="${name}"]`);
        if (!el) return;
        el.value = value;
    }

    function showStatus(msg, type) {
        if (!statusEl) return;
        const colors = { loading: '#888', success: '#2e7d32', error: '#c62828' };
        statusEl.textContent   = msg;
        statusEl.style.color   = colors[type] || '#333';
        statusEl.style.display = 'block';
    }
});