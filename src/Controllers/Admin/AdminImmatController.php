<?php

namespace Controllers\Admin;

/**
 * JSON endpoint to fetch vehicle data from a license plate number.
 * Calls an external API and normalizes the response to match the form fields.
 *
 * @package Controllers\Admin
 */
class AdminImmatController
{
    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/api/immat' && $method === 'GET';
    }

    /**
     * @return void
     */
    public function control(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        /* Vérification de session sans passer par SessionGuard (route API légère) */
        if (empty($_SESSION['admin_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Accès refusé']);
            exit;
        }

        /* Nettoyage et validation du format de plaque française (ex : AB123CD) */
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

        /* Normalisation des champs API vers les noms attendus par le formulaire */
        $data = $d['data'] ?? $d;

        echo json_encode([
            'marque'       => $data['marque']    ?? null,
            'modele'       => $data['modele']    ?? null,
            'annee'        => isset($data['date1erCir_us'])
                ? substr($data['date1erCir_us'], 0, 4)
                : (isset($data['date_mise_en_circulation'])
                    ? substr($data['date_mise_en_circulation'], 0, 4)
                    : null),
            'carburant'    => $this->normaliseCarburant($data['energie'] ?? ''),
            'transmission' => $this->normaliseTransmission($data['boite_de_vitesse'] ?? ''),
            'puissance'    => $data['puissance_din'] ?? null,
            'couleur'      => $data['couleur']       ?? null,
        ]);
        exit;
    }

    /**
     * Maps a raw fuel type string from the API to a normalized French label.
     *
     * @param string $val
     * @return string
     */
    private function normaliseCarburant(string $val): string
    {
        $val = strtolower($val);
        if (str_contains($val, 'diesel') || str_contains($val, 'go')) return 'Diesel';
        if (str_contains($val, 'hybrid'))                              return 'Hybride';
        if (str_contains($val, 'elec'))                                return 'Électrique';
        if (str_contains($val, 'gpl'))                                 return 'GPL';
        return 'Essence';
    }

    /**
     * Maps a raw gearbox string from the API to either 'Automatique' or 'Manuelle'.
     *
     * @param string $val
     * @return string
     */
    private function normaliseTransmission(string $val): string
    {
        return str_contains(strtolower($val), 'auto') ? 'Automatique' : 'Manuelle';
    }
}