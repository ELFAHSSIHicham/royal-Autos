<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

/**
 * Processes the vehicle creation form submission.
 *
 * @package Controllers\Admin
 */
class AdminVoitureCreatePost implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $statuts = ['disponible', 'reserve', 'vendu', 'en_preparation'];

        $d = [
            'marque_id'             => Sanitizer::int($_POST['marque_id']             ?? 0),
            'modele_id'             => Sanitizer::int($_POST['modele_id']             ?? 0) ?: null,
            'modele'                => Sanitizer::str($_POST['modele']                ?? ''),
            'annee'                 => Sanitizer::int($_POST['annee']                 ?? 0),
            'prix'                  => Sanitizer::float($_POST['prix']                ?? 0),
            'kilometrage'           => Sanitizer::int($_POST['kilometrage']           ?? 0),
            'carburant'             => Sanitizer::str($_POST['carburant']             ?? 'Essence'),
            'transmission'          => Sanitizer::str($_POST['transmission']          ?? 'Manuelle'),
            'puissance'             => Sanitizer::int($_POST['puissance']             ?? 0) ?: null,
            'couleur'               => Sanitizer::str($_POST['couleur']               ?? ''),
            'motorisation'          => Sanitizer::str($_POST['motorisation']          ?? ''),
            'finition'              => Sanitizer::str($_POST['finition']              ?? ''),
            'portes'                => Sanitizer::int($_POST['portes']                ?? 5),
            'places'                => Sanitizer::int($_POST['places']                ?? 5),
            'date_mise_circulation' => Sanitizer::str($_POST['date_mise_circulation'] ?? '') ?: null,
            'date_immatriculation'  => Sanitizer::str($_POST['date_immatriculation']  ?? '') ?: null,
            'description'           => Sanitizer::str($_POST['description']           ?? ''),
            'statut'                => in_array($_POST['statut'] ?? '', $statuts) ? $_POST['statut'] : 'disponible',
            'est_vedette'           => isset($_POST['est_vedette']) ? 1 : 0,
            'image_principale'      => '',
            'slug'                  => '',
        ];

        /* Construction du slug à partir de la marque, du modèle et de l'année */
        $nomMarque = '';
        foreach (Voiture::getMarques() as $m) {
            if ((int)$m['id'] === (int)$d['marque_id']) {
                $nomMarque = $m['nom'];
                break;
            }
        }
        $d['slug'] = Sanitizer::slug($nomMarque . '-' . $d['modele'] . '-' . $d['annee']);

        $id = Voiture::create($d);

        /* PHP envoie $_FILES sous forme de tableau inversé — normalisation nécessaire */
        $photos      = array_slice($this->normaliseFiles($_FILES['nouvelles_photos'] ?? []), 0, 40);
        $premiereUrl = Voiture::saveNewImages($id, $photos, 1);

        /* Si aucune image principale choisie, on prend la première uploadée */
        $imagePrincipale = Sanitizer::str($_POST['image_principale_url'] ?? '');
        if (!$imagePrincipale && $premiereUrl) {
            $imagePrincipale = $premiereUrl;
        }
        if ($imagePrincipale) {
            Voiture::update($id, array_merge($d, ['image_principale' => $imagePrincipale]));
        }

        $_SESSION['flash_success'] = 'Véhicule ajouté avec succès.';
        header('Location: /admin/voitures');
        exit();
    }

    /**
     * Normalizes the inverted $_FILES array structure into a flat array of file entries.
     *
     * @param array<string, mixed> $files
     * @return array<int, array<string, mixed>>
     */
    private function normaliseFiles(array $files): array
    {
        if (empty($files['tmp_name'])) return [];
        if (!is_array($files['tmp_name'])) return [$files];

        $result = [];
        $count  = count($files['tmp_name']);
        for ($i = 0; $i < $count; $i++) {
            $result[] = [
                'name'     => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
        }
        return $result;
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures/nouveau' && $method === 'POST';
    }
}