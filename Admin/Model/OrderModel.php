<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class OrderModel
{
    public static function all(): array
    {
        $sql = "SELECT o.id, u.name AS customer_name, o.total_amount, o.status, o.created_at
                FROM orders o
                INNER JOIN users u ON u.id=o.user_id
                ORDER BY o.created_at DESC";
        $result = db()->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function purchaseHistory(): array
    {
        $sql = "SELECT o.id AS order_id, u.name AS customer_name, u.email,
                       p.proName AS product_name, oi.quantity, oi.unit_price,
                       (oi.quantity * oi.unit_price) AS item_total,
                       o.total_amount, o.status, o.created_at
                FROM orders o
                INNER JOIN users u ON u.id=o.user_id
                INNER JOIN order_items oi ON oi.order_id=o.id
                INNER JOIN products p ON p.proID=oi.product_id
                ORDER BY o.created_at DESC, o.id DESC";
        $result = db()->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $allowed = ['confirmed', 'rejected'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = db()->prepare("UPDATE orders SET status=? WHERE id=? AND status='pending'");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $ok = $stmt->affected_rows === 1;
        $stmt->close();
        return $ok;
    }

    public static function count(): int
    {
        return (int)db()->query('SELECT COUNT(*) AS total FROM orders')->fetch_assoc()['total'];
    }

    public static function pendingCount(): int
    {
        return (int)db()->query("SELECT COUNT(*) AS total FROM orders WHERE status='pending'")->fetch_assoc()['total'];
    }
}
