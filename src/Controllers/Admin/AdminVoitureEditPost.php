<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer, InputValidator};
use Models\Voiture\Voiture;

class AdminVoitureEditPost implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $id = (int)(explode('/', $_SERVER['REQUEST_URI'])[4] ?? 0);

        $d = [
            'marque_id'       => Sanitizer::int($_POST['marque_id']    ?? 0),
            'modele_id'       => Sanitizer::int($_POST['modele_id']    ?? 0) ?: null,
            'modele'          => Sanitizer::str($_POST['modele']       ?? ''),
            'annee'           => Sanitizer::int($_POST['annee']        ?? 0),
            'prix'            => Sanitizer::float($_POST['prix']       ?? 0),
            'kilometrage'     => Sanitizer::int($_POST['kilometrage']  ?? 0),
            'carburant'       => Sanitizer::str($_POST['carburant']    ?? 'Essence'),
            'transmission'    => Sanitizer::str($_POST['transmission'] ?? 'Manuelle'),
            'puissance'       => Sanitizer::int($_POST['puissance']    ?? 0),
            'couleur'         => Sanitizer::str($_POST['couleur']      ?? ''),
            'portes'          => Sanitizer::int($_POST['portes']       ?? 5),
            'places'          => Sanitizer::int($_POST['places']       ?? 5),
            'description'     => Sanitizer::str($_POST['description']  ?? ''),
            'statut'          => Sanitizer::str($_POST['statut']       ?? 'disponible'),
            'est_vedette'     => isset($_POST['est_vedette']) ? 1 : 0,
            'image_principale'=> Sanitizer::str($_POST['image_actuelle'] ?? ''),
        ];

        // Slug basé sur marque (nom) + modele + annee
        $marques   = Voiture::getMarques();
        $nomMarque = '';
        foreach ($marques as $m) {
            if ((int)$m['id'] === (int)$d['marque_id']) { $nomMarque = $m['nom']; break; }
        }
        $d['slug'] = Sanitizer::slug($nomMarque . '-' . $d['modele'] . '-' . $d['annee']);

        if (!empty($_FILES['image']['tmp_name'])) {
            $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $name = uniqid('car_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../../storage/uploads/' . $name);
            $d['image_principale'] = '/uploads/' . $name;
        }

        Voiture::update($id, $d);
        $_SESSION['flash_success'] = 'Voiture modifiée avec succès.';
        header('Location: /admin/voitures');
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return preg_match('#^/admin/voitures/modifier/\d+$#', $path) && $method === 'POST';
    }
}
