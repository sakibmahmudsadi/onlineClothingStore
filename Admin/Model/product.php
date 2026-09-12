<?php

declare(strict_types=1);

class Product
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(): array
    {
        $sql = "
            SELECT
                proID,
                proName,
                proDesc,
                proPrice,
                proImg,
                proSize,
                proQuantity,
                proCategory
            FROM products
            ORDER BY proID DESC
        ";

        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT
                proID,
                proName,
                proDesc,
                proPrice,
                proImg,
                proSize,
                proQuantity,
                proCategory
            FROM products
            WHERE proID = ?
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function update(
        int $id,
        string $name,
        string $description,
        float $price,
        string $sizeChart,
        int $stock,
        string $category
    ): bool {

        $sql = "
            UPDATE products
            SET
                proName = ?,
                proDesc = ?,
                proPrice = ?,
                proSize = ?,
                proQuantity = ?,
                proCategory = ?
            WHERE proID = ?
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssdsi si",
            $name,
            $description,
            $price,
            $sizeChart,
            $stock,
            $category,
            $id
        );

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM products
            WHERE proID = ?
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function isInOrder(int $id): bool
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM order_details
            WHERE product_id = ?
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return (int)$row['total'] > 0;
    }
}
