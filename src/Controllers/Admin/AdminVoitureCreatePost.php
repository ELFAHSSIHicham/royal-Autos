<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer, InputValidator};
use Models\Voiture\Voiture;

class AdminVoitureCreatePost implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $d = $this->extract();
        $v = new InputValidator();
        $v->required('marque_id', (string)$d['marque_id'], 'Marque')
            ->required('modele',    $d['modele'],            'Modèle')
            ->positiveNumber('prix',   $d['prix'])
            ->positiveNumber('annee',  $d['annee']);

        if (!$v->isValid()) {
            $_SESSION['form_errors'] = $v->getErrors();
            $_SESSION['form_old']    = $_POST;
            header('Location: /admin/voitures/nouveau');
            exit();
        }

        // Upload image
        $d['image_principale'] = $this->handleUpload();

        // Slug basé sur marque (nom) + modele + annee
        $marques   = Voiture::getMarques();
        $nomMarque = '';
        foreach ($marques as $m) {
            if ((int)$m['id'] === (int)$d['marque_id']) { $nomMarque = $m['nom']; break; }
        }
        $d['slug'] = Sanitizer::slug($nomMarque . '-' . $d['modele'] . '-' . $d['annee']);

        // ✅ INSERTION en base
        Voiture::create($d);

        $_SESSION['flash_success'] = 'Voiture ajoutée avec succès.';
        header('Location: /admin/voitures');
        exit();
    }

    private function extract(): array
    {
        return [
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
            'description'     => Sanitizer::str($_POST['description']  ?? ''),
            'statut'          => in_array($_POST['statut'] ?? '', ['disponible','reserve','vendu','maintenance'])
                ? $_POST['statut']
                : 'disponible',
            'est_vedette'     => isset($_POST['est_vedette']) ? 1 : 0,
            'image_principale'=> '',
            'slug'            => '',
        ];
    }

    private function handleUpload(): string
    {
        if (empty($_FILES['image']['tmp_name'])) return '';
        $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allow = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allow, true)) return '';
        $name  = uniqid('car_', true) . '.' . $ext;
        $dest  = __DIR__ . '/../../../storage/uploads/' . $name;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        return '/uploads/' . $name;
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures/nouveau' && $method === 'POST';
    }
}