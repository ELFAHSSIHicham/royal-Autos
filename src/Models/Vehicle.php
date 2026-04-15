<?php
namespace App\Models;

class Vehicle
{
    public static function all(?string $type = null): array
    {
        $pdo = db();

        if ($type) {
            $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE type = :type ORDER BY id DESC");
            $stmt->execute(['type' => $type]);
            return $stmt->fetchAll();
        }

        return $pdo->query("SELECT * FROM vehicles ORDER BY id DESC")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = db();
        $stmt = $pdo->prepare("
            INSERT INTO vehicles (title, brand, type, price, mileage, year, description)
            VALUES (:title, :brand, :type, :price, :mileage, :year, :description)
        ");
        $stmt->execute([
            'title' => $data['title'],
            'brand' => $data['brand'],
            'type' => $data['type'],
            'price' => $data['price'],
            'mileage' => $data['mileage'],
            'year' => $data['year'] ?: null,
            'description' => $data['description'],
        ]);
    }
}


