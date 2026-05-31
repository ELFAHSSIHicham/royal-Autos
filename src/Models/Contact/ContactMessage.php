<?php

namespace Models\Contact;

use Models\Database;

/**
 * Handles persistence and retrieval of contact form messages.
 *
 * @package Models\Contact
 */
class ContactMessage
{
    /**
     * Inserts a new contact message and returns its ID.
     *
     * @param array<string, string> $d
     * @return int
     */
    public static function create(array $d): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO messages_contact (nom, email, telephone, sujet, message) VALUES (?,?,?,?,?)'
        );
        $stmt->bind_param('sssss', $d['nom'], $d['email'], $d['telephone'], $d['sujet'], $d['message']);
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Returns all contact messages ordered by date descending.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getAll(): array
    {
        $res = Database::getConnection()->query(
            'SELECT * FROM messages_contact ORDER BY created_at DESC'
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Returns the number of unread messages.
     *
     * @return int
     */
    public static function countUnread(): int
    {
        $row = Database::getConnection()
            ->query('SELECT COUNT(*) AS c FROM messages_contact WHERE lu = 0')
            ->fetch_assoc();
        return (int)$row['c'];
    }

    /**
     * Marks a message as read.
     *
     * @param int $id
     * @return void
     */
    public static function markRead(int $id): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('UPDATE messages_contact SET lu = 1 WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
}