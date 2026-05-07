<?php
namespace Models\User;

use Models\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public static function updateLastLogin(int $id): void
    {
        $db   = Database::getConnection();
        $now  = date('Y-m-d H:i:s');
        $stmt = $db->prepare('UPDATE admins SET last_login = ? WHERE id = ?');
        $stmt->bind_param('si', $now, $id);
        $stmt->execute();
        $stmt->close();
    }
}
