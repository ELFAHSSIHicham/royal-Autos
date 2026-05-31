<div class="section" style="max-width:760px;margin:0 auto;padding:40px 20px 80px">

    <div class="sec-h2" style="margin-bottom:8px">Informations <em>légales</em></div>
    <p style="font-size:10px;color:#aaa;letter-spacing:.08em;margin-bottom:48px">
        Dernière mise à jour : <?= date('d/m/Y') ?>
    </p>

    <!-- Tab bar -->
    <div style="display:flex;gap:0;margin-bottom:48px;border-bottom:2px solid #e8e8e8">
        <button onclick="showTab('mentions-legales')" id="tab-mentions-legales"
                style="background:none;border:none;cursor:pointer;padding:12px 24px;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#c9a84c;border-bottom:2px solid #c9a84c;margin-bottom:-2px;font-family:inherit">
            Mentions légales
        </button>
        <button onclick="showTab('confidentialite')" id="tab-confidentialite"
                style="background:none;border:none;cursor:pointer;padding:12px 24px;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#aaa;border-bottom:2px solid transparent;margin-bottom:-2px;font-family:inherit">
            Confidentialité
        </button>
        <button onclick="showTab('cgv')" id="tab-cgv"
                style="background:none;border:none;cursor:pointer;padding:12px 24px;font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:#aaa;border-bottom:2px solid transparent;margin-bottom:-2px;font-family:inherit">
            CGV
        </button>
    </div>

    <!-- Panel: Mentions légales -->
    <div id="panel-mentions-legales">
        <?php $ml = [
                ['Éditeur du site', '
                <strong>Royal Autos Montauban</strong><br>
                Entreprise individuelle<br>
                1279 Avenue de Toulouse, 82000 Montauban<br>
                Tél : <a href="tel:+33652015354" style="color:#c9a84c">06 52 01 53 54</a><br>
                Email : <a href="mailto:royalauto@laposte.net" style="color:#c9a84c">royalauto@laposte.net</a><br>
                SIRET : à compléter<br>
                Responsable de publication : M. El Yahyaoui Mourad
            '],
                ['Hébergement', '
                À compléter avec le nom, l\'adresse et le numéro de téléphone de l\'hébergeur.<br>
                <em style="font-size:10px;color:#aaa">(Obligation légale — art. 6 de la loi n° 2004-575 du 21 juin 2004)</em>
            '],
                ['Propriété intellectuelle', '
                L\'ensemble des contenus présents sur ce site (textes, photographies, visuels, logo, structure) est la propriété exclusive de Royal Autos Montauban ou de leurs auteurs respectifs.<br><br>
                Toute reproduction, représentation, modification, publication ou adaptation de tout ou partie des éléments du site est interdite sans autorisation écrite préalable.<br><br>
                Toute exploitation non autorisée sera poursuivie conformément aux articles L.335-2 et suivants du Code de Propriété Intellectuelle.
            '],
                ['Limitation de responsabilité', '
                Les informations présentées sur les fiches véhicules (photos, caractéristiques, prix) sont données à titre indicatif et peuvent être modifiées sans préavis. Seules les informations communiquées lors de la visite physique du véhicule font foi.
            '],
                ['Droit applicable', '
                Le présent site et ses conditions d\'utilisation sont soumis au droit français. En cas de litige, les tribunaux français seront seuls compétents.
            '],
        ]; ?>
        <?php foreach ($ml as [$titre, $contenu]): ?>
            <div style="margin-bottom:28px">
                <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#3a3a3a;margin-bottom:8px"><?= $titre ?></div>
                <p style="font-size:11px;color:#7a7a7a;line-height:1.9"><?= $contenu ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Panel: Confidentialité -->
    <div id="panel-confidentialite" style="display:none">
        <?php $rgpd = [
                ['Responsable du traitement', '
                Royal Autos Montauban — M. El Yahyaoui Mourad<br>
                1279 Avenue de Toulouse, 82000 Montauban<br>
                Email : <a href="mailto:royalauto@laposte.net" style="color:#c9a84c">royalauto@laposte.net</a>
            '],
                ['Données collectées', '
                — Via le <strong>formulaire de contact</strong> : nom, prénom, email, téléphone, message.<br>
                — Via le <strong>formulaire de réservation</strong> : nom, prénom, email, téléphone, date souhaitée, véhicule concerné.<br>
                — De manière automatique : adresse IP, type de navigateur, pages visitées (données anonymisées).
            '],
                ['Finalités des traitements', '
                Les données collectées sont utilisées exclusivement pour répondre à vos demandes de contact, gérer votre réservation et assurer le bon fonctionnement technique du site.<br><br>
                Aucune donnée n\'est utilisée à des fins commerciales ou de profilage. Aucune donnée n\'est cédée, vendue ou louée à des tiers.
            '],
                ['Durée de conservation', '
                Données de contact : conservées <strong>3 ans</strong> maximum à compter du dernier contact.<br>
                Données de réservation : conservées <strong>5 ans</strong> conformément aux obligations légales et comptables.
            '],
                ['Vos droits (RGPD)', '
                Conformément au RGPD (Règlement UE 2016/679) et à la loi Informatique et Libertés, vous disposez des droits suivants :<br><br>
                — <strong>Droit d\'accès</strong> : obtenir une copie des données vous concernant ;<br>
                — <strong>Droit de rectification</strong> : corriger des données inexactes ;<br>
                — <strong>Droit à l\'effacement</strong> : demander la suppression de vos données ;<br>
                — <strong>Droit d\'opposition</strong> : vous opposer au traitement de vos données.<br><br>
                Pour exercer ces droits : <a href="mailto:royalauto@laposte.net" style="color:#c9a84c">royalauto@laposte.net</a><br><br>
                En cas de réponse insatisfaisante, vous pouvez contacter la <strong>CNIL</strong> :
                <a href="https://www.cnil.fr" target="_blank" rel="noopener" style="color:#c9a84c">www.cnil.fr</a>
            '],
                ['Cookies', '
                Ce site utilise uniquement des <strong>cookies techniques strictement nécessaires</strong> à son fonctionnement (session, sécurité CSRF).<br><br>
                Aucun cookie publicitaire ni outil de tracking n\'est déposé. Ces cookies ne nécessitent pas votre consentement préalable (art. 82 de la loi Informatique et Libertés).
            '],
                ['Sécurité', '
                Les formulaires du site sont protégés par un jeton CSRF. Les données en base sont protégées par requêtes préparées contre les injections SQL.
            '],
        ]; ?>
        <?php foreach ($rgpd as [$titre, $contenu]): ?>
            <div style="margin-bottom:28px">
                <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#3a3a3a;margin-bottom:8px"><?= $titre ?></div>
                <p style="font-size:11px;color:#7a7a7a;line-height:1.9"><?= $contenu ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Panel: CGV -->
    <div id="panel-cgv" style="display:none">
        <?php $cgv = [
                ['Objet', '
                Les présentes Conditions Générales de Vente régissent les relations entre Royal Autos Montauban (ci-après « le Vendeur ») et toute personne souhaitant effectuer une réservation en ligne (ci-après « le Client »).<br><br>
                Toute réservation implique l\'acceptation pleine et entière des présentes CGV.
            '],
                ['Véhicules proposés', '
                Les véhicules présentés sont des véhicules d\'occasion. Les caractéristiques (kilométrage, année, état) sont indiquées à titre informatif sur la base des informations disponibles au moment de la mise en ligne.<br><br>
                Les photographies sont non contractuelles. Le Client est invité à venir vérifier le véhicule sur place avant tout engagement définitif.
            '],
                ['Réservation en ligne', '
                La réservation en ligne permet de bloquer un véhicule pour une durée de <strong>48 heures</strong> moyennant le versement d\'un acompte.<br><br>
                Cette réservation ne constitue pas une vente ferme. Elle garantit que le véhicule ne sera pas proposé à un autre acheteur pendant cette durée.
            '],
                ['Acompte et paiement', '
                L\'acompte versé lors de la réservation est déduit du prix total lors de la transaction finale.<br><br>
                Le paiement en ligne est sécurisé par <strong>Stripe</strong>. Royal Autos Montauban ne stocke à aucun moment vos coordonnées bancaires.<br><br>
                Le solde est réglé directement au garage lors de la remise des clés.
            '],
                ['Rétractation et remboursement', '
                L\'acompte versé est remboursable dans les cas suivants :<br><br>
                — Le véhicule présente une non-conformité significative non mentionnée lors de la réservation ;<br>
                — Le Vendeur n\'est plus en mesure de livrer le véhicule réservé.<br><br>
                En dehors de ces cas, l\'acompte est conservé à titre d\'indemnisation pour immobilisation du véhicule.
            '],
                ['Responsabilité', '
                Royal Autos Montauban ne pourra être tenu responsable des dommages indirects pouvant résulter de l\'utilisation du site ou d\'une réservation en ligne.<br><br>
                En cas de force majeure, Royal Autos Montauban procédera au remboursement intégral de l\'acompte dans un délai de 14 jours ouvrés.
            '],
                ['Litiges', '
                En cas de litige, le Client s\'adressera en priorité à Royal Autos Montauban pour une résolution amiable.<br><br>
                À défaut, le Client peut recourir à un médiateur de la consommation (loi n° 2015-990 du 6 août 2015).<br><br>
                Les présentes CGV sont soumises au droit français.
            '],
        ]; ?>
        <?php foreach ($cgv as [$titre, $contenu]): ?>
            <div style="margin-bottom:28px">
                <div style="font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#3a3a3a;margin-bottom:8px"><?= $titre ?></div>
                <p style="font-size:11px;color:#7a7a7a;line-height:1.9"><?= $contenu ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
    /**
     * Activates the given tab panel and deactivates all others.
     * Updates the URL hash without triggering a page scroll.
     *
     * @param {string} tab - One of 'mentions-legales', 'confidentialite', 'cgv'
     */
    function showTab(tab) {
        const tabs = ['mentions-legales', 'confidentialite', 'cgv'];

        tabs.forEach(function (t) {
            document.getElementById('panel-' + t).style.display = 'none';
            const btn = document.getElementById('tab-' + t);
            btn.style.color = '#aaa';
            btn.style.borderBottomColor = 'transparent';
        });

        document.getElementById('panel-' + tab).style.display = 'block';
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.style.color = '#c9a84c';
        activeBtn.style.borderBottomColor = '#c9a84c';

        history.replaceState(null, '', '#' + tab);
    }

    /**
     * Reads the current URL hash and activates the matching tab.
     * Falls back to the default tab if the hash is absent or invalid.
     */
    function loadFromHash() {
        const hash = window.location.hash.replace('#', '');
        const valid = ['mentions-legales', 'confidentialite', 'cgv'];
        if (valid.includes(hash)) {
            showTab(hash);
        }
    }

    loadFromHash();
    window.addEventListener('hashchange', loadFromHash);
</script>