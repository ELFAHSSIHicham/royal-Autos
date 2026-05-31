<?php

namespace Controllers\Admin;

class AdminImmatController
{
    public static function support(string $path, string $method): bool
    {
        return $path === '/api/immat' && $method === 'GET';
    }

    public function control(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_SESSION['admin_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Accès refusé']);
            exit;
        }

        $plaque = strtoupper(trim($_GET['plaque'] ?? ''));
        $plaque = str_replace('-', '', $plaque);

        if (!preg_match('/^[A-Z]{2}[0-9]{3}[A-Z]{2}$/', $plaque)) {
            echo json_encode(['error' => 'Format invalide — exemple : AB123CD']);
            exit;
        }

        $apiKey = getenv('VEHICULE_API_KEY');
        if (!$apiKey) {
            echo json_encode(['error' => 'Clé API non configurée dans .env']);
            exit;
        }

        $url = 'https://api.apiplaqueimmatriculation.com/plaque?' . http_build_query([
                'immatriculation' => $plaque,
                'token'           => $apiKey,
                'pays'            => 'FR',
            ]);

        $ctx = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => "Accept: application/json\r\n",
            'timeout' => 8,
        ]]);

        $raw = @file_get_contents($url, false, $ctx);

        if (!$raw) {
            echo json_encode(['error' => 'Véhicule introuvable ou quota API dépassé']);
            exit;
        }

        $d = json_decode($raw, true);

        if (!$d || !empty($d['error'])) {
            echo json_encode(['error' => $d['error'] ?? 'Véhicule non trouvé']);
            exit;
        }

        /* Log brut pour debug — à retirer en prod */
        error_log('[ImmatAPI] raw=' . json_encode($d));

        $data = $d['data'] ?? $d;

        /* Dates */
        $dateMC = null;
        foreach (['date1erCir_us','date_mise_en_circulation','date_mise_circulation','date_1ere_circulation'] as $k) {
            if (!empty($data[$k])) { $dateMC = $this->toFrDate($data[$k]); break; }
        }
        $dateIM = null;
        foreach (['date_premiere_immatriculation','date_immatriculation','date_1ere_immatriculation'] as $k) {
            if (!empty($data[$k])) { $dateIM = $this->toFrDate($data[$k]); break; }
        }
        if (!$dateIM && $dateMC) $dateIM = $dateMC;

        /* Année */
        $annee = null;
        foreach (['date1erCir_us','date_mise_en_circulation','date_mise_circulation'] as $k) {
            if (!empty($data[$k])) { $annee = substr($data[$k], 0, 4); break; }
        }

        /* Couleur — essaie tous les champs possibles */
        $couleur = null;
        foreach (['couleur','couleur_vehicule','couleur_exterieure','teinte','color'] as $k) {
            if (!empty($data[$k])) { $couleur = ucfirst(strtolower($data[$k])); break; }
        }

        /* Puissance — la clé réelle est puisFiscReelCH ("102 CH") */
        $puissance = null;
        foreach (['puisFiscReelCH','puissance_din','puissance_fiscale','puissance','ch'] as $k) {
            if (!empty($data[$k])) { $puissance = (int) preg_replace('/\D+/', '', $data[$k]); break; } // "102 CH" -> 102
        }

        /* Portes — nb_portes peut valoir "0" (info absente côté API) */
        $portes = null;
        foreach (['nb_portes','nombre_de_portes','nombre_portes','portes'] as $k) {
            if (!empty($data[$k])) { $portes = (int)$data[$k]; break; }
        }

        /* Places — la clé réelle est nr_passagers */
        $places = null;
        foreach (['nr_passagers','nb_places','nombre_de_places','nombre_places','places','nb_places_assises'] as $k) {
            if (!empty($data[$k])) { $places = (int)$data[$k]; break; }
        }

        echo json_encode([
            'marque'                => $data['marque']       ?? null,
            'modele'                => $data['modele']       ?? $data['modele_en'] ?? null,
            'annee'                 => $annee,
            'carburant'             => $this->normaliseCarburant($data['energieNGC'] ?? $data['type_moteur'] ?? $data['energie'] ?? $data['carburant'] ?? ''),
            'transmission'          => $this->normaliseTransmission($data['boite_vitesse'] ?? $data['boite_de_vitesse'] ?? $data['boite'] ?? $data['transmission'] ?? ''),
            'puissance'             => $puissance,
            'couleur'               => $couleur,
            'motorisation'          => $data['motorisation'] ?? $data['cylindree'] ?? $data['version'] ?? null,
            'finition'              => $data['finition']     ?? $data['version']   ?? null,
            'portes'                => $portes,
            'places'                => $places,
            'date_mise_circulation' => $dateMC,
            'date_immatriculation'  => $dateIM,
        ]);
        exit;
    }

    private function toFrDate(string $val): ?string
    {
        $val = trim($val);
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $val)) return $val;
        if (preg_match('/^(\d{4})[-\/](\d{2})[-\/](\d{2})$/', $val, $m)) return $m[3].'/'.$m[2].'/'.$m[1];
        return null;
    }

    private function normaliseCarburant(string $val): string
    {
        $val = strtolower($val);
        if (str_contains($val, 'diesel') || str_contains($val, 'go')) return 'Diesel';
        if (str_contains($val, 'hybrid'))                              return 'Hybride';
        if (str_contains($val, 'elec'))                                return 'Électrique';
        if (str_contains($val, 'gpl'))                                 return 'GPL';
        return 'Essence';
    }

    private function normaliseTransmission(string $val): string
    {
        $v = strtolower(trim($val));
        if ($v === 'a' || str_contains($v, 'auto')) return 'Automatique';
        // "M", "man", "manuelle", ou valeur inconnue -> Manuelle
        return 'Manuelle';
    }
}