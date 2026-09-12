<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class ProductModel
{
    public static function all(): array
    {
        $db = db();
        $result = $db->query('SELECT * FROM products ORDER BY proID DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function find(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM products WHERE proID = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO products (proName, proDesc, proPrice, proImg, proSize, proQuantity, proGender, proCategory)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssdssiss',
            $data['name'], $data['description'], $data['price'], $data['image'],
            $data['size_chart'], $data['stock'], $data['gender'], $data['category']
        );
        $stmt->execute();
        $id = db()->insert_id;
        $stmt->close();
        return (int)$id;
    }

    public static function update(int $id, array $data): bool
    {
        if ($data['image'] !== null) {
            $stmt = db()->prepare(
                'UPDATE products SET proName=?, proDesc=?, proPrice=?, proImg=?, proSize=?, proQuantity=?, proGender=?, proCategory=? WHERE proID=?'
            );
            $stmt->bind_param(
                'ssdssissi',
                $data['name'], $data['description'], $data['price'], $data['image'],
                $data['size_chart'], $data['stock'], $data['gender'], $data['category'], $id
            );
        } else {
            $stmt = db()->prepare(
                'UPDATE products SET proName=?, proDesc=?, proPrice=?, proSize=?, proQuantity=?, proGender=?, proCategory=? WHERE proID=?'
            );
            $stmt->bind_param(
                'ssdssisi',
                $data['name'], $data['description'], $data['price'],
                $data['size_chart'], $data['stock'], $data['gender'], $data['category'], $id
            );
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function delete(int $id): array
    {
        $db = db();
        $check = $db->prepare('SELECT COUNT(*) AS total FROM order_items WHERE product_id=?');
        $check->bind_param('i', $id);
        $check->execute();
        $count = (int)$check->get_result()->fetch_assoc()['total'];
        $check->close();

        if ($count > 0) {
            return ['ok' => false, 'message' => 'This product exists in an order and cannot be deleted.'];
        }

        $product = self::find($id);
        if (!$product) {
            return ['ok' => false, 'message' => 'Product not found.'];
        }

        $stmt = $db->prepare('DELETE FROM products WHERE proID=?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return ['ok' => $ok, 'message' => $ok ? 'Product deleted.' : 'Could not delete product.', 'product' => $product];
    }

    public static function count(): int
    {
        $row = db()->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc();
        return (int)$row['total'];
    }
}
