<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

class AdminVoitureEditPost implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $id = (int)(explode('/', $_SERVER['REQUEST_URI'])[4] ?? 0);

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

        // Slug
        $marques   = Voiture::getMarques();
        $nomMarque = '';
        foreach ($marques as $m) {
            if ((int)$m['id'] === (int)$d['marque_id']) { $nomMarque = $m['nom']; break; }
        }
        $d['slug'] = Sanitizer::slug($nomMarque . '-' . $d['modele'] . '-' . $d['annee']);

        // Supprimer les images que l'admin a retirées via le bouton "×"
        $imagesExistantes = array_map('intval', $_POST['images_existantes'] ?? []);
        $toutesImages     = Voiture::getImages($id);
        foreach ($toutesImages as $img) {
            if (!in_array((int)$img['id'], $imagesExistantes, true)) {
                Voiture::deleteImage((int)$img['id']);
            }
        }

        // Sauvegarder les nouvelles photos uploadées
        $photosRestantes = count(Voiture::getImages($id));
        $photos          = $this->normaliseFiles($_FILES['nouvelles_photos'] ?? []);
        $photos          = array_slice($photos, 0, max(0, 40 - $photosRestantes));
        $premiereUrl     = Voiture::saveNewImages($id, $photos, $photosRestantes + 1);

        // Définir l'image principale
        $imagePrincipale = Sanitizer::str($_POST['image_principale_url'] ?? '');
        if (!$imagePrincipale && $premiereUrl) {
            $imagePrincipale = $premiereUrl;
        }
        if (!$imagePrincipale) {
            $remaining       = Voiture::getImages($id);
            $imagePrincipale = $remaining[0]['url'] ?? '';
        }
        $d['image_principale'] = $imagePrincipale;

        Voiture::update($id, $d);

        $_SESSION['flash_success'] = 'Voiture modifiée avec succès.';
        header('Location: /admin/voitures');
        exit();
    }

    private function normaliseFiles(array $files): array
    {
        if (empty($files['tmp_name'])) return [];
        if (!is_array($files['tmp_name'])) return [$files];
        $result = [];
        for ($i = 0, $count = count($files['tmp_name']); $i < $count; $i++) {
            $result[] = [
                'name'     => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
        }
        return $result;
    }

    public static function support(string $path, string $method): bool
    {
        return preg_match('#^/admin/voitures/modifier/\d+$#', $path) && $method === 'POST';
    }
}