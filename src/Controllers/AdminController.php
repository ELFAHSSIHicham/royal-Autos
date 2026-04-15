<?php
namespace App\Controllers;

use App\Models\Vehicle;

class AdminController
{
    private function requireAuth(): void
    {
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    public function loginForm(): void
    {
        require __DIR__ . '/../Views/admin_login.php';
    }

    public function loginPost(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // MVP credentials (replace later with users table + password_verify)
        if ($email === 'admin@royal.com' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            header('Location: /admin/vehicles');
            exit;
        }

        $error = "Invalid credentials";
        require __DIR__ . '/../Views/admin_login.php';
    }

    public function vehiclesList(): void
    {
        $this->requireAuth();
        $vehicles = Vehicle::all();
        require __DIR__ . '/../Views/admin_vehicles_list.php';
    }

    public function vehicleCreateForm(): void
    {
        $this->requireAuth();
        require __DIR__ . '/../Views/admin_vehicle_create.php';
    }

    public function vehicleStore(): void
    {
        $this->requireAuth();

        Vehicle::create([
            'title' => $_POST['title'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'type' => $_POST['type'] ?? '',
            'price' => (float)($_POST['price'] ?? 0),
            'mileage' => (int)($_POST['mileage'] ?? 0),
            'year' => (int)($_POST['year'] ?? 0),
            'description' => $_POST['description'] ?? '',
        ]);

        header('Location: /admin/vehicles');
        exit;
    }
}
