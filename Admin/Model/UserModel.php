<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class UserModel
{
    public static function customers(): array
    {
        $result = db()->query("SELECT id, name, email, created_at FROM users WHERE role='customer' ORDER BY id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function deleteCustomer(int $id): bool
    {
        $db = db();
        $db->begin_transaction();

        try {
            $stmt = $db->prepare("SELECT id FROM users WHERE id=? AND role='customer'");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $exists = (bool)$stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$exists) {
                throw new RuntimeException('Customer not found.');
            }

            $stmt = $db->prepare('DELETE FROM users WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();

            $db->commit();
            return true;
        } catch (Throwable $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function countCustomers(): int
    {
        $row = db()->query("SELECT COUNT(*) AS total FROM users WHERE role='customer'")->fetch_assoc();
        return (int)$row['total'];
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}
