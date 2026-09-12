<?php
declare(strict_types=1);

require_once __DIR__ . "/../config/database.php";

class CategoryModel
{
    public static function all(): array
    {
        $result = db()->query(
            "SELECT id, name, parent_id, created_at
             FROM categories ORDER BY parent_id IS NOT NULL, parent_id, name"
        );
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function childrenByGender(string $gender): array
    {
        $stmt = db()->prepare(
            "SELECT c.id, c.name, c.parent_id
             FROM categories c
             INNER JOIN categories g ON c.parent_id=g.id
             WHERE g.name=? ORDER BY c.name"
        );
        $stmt->bind_param("s", $gender);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public static function find(int $id): ?array
    {
        $stmt = db()->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}
