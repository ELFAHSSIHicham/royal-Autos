<?php

namespace Models\User;

use Models\Database;

/**
 * Handles admin user authentication and session data.
 *
 * @package Models\User
 */
class User
{
    /**
     * Finds an admin user by email address.
     *
     * @param string $email
     * @return array<string, mixed>|null
     */
    public static function findByEmail(string $email): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Verifies a plain-text password against a bcrypt hash.
     *
     * @param string $plain
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /**
     * Updates the last_login timestamp for the given admin user.
     *
     * @param int $id
     * @return void
     */
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