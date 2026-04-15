<?php
namespace App\Controllers;

use App\Models\Vehicle;

class VehicleController
{
    public function index(): void
    {
        $type = $_GET['type'] ?? null;
        $vehicles = Vehicle::all($type);
        require __DIR__ . '/../Views/vehicles_list.php';
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            http_response_code(404);
            echo "Vehicle not found";
            return;
        }

        require __DIR__ . '/../Views/vehicle_show.php';
    }
}


